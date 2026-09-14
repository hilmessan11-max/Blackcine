<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PushNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PushNotificationController extends Controller
{
    public function index()
    {
        $notifications = PushNotification::latest()->paginate(20);
        return view('admin.notifications.push.index', compact('notifications'));
    }

    public function create()
    {
        return view('admin.notifications.push.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'target_url' => 'nullable|url',
            'image_url' => 'nullable|url',
            'target_audience' => 'required|in:all,subscribers,free_users,specific_users',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        $status = $request->has('send_now') ? 'processing' : ($validated['scheduled_at'] ? 'scheduled' : 'draft');

        $notification = PushNotification::create([
            ...$validated,
            'status' => $status,
            'created_by' => Auth::id(),
        ]);

        if ($request->has('send_now')) {
            // Ici on appellerait le service de push (Firebase, OneSignal, etc.)
            // Pour l'instant on simule l'envoi
            $this->simulateSend($notification);
            return redirect()->route('admin.notifications.push.index')
                ->with('success', 'Notification envoyée avec succès.');
        }

        return redirect()->route('admin.notifications.push.index')
            ->with('success', 'Notification enregistrée.');
    }

    public function edit(PushNotification $push)
    {
        if ($push->status === 'sent' || $push->status === 'processing') {
            return redirect()->route('admin.notifications.push.index')
                ->with('error', 'Impossible de modifier une notification déjà envoyée.');
        }
        return view('admin.notifications.push.edit', compact('push'));
    }

    public function update(Request $request, PushNotification $push)
    {
        if ($push->status === 'sent' || $push->status === 'processing') {
            return back()->with('error', 'Impossible de modifier une notification déjà envoyée.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'target_url' => 'nullable|url',
            'image_url' => 'nullable|url',
            'target_audience' => 'required|in:all,subscribers,free_users,specific_users',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        $push->update($validated);

        if ($request->has('send_now')) {
            $push->update(['status' => 'processing']);
            $this->simulateSend($push);
            return redirect()->route('admin.notifications.push.index')
                ->with('success', 'Notification envoyée avec succès.');
        }

        return redirect()->route('admin.notifications.push.index')
            ->with('success', 'Notification mise à jour.');
    }

    public function destroy(PushNotification $push)
    {
        $push->delete();
        return redirect()->route('admin.notifications.push.index')
            ->with('success', 'Notification supprimée.');
    }

    private function simulateSend(PushNotification $notification)
    {
        // Simulation d'envoi asynchrone
        // Dans la réalité, cela serait un Job
        $notification->update([
            'status' => 'sent',
            'sent_at' => now(),
            'success_count' => rand(100, 5000), // Fake stats
            'failure_count' => rand(0, 50),
        ]);
    }
}
