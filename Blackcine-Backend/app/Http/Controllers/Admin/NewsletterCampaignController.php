<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsletterCampaignController extends Controller
{
    public function index()
    {
        $campaigns = NewsletterCampaign::with('creator')->latest()->paginate(15);
        
        $stats = [
            'total' => NewsletterCampaign::count(),
            'sent' => NewsletterCampaign::where('status', 'sent')->count(),
            'scheduled' => NewsletterCampaign::where('status', 'scheduled')->count(),
            'draft' => NewsletterCampaign::where('status', 'draft')->count(),
        ];

        return view('admin.newsletter.campaigns.index', compact('campaigns', 'stats'));
    }

    public function create()
    {
        $subscribersCount = NewsletterSubscriber::where('is_active', true)->count();
        return view('admin.newsletter.campaigns.create', compact('subscribersCount'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'from_name' => 'nullable|string|max:255',
            'from_email' => 'nullable|email',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['status'] = $request->has('send_now') ? 'sending' : ($validated['scheduled_at'] ? 'scheduled' : 'draft');
        $validated['total_recipients'] = NewsletterSubscriber::where('is_active', true)->count();

        $campaign = NewsletterCampaign::create($validated);

        if ($request->has('send_now')) {
            // Ici on appellerait le service d'envoi (Queue, Job, etc.)
            // Pour l'instant on simule
            $this->simulateSend($campaign);
            return redirect()->route('admin.newsletter.campaigns.index')
                ->with('success', 'Campagne envoyée avec succès.');
        }

        return redirect()->route('admin.newsletter.campaigns.index')
            ->with('success', 'Campagne créée avec succès.');
    }

    public function show(NewsletterCampaign $campaign)
    {
        return view('admin.newsletter.campaigns.show', compact('campaign'));
    }

    public function edit(NewsletterCampaign $campaign)
    {
        if ($campaign->status === 'sent' || $campaign->status === 'sending') {
            return redirect()->route('admin.newsletter.campaigns.index')
                ->with('error', 'Impossible de modifier une campagne envoyée.');
        }

        $subscribersCount = NewsletterSubscriber::where('is_active', true)->count();
        return view('admin.newsletter.campaigns.edit', compact('campaign', 'subscribersCount'));
    }

    public function update(Request $request, NewsletterCampaign $campaign)
    {
        if ($campaign->status === 'sent' || $campaign->status === 'sending') {
            return back()->with('error', 'Impossible de modifier une campagne envoyée.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'from_name' => 'nullable|string|max:255',
            'from_email' => 'nullable|email',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        $campaign->update($validated);

        if ($request->has('send_now')) {
            $campaign->update(['status' => 'sending']);
            $this->simulateSend($campaign);
            return redirect()->route('admin.newsletter.campaigns.index')
                ->with('success', 'Campagne envoyée avec succès.');
        }

        return redirect()->route('admin.newsletter.campaigns.index')
            ->with('success', 'Campagne mise à jour.');
    }

    public function destroy(NewsletterCampaign $campaign)
    {
        $campaign->delete();
        return redirect()->route('admin.newsletter.campaigns.index')
            ->with('success', 'Campagne supprimée.');
    }

    public function send(NewsletterCampaign $campaign)
    {
        if ($campaign->status === 'sent' || $campaign->status === 'sending') {
            return back()->with('error', 'Cette campagne a déjà été envoyée.');
        }

        $campaign->update(['status' => 'sending']);
        $this->simulateSend($campaign);

        return redirect()->route('admin.newsletter.campaigns.index')
            ->with('success', 'Campagne envoyée avec succès.');
    }

    public function duplicate(NewsletterCampaign $campaign)
    {
        $newCampaign = $campaign->replicate();
        $newCampaign->name = $campaign->name . ' (Copie)';
        $newCampaign->status = 'draft';
        $newCampaign->sent_at = null;
        $newCampaign->sent_count = 0;
        $newCampaign->opened_count = 0;
        $newCampaign->clicked_count = 0;
        $newCampaign->created_by = Auth::id();
        $newCampaign->save();

        return redirect()->route('admin.newsletter.campaigns.edit', $newCampaign)
            ->with('success', 'Campagne dupliquée avec succès.');
    }

    public function stats()
    {
        $stats = [
            'total_campaigns' => NewsletterCampaign::count(),
            'total_sent' => NewsletterCampaign::where('status', 'sent')->count(),
            'total_subscribers' => NewsletterSubscriber::where('is_active', true)->count(),
            'avg_open_rate' => NewsletterCampaign::where('status', 'sent')
                ->where('sent_count', '>', 0)
                ->avg(\DB::raw('(opened_count / sent_count) * 100')),
            'avg_click_rate' => NewsletterCampaign::where('status', 'sent')
                ->where('sent_count', '>', 0)
                ->avg(\DB::raw('(clicked_count / sent_count) * 100')),
        ];

        $recentCampaigns = NewsletterCampaign::where('status', 'sent')
            ->latest('sent_at')
            ->take(10)
            ->get();

        return view('admin.newsletter.stats.index', compact('stats', 'recentCampaigns'));
    }

    private function simulateSend(NewsletterCampaign $campaign)
    {
        // Simulation d'envoi - dans la réalité ce serait un Job en queue
        $campaign->update([
            'status' => 'sent',
            'sent_at' => now(),
            'sent_count' => $campaign->total_recipients,
            'opened_count' => rand(50, $campaign->total_recipients * 0.7), // Fake stats
            'clicked_count' => rand(10, $campaign->total_recipients * 0.3),
        ]);
    }
}
