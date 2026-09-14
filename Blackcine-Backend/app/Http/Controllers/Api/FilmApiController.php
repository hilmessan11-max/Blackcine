<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\SanitizeHelper;
use App\Http\Resources\TitleResource;
use App\Models\Film;
use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * API Films — Unifiée sur Title (type=movie).
 * Film (legacy) gardé en lecture seule pour compatibilité.
 */
class FilmApiController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'year' => 'nullable|integer|min:1900|max:2030',
            'genre' => 'nullable|string|max:100',
            'page' => 'nullable|integer|min:1',
        ]);

        $query = Title::where('type', 'movie')
            ->with(['genres', 'images'])
            ->withRatingAggregates()
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = (string) $request->string('search');
                $q->where(function ($inner) use ($s) {
                    SanitizeHelper::whereLike($inner, 'name', $s);
                    SanitizeHelper::orWhereLike($inner, 'synopsis', $s);
                    SanitizeHelper::orWhereLike($inner, 'origin_country', $s);
                });
            })
            ->when($request->filled('country'), fn($q) => $q->where('origin_country', $request->country))
            ->when($request->filled('year'), fn($q) => $q->whereYear('release_date', $request->year))
            ->when($request->filled('genre'), fn($q) => $q->whereHas('genres', fn($gq) => $gq->where('name', $request->genre)))
            ->orderBy('created_at', 'desc');

        $titles = $query->paginate(20);

        // Retourne TitleResource avec aliases Film pour rétro-compat (title/overview/year)
        $mapped = $titles->getCollection()->map(function ($title) {
            $arr = (new TitleResource($title))->toArray(request());
            $arr['title'] = $title->name;
            $arr['overview'] = $title->synopsis;
            $arr['year'] = $title->release_date?->year;
            $arr['country'] = $title->origin_country;
            return $arr;
        });

        return response()->json([
            'success' => true,
            'data' => $mapped,
            'pagination' => [
                'current_page' => $titles->currentPage(),
                'last_page' => $titles->lastPage(),
                'per_page' => $titles->perPage(),
                'total' => $titles->total(),
            ],
        ]);
    }

    public function show($id)
    {
        // Tente Title d'abord, fallback Film legacy
        $title = Title::where('type', 'movie')->with(['genres', 'images', 'trailers'])->withRatingAggregates()->find($id);
        if ($title) {
            $data = (new TitleResource($title))->toArray(request());
            $data['title'] = $title->name;
            $data['overview'] = $title->synopsis;
            $data['year'] = $title->release_date?->year;
            return response()->json(['success' => true, 'data' => $data]);
        }

        $film = Film::with('trailers')->find($id);
        if (!$film) {
            return response()->json(['success' => false, 'message' => 'Film non trouvé.'], 404);
        }

        return response()->json(['success' => true, 'data' => $film]);
    }

    public function store(Request $request)
    {
        if (!auth()->check() || !auth()->user()->hasAnyRole(['SuperAdmin', 'Admin'])) {
            return response()->json(['success' => false, 'message' => 'Accès interdit : rôle administrateur requis.'], 403);
        }

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:titles,slug'],
            'overview' => ['nullable', 'string'],
            'country' => ['nullable', 'string', 'max:255'],
            'language' => ['nullable', 'string', 'max:255'],
            'genres' => ['nullable', 'array'],
            'genres.*' => ['string', 'max:50'],
            'category' => ['nullable', 'in:nouveaute,a_l_affiche,a_venir,par_pays,par_langue,box_office,court_web,classiques_africains,tous'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:2030'],
        ]);

        $title = Title::create([
            'name' => $data['title'],
            'slug' => $data['slug'] ?? Str::slug($data['title']),
            'type' => 'movie',
            'synopsis' => $data['overview'] ?? null,
            'origin_country' => $data['country'] ?? null,
            'original_language' => $data['language'] ?? null,
            'release_date' => isset($data['year']) ? sprintf('%04d-01-01', $data['year']) : null,
            'status' => 'published',
            'published_at' => now(),
        ]);

        if (!empty($data['genres'])) {
            $genreIds = [];
            foreach ($data['genres'] as $gName) {
                $genre = \App\Models\Genre::firstOrCreate(['slug' => Str::slug($gName)], ['name' => $gName]);
                $genreIds[] = $genre->id;
            }
            $title->genres()->sync($genreIds);
        }

        // Garde compatibilité : crée aussi entrée legacy Film (optionnel, pour outils qui lisent films table)
        Film::create([
            'title' => $data['title'],
            'slug' => $title->slug,
            'overview' => $data['overview'] ?? null,
            'country' => $data['country'] ?? null,
            'language' => $data['language'] ?? null,
            'genres' => $data['genres'] ?? [],
            'category' => $data['category'] ?? null,
            'year' => $data['year'] ?? null,
            'status' => 'published',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Film créé avec succès.',
            'data' => (new TitleResource($title->load(['genres', 'images'])))->toArray(request()),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        if (!auth()->check() || !auth()->user()->hasAnyRole(['SuperAdmin', 'Admin'])) {
            return response()->json(['success' => false, 'message' => 'Accès interdit : rôle administrateur requis.'], 403);
        }

        $title = Title::where('type', 'movie')->find($id);
        $filmLegacy = null;
        if (!$title) {
            $filmLegacy = Film::find($id);
            if (!$filmLegacy) {
                return response()->json(['success' => false, 'message' => 'Film non trouvé.'], 404);
            }
        }

        $data = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:titles,slug,' . ($title?->id ?? '')],
            'overview' => ['nullable', 'string'],
            'country' => ['nullable', 'string', 'max:255'],
            'language' => ['nullable', 'string', 'max:255'],
            'genres' => ['nullable', 'array'],
            'genres.*' => ['string', 'max:50'],
            'category' => ['nullable', 'in:nouveaute,a_l_affiche,a_venir,par_pays,par_langue,box_office,court_web,classiques_africains,tous'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:2030'],
        ]);

        if ($title) {
            $update = [];
            if (isset($data['title'])) $update['name'] = $data['title'];
            if (array_key_exists('slug', $data)) $update['slug'] = $data['slug'] ?? Str::slug($data['title'] ?? $title->name);
            if (array_key_exists('overview', $data)) $update['synopsis'] = $data['overview'];
            if (array_key_exists('country', $data)) $update['origin_country'] = $data['country'];
            if (array_key_exists('language', $data)) $update['original_language'] = $data['language'];
            if (isset($data['year'])) $update['release_date'] = sprintf('%04d-01-01', $data['year']);
            $title->update($update);

            if (array_key_exists('genres', $data)) {
                $genreIds = [];
                foreach ($data['genres'] as $gName) {
                    $genre = \App\Models\Genre::firstOrCreate(['slug' => Str::slug($gName)], ['name' => $gName]);
                    $genreIds[] = $genre->id;
                }
                $title->genres()->sync($genreIds);
            }

            return response()->json([
                'success' => true,
                'message' => 'Film mis à jour.',
                'data' => (new TitleResource($title->fresh()->load(['genres', 'images'])))->toArray(request()),
            ]);
        }

        // Fallback legacy
        $updateLegacy = [];
        foreach (['title','slug','overview','country','language','category','year'] as $k) {
            if (array_key_exists($k, $data)) $updateLegacy[$k] = $data[$k];
        }
        if (array_key_exists('genres', $data)) $updateLegacy['genres'] = $data['genres'];
        $filmLegacy->update($updateLegacy);

        return response()->json(['success' => true, 'message' => 'Film mis à jour.', 'data' => $filmLegacy->fresh()]);
    }

    public function destroy($id)
    {
        if (!auth()->check() || !auth()->user()->hasAnyRole(['SuperAdmin', 'Admin'])) {
            return response()->json(['success' => false, 'message' => 'Accès interdit : rôle administrateur requis.'], 403);
        }

        $title = Title::where('type', 'movie')->find($id);
        if ($title) {
            $title->delete();
            Film::where('slug', $title->slug)->delete();
            return response()->json(['success' => true, 'message' => 'Film supprimé.']);
        }

        $film = Film::find($id);
        if (!$film) {
            return response()->json(['success' => false, 'message' => 'Film non trouvé.'], 404);
        }
        $film->delete();

        return response()->json(['success' => true, 'message' => 'Film supprimé.']);
    }
}
