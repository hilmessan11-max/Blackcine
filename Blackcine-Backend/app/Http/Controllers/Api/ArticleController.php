<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\SanitizeHelper;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'category' => 'nullable|string|max:100',
            'search' => 'nullable|string|max:100',
            'limit' => 'nullable|integer|min:1|max:50',
        ]);

        $query = Article::where('status', 'published')
            ->orderBy('published_at', 'desc');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $s = $request->search;
                SanitizeHelper::whereLike($q, 'title', $s);
                SanitizeHelper::orWhereLike($q, 'excerpt', $s);
            });
        }

        $limit = min((int) $request->input('limit', 10), 50);
        $articles = $query->limit($limit)->get();

        return response()->json([
            'success' => true,
            'data' => ArticleResource::collection($articles),
        ]);
    }

    public function show($id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json([
                'success' => false,
                'message' => 'Article non trouvé.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new ArticleResource($article),
        ]);
    }
}
