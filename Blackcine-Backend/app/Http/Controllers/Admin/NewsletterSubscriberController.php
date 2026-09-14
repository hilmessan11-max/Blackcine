<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterSubscriberController extends Controller
{
    public function index()
    {
        $subscribers = NewsletterSubscriber::latest()->paginate(20);
        
        $stats = [
            'total' => NewsletterSubscriber::count(),
            'active' => NewsletterSubscriber::where('is_active', true)->count(),
            'inactive' => NewsletterSubscriber::where('is_active', false)->count(),
            'this_month' => NewsletterSubscriber::whereMonth('subscribed_at', now()->month)->count(),
        ];

        return view('admin.newsletter.subscribers.index', compact('subscribers', 'stats'));
    }

    public function create()
    {
        return view('admin.newsletter.subscribers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:newsletter_subscribers,email',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
        ]);

        $validated['is_active'] = $request->has('is_active');

        NewsletterSubscriber::create($validated);

        return redirect()->route('admin.newsletter.subscribers.index')
            ->with('success', 'Abonné ajouté avec succès.');
    }

    public function edit(NewsletterSubscriber $subscriber)
    {
        return view('admin.newsletter.subscribers.edit', compact('subscriber'));
    }

    public function update(Request $request, NewsletterSubscriber $subscriber)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:newsletter_subscribers,email,' . $subscriber->id,
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $subscriber->update($validated);

        return redirect()->route('admin.newsletter.subscribers.index')
            ->with('success', 'Abonné mis à jour.');
    }

    public function destroy(NewsletterSubscriber $subscriber)
    {
        $subscriber->delete();
        return redirect()->route('admin.newsletter.subscribers.index')
            ->with('success', 'Abonné supprimé.');
    }

    public function export()
    {
        $subscribers = NewsletterSubscriber::where('is_active', true)->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="abonnes_newsletter_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($subscribers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Email', 'Prénom', 'Nom', 'Source', 'Date d\'inscription']);

            foreach ($subscribers as $subscriber) {
                fputcsv($file, [
                    $subscriber->email,
                    $subscriber->first_name,
                    $subscriber->last_name,
                    $subscriber->source,
                    $subscriber->subscribed_at?->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
