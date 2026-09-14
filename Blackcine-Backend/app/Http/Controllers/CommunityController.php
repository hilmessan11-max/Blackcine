<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommunityController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('created_at', '>=', now()->subMonth())->count(),
            'banned_users' => 0, // User::whereNotNull('banned_at')->count(), // À implémenter avec migration
        ];

        $users = User::with('roles')
            ->latest()
            ->paginate(20);

        $recentRegistrations = User::latest()->take(10)->get();

        return view('admin.community.index', compact('stats', 'users', 'recentRegistrations'));
    }

    public function banUser(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        $validated = $request->validate([
            'reason' => 'nullable|string|max:500',
            'duration_days' => 'nullable|integer|min:1',
        ]);

        $bannedUntil = isset($validated['duration_days'])
            ? now()->addDays($validated['duration_days'])
            : null;

        $user->update([
            'banned_at' => now(),
            'banned_until' => $bannedUntil,
            'ban_reason' => $validated['reason'] ?? 'Raison non spécifiée',
        ]);

        Notification::create([
            'user_id' => $user->id,
            'type' => 'ban',
            'title' => 'Compte suspendu',
            'body' => 'Votre compte a été suspendu. Raison: ' . ($validated['reason'] ?? 'Non spécifiée'),
        ]);

        return redirect()->route('admin.community.index')
            ->with('success', 'Utilisateur banni avec succès.');
    }
}

