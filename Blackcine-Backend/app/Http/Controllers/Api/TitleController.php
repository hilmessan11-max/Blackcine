<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\SanitizeHelper;
use App\Http\Resources\TitleResource;
use App\Models\Title;
use App\Models\Genre;
use Illuminate\Http\Request;

class TitleController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'year' => 'nullable|integer|min:1900|max:2030',
            'genre' => 'nullable|string|max:100',
            'type' => 'nullable|in:movie,series,classic',
            'page' => 'nullable|integer|min:1',
        ]);

        $query = Title::with(['genres', 'trailers', 'images'])
            ->withRatingAggregates()
            ->when($request->search, fn($q, $s) => SanitizeHelper::whereLike($q, 'name', $s))
            ->when($request->country, fn($q, $c) => $q->where('origin_country', $c))
            ->when($request->year, fn($q, $y) => $q->whereYear('release_date', $y))
            ->when($request->genre, fn($q, $g) => $q->whereHas('genres', fn($gq) => $gq->where('name', $g)))
            ->when($request->type, fn($q, $t) => $q->where('type', $t))
            ->orderBy('created_at', 'desc');

        return TitleResource::collection($query->paginate(20));
    }

    public function films(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'year' => 'nullable|integer|min:1900|max:2030',
            'genre' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
        ]);

        $query = Title::where('type', 'movie')
            ->with(['genres', 'trailers', 'images'])
            ->withRatingAggregates()
            ->when($request->search, fn($q, $s) => SanitizeHelper::whereLike($q, 'name', $s))
            ->when($request->country, fn($q, $c) => $q->where('origin_country', $c))
            ->when($request->year, fn($q, $y) => $q->whereYear('release_date', $y))
            ->when($request->genre, fn($q, $g) => $q->whereHas('genres', fn($gq) => $gq->where('name', $g)))
            ->when($request->category, fn($q, $c) => $q->where('category', $c))
            ->orderBy('created_at', 'desc');

        return TitleResource::collection($query->paginate(20));
    }

    public function series(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'year' => 'nullable|integer|min:1900|max:2030',
            'genre' => 'nullable|string|max:100',
        ]);

        $query = Title::where('type', 'series')
            ->with(['genres', 'trailers', 'images'])
            ->withRatingAggregates()
            ->when($request->search, fn($q, $s) => SanitizeHelper::whereLike($q, 'name', $s))
            ->when($request->country, fn($q, $c) => $q->where('origin_country', $c))
            ->when($request->year, fn($q, $y) => $q->whereYear('release_date', $y))
            ->when($request->genre, fn($q, $g) => $q->whereHas('genres', fn($gq) => $gq->where('name', $g)))
            ->orderBy('created_at', 'desc');

        return TitleResource::collection($query->paginate(20));
    }

    public function classics(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
        ]);

        $query = Title::where('type', 'classic')
            ->with(['genres', 'trailers', 'images'])
            ->withRatingAggregates()
            ->when($request->search, fn($q, $s) => SanitizeHelper::whereLike($q, 'name', $s))
            ->when($request->country, fn($q, $c) => $q->where('origin_country', $c))
            ->orderBy('release_date', 'desc');

        return TitleResource::collection($query->paginate(20));
    }

    public function show($id)
    {
        $title = Title::with(['genres', 'trailers', 'images', 'credits.person', 'seasons.episodes'])
            ->withRatingAggregates()
            ->find($id);

        if (!$title) {
            return response()->json([
                'success' => false,
                'message' => 'Titre non trouvé.'
            ], 404);
        }

        return response()->json(new TitleResource($title));
    }

    public function trailer($id)
    {
        $title = Title::with('trailers')->find($id);

        if (!$title) {
            return response()->json([
                'success' => false,
                'message' => 'Titre non trouvé.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'trailers' => $title->trailers->map(fn($t) => [
                    'id' => $t->id,
                    'title' => $t->title ?? '',
                    'source_type' => $t->source_type ?? 'youtube',
                    'source_url' => $t->source_url,
                ]),
            ],
        ]);
    }

    public function genres()
    {
        $genres = Genre::orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $genres->map(fn($g) => [
                'id' => $g->id,
                'name' => $g->name,
                'slug' => $g->slug,
            ]),
        ]);
    }

    public function countries()
    {
        $countries = Title::whereNotNull('origin_country')
            ->distinct()
            ->pluck('origin_country')
            ->sort()
            ->values();

        return response()->json([
            'success' => true,
            'data' => $countries,
        ]);
    }
}
