<?php

namespace App\Http\Controllers;

use App\Models\Ranking;
use App\Models\RankingEntry;
use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RankingController extends Controller
{
    public function index()
    {
        $rankings = Ranking::with('entries')->latest()->paginate(20);
        return view('admin.rankings.index', compact('rankings'));
    }

    public function create()
    {
        return view('admin.rankings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:general,genre,country,year,custom',
            'genre' => 'nullable|string',
            'country' => 'nullable|string',
            'year' => 'nullable|integer',
            'period' => 'required|in:all_time,year,month,week',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        $ranking = Ranking::create($validated);

        return redirect()->route('admin.rankings.show', $ranking->id)
            ->with('success', 'Classement créé avec succès.');
    }

    public function show($id)
    {
        $ranking = Ranking::with(['entries.title'])->findOrFail($id);
        $availableTitles = Title::whereNotIn('id', $ranking->entries->pluck('title_id'))
            ->orderBy('name')
            ->get();
            
        return view('admin.rankings.show', compact('ranking', 'availableTitles'));
    }

    public function edit($id)
    {
        $ranking = Ranking::findOrFail($id);
        return view('admin.rankings.edit', compact('ranking'));
    }

    public function update(Request $request, $id)
    {
        $ranking = Ranking::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:general,genre,country,year,custom',
            'genre' => 'nullable|string',
            'country' => 'nullable|string',
            'year' => 'nullable|integer',
            'period' => 'required|in:all_time,year,month,week',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        $ranking->update($validated);

        return redirect()->route('admin.rankings.show', $ranking->id)
            ->with('success', 'Classement mis à jour.');
    }

    public function destroy($id)
    {
        $ranking = Ranking::findOrFail($id);
        $ranking->delete();

        return redirect()->route('admin.rankings.index')
            ->with('success', 'Classement supprimé.');
    }

    public function addTitle(Request $request, $id)
    {
        $ranking = Ranking::findOrFail($id);
        
        $validated = $request->validate([
            'title_id' => 'required|exists:titles,id',
            'position' => 'required|integer|min:1',
            'score' => 'nullable|numeric',
        ]);

        // Vérifier si le titre existe déjà dans le classement
        if ($ranking->entries()->where('title_id', $validated['title_id'])->exists()) {
            return red​irect()->back()->withErrors(['title_id' => 'Ce titre est déjà dans le classement.']);
        }

        $ranking->entries()->create($validated);

        return redirect()->back()->with('success', 'Titre ajouté au classement.');
    }

    public function removeTitle($rankingId, $entryId)
    {
        $entry = RankingEntry::where('ranking_id', $rankingId)
            ->where('id', $entryId)
            ->firstOrFail();
            
        $entry->delete();

        return redirect()->back()->with('success', 'Titre retiré du classement.');
    }

    public function updatePositions(Request $request, $id)
    {
        $ranking = Ranking::findOrFail($id);
        
        $validated = $request->validate([
            'positions' => 'required|array',
            'positions.*' => 'required|integer|min:1',
        ]);

        foreach ($validated['positions'] as $entryId => $position) {
            $entry = RankingEntry::where('ranking_id', $id)
                ->where('id', $entryId)
                ->first();
                
            if ($entry) {
                $entry->updatePosition($position);
            }
        }

        return redirect()->back()->with('success', 'Positions mises à jour.');
    }

    public function recalculateScores($id)
    {
        $ranking = Ranking::findOrFail($id);
        $ranking->recalculateScores();

        return redirect()->back()->with('success', 'Scores recalculés.');
    }

    public function toggleActive($id)
    {
        $ranking = Ranking::findOrFail($id);
        $ranking->update(['is_active' => !$ranking->is_active]);

        return redirect()->back()->with('success', 'Statut modifié.');
    }
}
