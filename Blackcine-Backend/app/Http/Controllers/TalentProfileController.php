<?php

namespace App\Http\Controllers;

use App\Helpers\SanitizeHelper;
use App\Models\TalentProfile;
use App\Models\Asset;
use Illuminate\Http\Request;

class TalentProfileController extends Controller
{
    public function index(Request $request)
    {
        $query = TalentProfile::with(['user', 'photoAsset']);

        if ($request->has('profession')) {
            $query->where('profession', $request->profession);
        }

        if ($request->has('verification_status')) {
            $query->where('verification_status', $request->verification_status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                SanitizeHelper::whereLike($q, 'first_name', $search);
                SanitizeHelper::orWhereLike($q, 'last_name', $search);
                SanitizeHelper::orWhereLike($q, 'profession', $search);
            });
        }

        $talents = $query->latest()->paginate(20);

        return view('admin.community.talents.index', compact('talents'));
    }

    public function create()
    {
        return view('admin.community.talents.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'profession' => 'nullable|string|max:255',
            'specialties' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'experience_years' => 'nullable|integer|min:0',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'showreel_url' => 'nullable|url',
        ]);

        TalentProfile::create($validated);

        return redirect()->route('admin.community.talents.index')
            ->with('success', 'Profil talent créé avec succès.');
    }

    public function show($id)
    {
        $talent = TalentProfile::with(['user', 'photoAsset', 'cvAsset'])->findOrFail($id);

        return view('admin.community.talents.show', compact('talent'));
    }

    public function edit($id)
    {
        $talent = TalentProfile::findOrFail($id);

        return view('admin.community.talents.edit', compact('talent'));
    }

    public function update(Request $request, $id)
    {
        $talent = TalentProfile::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'profession' => 'nullable|string|max:255',
            'specialties' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'experience_years' => 'nullable|integer|min:0',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'showreel_url' => 'nullable|url',
        ]);

        $talent->update($validated);

        return redirect()->route('admin.community.talents.index')
            ->with('success', 'Profil talent mis à jour.');
    }

    public function destroy($id)
    {
        $talent = TalentProfile::findOrFail($id);
        $talent->delete();

        return redirect()->route('admin.community.talents.index')
            ->with('success', 'Profil talent supprimé.');
    }

    public function verify($id)
    {
        $talent = TalentProfile::findOrFail($id);
        $talent->update([
            'is_verified' => true,
            'verification_status' => 'verified',
            'verified_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Profil talent vérifié avec succès.');
    }

    public function reject(Request $request, $id)
    {
        $talent = TalentProfile::findOrFail($id);

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $talent->update([
            'is_verified' => false,
            'verification_status' => 'rejected',
        ]);

        return redirect()->back()
            ->with('success', 'Profil talent rejeté.');
    }
}

