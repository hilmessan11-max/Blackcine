<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with(['author', 'thumbnail', 'categories', 'tags'])
            ->latest()
            ->paginate(15);

        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('admin.articles.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'body' => 'required|string',
            'thumbnail_asset_id' => 'nullable|exists:assets,id',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['author_id'] = Auth::id();

        if ($validated['status'] === 'published' && !isset($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $article = Article::create($validated);

        if (isset($validated['categories'])) {
            $article->categories()->sync($validated['categories']);
        }

        if (isset($validated['tags'])) {
            $article->tags()->sync($validated['tags']);
        }

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article créé avec succès.');
    }

    public function edit($id)
    {
        $article = Article::with(['categories', 'tags'])->findOrFail($id);
        $categories = Category::all();
        $tags = Tag::all();

        return view('admin.articles.edit', compact('article', 'categories', 'tags'));
    }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'body' => 'required|string',
            'thumbnail_asset_id' => 'nullable|exists:assets,id',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        if ($validated['status'] === 'published' && !isset($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $article->update($validated);

        if (isset($validated['categories'])) {
            $article->categories()->sync($validated['categories']);
        } else {
            $article->categories()->detach();
        }

        if (isset($validated['tags'])) {
            $article->tags()->sync($validated['tags']);
        } else {
            $article->tags()->detach();
        }

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article mis à jour avec succès.');
    }
}

