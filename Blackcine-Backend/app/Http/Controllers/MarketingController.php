<?php

namespace App\Http\Controllers;

use App\Models\Slide;
use App\Models\Selection;
use App\Models\Notification;
use Illuminate\Http\Request;

class MarketingController extends Controller
{
    public function index()
    {
        $slides = Slide::latest()->get();
        $selections = Selection::latest()->get();
        
        $stats = [
            'total_slides' => Slide::count(),
            'active_slides' => Slide::where('is_active', true)->count(),
            'total_selections' => Selection::count(),
            'active_selections' => Selection::where('is_active', true)->count(),
        ];

        return view('admin.marketing.index', compact('slides', 'selections', 'stats'));
    }

    public function createCampaign(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:slide,selection,notification',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_audience' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        switch ($validated['type']) {
            case 'slide':
                Slide::create($validated);
                break;
            case 'selection':
                Selection::create($validated);
                break;
            case 'notification':
                // Créer une notification pour tous les utilisateurs ou un segment
                // Logique à implémenter selon les besoins
                break;
        }

        return redirect()->route('admin.marketing.index')
            ->with('success', 'Campagne marketing créée avec succès.');
    }
}

