<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\SanitizeHelper;
use App\Http\Controllers\Controller;
use App\Models\Serie;
use App\Models\Asset;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
    public function index(Request $request)
    {
        $query = Serie::query();

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                SanitizeHelper::whereLike($q, 'title', $search);
                SanitizeHelper::orWhereLike($q, 'overview', $search);
                SanitizeHelper::orWhereLike($q, 'creator', $search);
            });
        }

        // Filtre par catégorie/genre
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filtre par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Tri
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'seasons':
                $query->orderBy('seasons', 'desc');
                break;
            default:
                $query->latest();
        }

        $items = $query->paginate(20);

        // Statistiques
        $stats = [
            'new_this_week' => Serie::where('created_at', '>=', now()->subWeek())->count(),
            'ongoing' => Serie::where('status', 'ongoing')->count(),
            'completed' => Serie::where('status', 'completed')->count(),
            'top_rated' => Serie::where('rating', '>=', 4)->count(),
        ];

        return view('admin.series.index', compact('items', 'stats'));
    }

    public function create()
    {
        return view('admin.series.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:series,slug',
            'overview' => 'nullable|string',
            'creator' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:255',
            'genres' => 'nullable|array',
            'category' => 'nullable|string',
            'seasons' => 'nullable|integer',
            'status' => 'nullable|in:ongoing,completed,cancelled',
            'first_air_date' => 'nullable|date',
            'rating' => 'nullable|numeric|min:0|max:10',
            'is_featured' => 'nullable|boolean',
            'poster' => 'nullable|image|max:2048',
        ]);

        $data['genres'] = $request->input('genres', []);

        if ($request->hasFile('poster')) {
            $asset = Asset::create([
                'disk' => 'public',
                'path' => $request->file('poster')->store('posters', 'public'),
                'mime_type' => $request->file('poster')->getMimeType(),
                'size_bytes' => $request->file('poster')->getSize(),
                'status' => 'ready',
            ]);
            $data['poster_asset_id'] = $asset->id;
        }

        unset($data['poster']);
        Serie::create($data);

        return redirect()->route('admin.series.index')->with('success', 'Série créée.');
    }

    public function show($id)
    {
       $serie = Serie::with('trailers')->findOrFail($id);
        return view('admin.series.show', compact('serie'));
    }

    public function edit(Serie $serie)
    {
        return view('admin.series.edit', compact('serie'));
    }

    public function update(Request $request, Serie $serie)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:series,slug,'.$serie->id,
            'overview' => 'nullable|string',
            'creator' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:255',
            'genres' => 'nullable|array',
            'category' => 'nullable|string',
            'seasons' => 'nullable|integer',
            'status' => 'nullable|in:ongoing,completed,cancelled',
            'first_air_date' => 'nullable|date',
        ]);

        $data['genres'] = $request->input('genres', []);
        $serie->update($data);

        return redirect()->route('admin.series.index')->with('success', 'Série mise à jour.');
    }

    public function destroy(Serie $serie)
    {
        $serie->delete();
        return redirect()->route('admin.series.index')->with('success', 'Série supprimée.');
    }

    public function trailers($id)
    {
        $serie = Serie::with('trailers')->findOrFail($id);
        return view('admin.series.trailers', compact('serie'));
    }

    public function storeTrailer(Request $request, $id)
    {
        $serie = Serie::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'source_type' => 'required|in:youtube,vimeo,asset',
            'source_url' => 'required_if:source_type,youtube,vimeo|nullable|url',
            'asset_id' => 'required_if:source_type,asset|nullable|exists:assets,id',
            'is_official' => 'boolean',
        ]);

        $serie->trailers()->create($validated);

        return redirect()->route('admin.series.trailers', $serie->id)
            ->with('success', 'Bande-annonce ajoutée avec succès.');
    }

    public function destroyTrailer($id, $trailerId)
    {
        $serie = Serie::findOrFail($id);
        $serie->trailers()->findOrFail($trailerId)->delete();

        return redirect()->route('admin.series.trailers', $serie->id)
            ->with('success', 'Bande-annonce supprimée avec succès.');
    }
}