<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\SanitizeHelper;
use App\Http\Resources\TitleResource;
use App\Models\Serie;
use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * API Séries — Unifiée sur Title (type=series).
 */
class SeriesApiController extends Controller
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

        $query = Title::where('type', 'series')
            ->with(['genres', 'images'])
            ->withRatingAggregates()
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = (string) $request->string('search');
                $q->where(function ($inner) use ($s) {
                    SanitizeHelper::whereLike($inner, 'name', $s);
                    SanitizeHelper::orWhereLike($inner, 'synopsis', $s);
                });
            })
            ->when($request->filled('country'), fn($q) => $q->where('origin_country', $request->country))
            ->when($request->filled('year'), fn($q) => $q->whereYear('release_date', $request->year))
            ->when($request->filled('genre'), fn($q) => $q->whereHas('genres', fn($gq) => $gq->where('name', $request->genre)))
            ->orderBy('created_at', 'desc');

        $titles = $query->paginate(20);
        $mapped = $titles->getCollection()->map(function ($title) {
            $arr = (new TitleResource($title))->toArray(request());
            $arr['title'] = $title->name;
            $arr['overview'] = $title->synopsis;
            $arr['year'] = $title->release_date?->year;
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
        $title = Title::where('type', 'series')->with(['genres', 'images', 'trailers'])->withRatingAggregates()->find($id);
        if ($title) {
            $data = (new TitleResource($title))->toArray(request());
            $data['title'] = $title->name;
            $data['overview'] = $title->synopsis;
            return response()->json(['success' => true, 'data' => $data]);
        }

        $serie = Serie::find($id);
        if (!$serie) {
            return response()->json(['success' => false, 'message' => 'Série non trouvée.'], 404);
        }
        return response()->json(['success' => true, 'data' => $serie]);
    }

    public function store(Request $request)
    {
        if (!auth()->check() || !auth()->user()->hasAnyRole(['SuperAdmin', 'Admin'])) {
            return response()->json(['success' => false, 'message' => 'Accès interdit : rôle administrateur requis.'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:titles,slug',
            'overview' => 'nullable|string',
            'country' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:255',
            'genres' => 'nullable|array',
            'genres.*' => 'string|max:50',
            'seasons' => 'nullable|integer|min:1|max:100',
            'year' => 'nullable|integer|min:1900|max:2030',
            'status' => 'nullable|in:draft,published,archived'
        ]);

        $title = Title::create([
            'name' => $validated['title'],
            'slug' => $validated['slug'] ?? Str::slug($validated['title']),
            'type' => 'series',
            'synopsis' => $validated['overview'] ?? null,
            'origin_country' => $validated['country'] ?? null,
            'original_language' => $validated['language'] ?? null,
            'release_date' => isset($validated['year']) ? sprintf('%04d-01-01', $validated['year']) : null,
            'status' => $validated['status'] ?? 'published',
            'published_at' => now(),
        ]);

        if (!empty($validated['genres'])) {
            $genreIds = [];
            foreach ($validated['genres'] as $gName) {
                $genre = \App\Models\Genre::firstOrCreate(['slug' => Str::slug($gName)], ['name' => $gName]);
                $genreIds[] = $genre->id;
            }
            $title->genres()->sync($genreIds);
        }

        Serie::create([
            'title' => $validated['title'],
            'slug' => $title->slug,
            'overview' => $validated['overview'] ?? null,
            'country' => $validated['country'] ?? null,
            'language' => $validated['language'] ?? null,
            'genres' => $validated['genres'] ?? [],
            'seasons' => $validated['seasons'] ?? null,
            'status' => $validated['status'] ?? 'published',
        ]);

        return response()->json(['success' => true, 'message' => 'Série créée avec succès.', 'data' => (new TitleResource($title->load(['genres'])))->toArray(request())], 201);
    }

    public function update(Request $request, $id)
    {
        if (!auth()->check() || !auth()->user()->hasAnyRole(['SuperAdmin', 'Admin'])) {
            return response()->json(['success' => false, 'message' => 'Accès interdit : rôle administrateur requis.'], 403);
        }

        $title = Title::where('type', 'series')->find($id);
        if (!$title) {
            $serie = Serie::find($id);
            if (!$serie) return response()->json(['success' => false, 'message' => 'Série non trouvée.'], 404);
            $validated = $request->validate([
                'title' => 'sometimes|string|max:255',
                'slug' => 'nullable|string|max:255|unique:series,slug,' . $serie->id,
                'overview' => 'nullable|string',
                'country' => 'nullable|string|max:255',
                'language' => 'nullable|string|max:255',
                'genres' => 'nullable|array',
                'genres.*' => 'string|max:50',
                'seasons' => 'nullable|integer|min:1|max:100',
                'year' => 'nullable|integer|min:1900|max:2030',
                'status' => 'nullable|in:draft,published,archived'
            ]);
            if (array_key_exists('genres', $validated)) $validated['genres'] = $validated['genres'];
            $serie->update($validated);
            return response()->json(['success' => true, 'message' => 'Série mise à jour.', 'data' => $serie->fresh()]);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'slug' => 'nullable|string|max:255|unique:titles,slug,' . $title->id,
            'overview' => 'nullable|string',
            'country' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:255',
            'genres' => 'nullable|array',
            'genres.*' => 'string|max:50',
            'seasons' => 'nullable|integer|min:1|max:100',
            'year' => 'nullable|integer|min:1900|max:2030',
            'status' => 'nullable|in:draft,published,archived'
        ]);

        $update = [];
        if (isset($validated['title'])) $update['name'] = $validated['title'];
        if (array_key_exists('slug', $validated)) $update['slug'] = $validated['slug'] ?? Str::slug($validated['title'] ?? $title->name);
        if (array_key_exists('overview', $validated)) $update['synopsis'] = $validated['overview'];
        if (array_key_exists('country', $validated)) $update['origin_country'] = $validated['country'];
        if (array_key_exists('language', $validated)) $update['original_language'] = $validated['language'];
        if (isset($validated['year'])) $update['release_date'] = sprintf('%04d-01-01', $validated['year']);
        if (isset($validated['status'])) $update['status'] = $validated['status'];
        $title->update($update);

        if (array_key_exists('genres', $validated)) {
            $genreIds = [];
            foreach ($validated['genres'] as $gName) {
                $genre = \App\Models\Genre::firstOrCreate(['slug' => Str::slug($gName)], ['name' => $gName]);
                $genreIds[] = $genre->id;
            }
            $title->genres()->sync($genreIds);
        }

        return response()->json(['success' => true, 'message' => 'Série mise à jour.', 'data' => (new TitleResource($title->fresh()->load(['genres'])))->toArray(request())]);
    }

    public function destroy($id)
    {
        if (!auth()->check() || !auth()->user()->hasAnyRole(['SuperAdmin', 'Admin'])) {
            return response()->json(['success' => false, 'message' => 'Accès interdit : rôle administrateur requis.'], 403);
        }

        $title = Title::where('type', 'series')->find($id);
        if ($title) {
            $title->delete();
            Serie::where('slug', $title->slug)->delete();
            return response()->json(['success' => true, 'message' => 'Série supprimée avec succès']);
        }

        $serie = Serie::find($id);
        if (!$serie) return response()->json(['success' => false, 'message' => 'Série non trouvée.'], 404);
        $serie->delete();
        return response()->json(['success' => true, 'message' => 'Série supprimée avec succès']);
    }
}
