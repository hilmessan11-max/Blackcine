<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Helpers\SanitizeHelper;
use App\Models\Title;
use App\Models\Article;
use App\Models\Emission;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2|max:100',
            'type' => 'nullable|in:all,films,series,articles,emissions',
        ]);

        $query = $request->input('q');
        $type = $request->input('type', 'all');
        $results = [];
        $total = 0;

        if ($type === 'all' || $type === 'films') {
            $filmsQuery = Title::where('type', 'movie')->where('status', 'published');
            try {
                if (config('database.default') === 'mysql' && \Illuminate\Support\Facades\Schema::hasTable('titles')) {
                    $films = (clone $filmsQuery)->whereFullText(['name', 'synopsis'], $query)->limit(10)->get();
                    if ($films->isEmpty()) throw new \Exception('fallback');
                } else {
                    throw new \Exception('fallback');
                }
            } catch (\Throwable $e) {
                $films = $filmsQuery->where(function ($q) use ($query) {
                    SanitizeHelper::whereLike($q, 'name', $query);
                    SanitizeHelper::orWhereLike($q, 'synopsis', $query);
                })->limit(10)->get();
            }

            $results['films'] = $films;
            $total += $films->count();
        }

        if ($type === 'all' || $type === 'series') {
            $seriesQuery = Title::where('type', 'series')->where('status', 'published');
            try {
                if (config('database.default') === 'mysql') {
                    $series = (clone $seriesQuery)->whereFullText(['name', 'synopsis'], $query)->limit(10)->get();
                    if ($series->isEmpty()) throw new \Exception('fallback');
                } else {
                    throw new \Exception('fallback');
                }
            } catch (\Throwable $e) {
                $series = $seriesQuery->where(function ($q) use ($query) {
                    SanitizeHelper::whereLike($q, 'name', $query);
                    SanitizeHelper::orWhereLike($q, 'synopsis', $query);
                })->limit(10)->get();
            }

            $results['series'] = $series;
            $total += $series->count();
        }

        if ($type === 'all' || $type === 'articles') {
            // Colonnes réelles: title, excerpt, body (pas de `content`)
            $articlesQuery = Article::where('status', 'published');
            try {
                if (config('database.default') === 'mysql') {
                    $articles = (clone $articlesQuery)->whereFullText(['title', 'excerpt', 'body'], $query)->limit(10)->get();
                    if ($articles->isEmpty()) throw new \Exception('fallback');
                } else {
                    throw new \Exception('fallback');
                }
            } catch (\Throwable $e) {
                $articles = $articlesQuery->where(function ($q) use ($query) {
                    SanitizeHelper::whereLike($q, 'title', $query);
                    SanitizeHelper::orWhereLike($q, 'excerpt', $query);
                    SanitizeHelper::orWhereLike($q, 'body', $query);
                })->limit(10)->get();
            }

            $results['articles'] = $articles;
            $total += $articles->count();
        }

        if ($type === 'all' || $type === 'emissions') {
            $emissions = Emission::where(function ($q) use ($query) {
                    SanitizeHelper::whereLike($q, 'title', $query);
                    SanitizeHelper::orWhereLike($q, 'description', $query);
                })
                ->limit(10)
                ->get();

            $results['emissions'] = $emissions;
            $total += $emissions->count();
        }

        return response()->json([
            'success' => true,
            'query' => $query,
            'total' => $total,
            'data' => $results
        ]);
    }
}
