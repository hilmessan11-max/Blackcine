<?php

namespace App\Http\Controllers;

use App\Models\Title;
use App\Models\Season;
use App\Models\Episode;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
    public function index()
    {
        $series = Title::where('type', 'series')
            ->with(['seasons.episodes'])
            ->latest()
            ->paginate(15);

        return view('admin.catalog.series.index', compact('series'));
    }

    public function show($id)
    {
        $series = Title::where('type', 'series')
            ->with(['seasons.episodes'])
            ->findOrFail($id);

        return view('admin.catalog.series.show', compact('series'));
    }

    public function createSeason(Request $request, $seriesId)
    {
        $series = Title::where('type', 'series')->findOrFail($seriesId);

        $validated = $request->validate([
            'season_number' => 'required|integer|min:1',
            'name' => 'nullable|string|max:255',
            'release_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'synopsis' => 'nullable|string',
        ]);

        $validated['title_id'] = $series->id;
        Season::create($validated);

        return redirect()->route('admin.catalog.series.show', $series->id)
            ->with('success', 'Saison créée avec succès.');
    }

    public function createEpisode(Request $request, $seriesId, $seasonId)
    {
        $series = Title::where('type', 'series')->findOrFail($seriesId);
        $season = Season::where('title_id', $series->id)->findOrFail($seasonId);

        $validated = $request->validate([
            'episode_number' => 'required|integer|min:1',
            'name' => 'nullable|string|max:255',
            'synopsis' => 'nullable|string',
            'runtime_seconds' => 'nullable|integer|min:0',
            'air_date' => 'nullable|date',
            'is_free' => 'boolean',
        ]);

        $validated['season_id'] = $season->id;
        Episode::create($validated);

        return redirect()->route('admin.catalog.series.show', $series->id)
            ->with('success', 'Épisode créé avec succès.');
    }
}

