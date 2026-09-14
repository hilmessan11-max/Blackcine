<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\SanitizeHelper;
use App\Http\Controllers\Controller;
use App\Models\Film;
use App\Models\Asset;
use Illuminate\Http\Request;

class FilmController extends Controller
{
    public function index(Request $request)
    {
        $query = Film::query();

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                SanitizeHelper::whereLike($q, 'title', $search);
                SanitizeHelper::orWhereLike($q, 'overview', $search);
                SanitizeHelper::orWhereLike($q, 'country', $search);
            });
        }

        // Filtre par catégorie
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filtre par année
        if ($request->filled('year')) {
            $query->where('release_year', $request->year);
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
            default:
                $query->latest();
        }

        $items = $query->paginate(20);

        // Statistiques
        $stats = [
            'new_this_week' => Film::where('created_at', '>=', now()->subWeek())->count(),
            'top_rated' => Film::where('rating', '>=', 4)->count(),
            'featured' => Film::where('is_featured', true)->count(),
        ];

        return view('admin.films.index', compact('items', 'stats'));
    }

    public function create()
    {
        return view('admin.films.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:films,slug',
            'overview' => 'nullable|string',
            'country' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:255',
            'genres' => 'nullable|array',
            'category' => 'nullable|in:nouveaute,a_l_affiche,a_venir,par_pays,par_langue,box_office,court_web,classiques_africains,tous',
            'year' => 'nullable|integer',
            'duration_minutes' => 'nullable|integer|min:0',
            'rating' => 'nullable|numeric|min:0|max:10',
            'is_featured' => 'nullable|boolean',
            'poster' => 'nullable|image|max:2048',
            'creator' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'first_air_date' => 'nullable|date',
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
        Film::create($data);

        return redirect()->route('admin.films.index')->with('success', 'Film créé.');
    }

    public function show($id)
    {
        $film = Film::with('trailers')->findOrFail($id);
        return view('admin.films.show', compact('film'));
    }

    public function edit(Film $film)
    {
        return view('admin.films.edit', compact('film'));
    }

    public function update(Request $request, Film $film)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:films,slug,'.$film->id,
            'overview' => 'nullable|string',
            'country' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:255',
            'genres' => 'nullable|array',
            'category' => 'nullable|in:nouveaute,a_l_affiche,a_venir,par_pays,par_langue,box_office,court_web,classiques_africains,tous',
            'year' => 'nullable|integer',
        ]);

        $data['genres'] = $request->input('genres', []);
        $film->update($data);

        return redirect()->route('admin.films.index')->with('success', 'Film mis à jour.');
    }

    public function destroy(Film $film)
    {
        $film->delete();
        return redirect()->route('admin.films.index')->with('success', 'Film supprimé.');
    }

    public function trailers($id)
    {
        $film = Film::with('trailers')->findOrFail($id);
        return view('admin.films.trailers', compact('film'));
    }

    public function storeTrailer(Request $request, $id)
    {
        $film = Film::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'source_type' => 'required|in:youtube,vimeo,asset',
            'source_url' => 'required_if:source_type,youtube,vimeo|nullable|url',
            'asset_id' => 'required_if:source_type,asset|nullable|exists:assets,id',
            'is_official' => 'boolean',
        ]);

        $film->trailers()->create($validated);

        return redirect()->route('admin.films.trailers', $film->id)
            ->with('success', 'Bande-annonce ajoutée avec succès.');
    }

    public function destroyTrailer($id, $trailerId)
    {
        $film = Film::findOrFail($id);
        $film->trailers()->findOrFail($trailerId)->delete();

        return redirect()->route('admin.films.trailers', $film->id)
            ->with('success', 'Bande-annonce supprimée avec succès.');
    }
}