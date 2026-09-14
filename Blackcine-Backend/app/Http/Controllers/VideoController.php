<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    /**
     * Afficher la liste des vidéos
     */
    public function index()
    {
        $videos = Video::with(['file', 'thumbnail', 'categories', 'tags'])
            ->latest()
            ->paginate(15);

        return view('admin.videos.index', compact('videos'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        
        return view('admin.videos.create', compact('categories', 'tags'));
    }

    /**
     * Enregistrer une nouvelle vidéo
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'source_type' => 'required|in:upload,external,youtube,vimeo',
            'source_url' => 'nullable|url',
            'asset_id' => 'nullable|exists:assets,id',
            'thumbnail_asset_id' => 'nullable|exists:assets,id',
            'duration_seconds' => 'nullable|integer|min:0',
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

        $video = Video::create($validated);

        if (isset($validated['categories'])) {
            $video->categories()->sync($validated['categories']);
        }

        if (isset($validated['tags'])) {
            $video->tags()->sync($validated['tags']);
        }

        return redirect()->route('admin.videos.index')
            ->with('success', 'Vidéo créée avec succès.');
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        $video = Video::with(['categories', 'tags'])->findOrFail($id);
        $categories = Category::all();
        $tags = Tag::all();
        
        return view('admin.videos.edit', compact('video', 'categories', 'tags'));
    }

    /**
     * Mettre à jour une vidéo
     */
    public function update(Request $request, $id)
    {
        $video = Video::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'source_type' => 'required|in:upload,external,youtube,vimeo',
            'source_url' => 'nullable|url',
            'asset_id' => 'nullable|exists:assets,id',
            'thumbnail_asset_id' => 'nullable|exists:assets,id',
            'duration_seconds' => 'nullable|integer|min:0',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        if ($validated['status'] === 'published' && !$video->published_at && !isset($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $video->update($validated);

        if (isset($validated['categories'])) {
            $video->categories()->sync($validated['categories']);
        } else {
            $video->categories()->detach();
        }

        if (isset($validated['tags'])) {
            $video->tags()->sync($validated['tags']);
        } else {
            $video->tags()->detach();
        }

        return redirect()->route('admin.videos.index')
            ->with('success', 'Vidéo mise à jour avec succès.');
    }

    /**
     * Supprimer une vidéo
     */
    public function destroy($id)
    {
        $video = Video::findOrFail($id);
        
        // Détacher les relations
        $video->categories()->detach();
        $video->tags()->detach();
        
        $video->delete();

        return redirect()->route('admin.videos.index')
            ->with('success', 'Vidéo supprimée avec succès.');
    }

    /**
     * Upload rapide de vidéo (méthode existante)
     */
    public function upload(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'source_type' => 'required|in:upload,external,youtube,vimeo',
            'source_url' => 'nullable|url',
            'asset_id' => 'nullable|exists:assets,id',
            'thumbnail_asset_id' => 'nullable|exists:assets,id',
            'duration_seconds' => 'nullable|integer|min:0',
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

        $video = Video::create($validated);

        if (isset($validated['categories'])) {
            $video->categories()->sync($validated['categories']);
        }

        if (isset($validated['tags'])) {
            $video->tags()->sync($validated['tags']);
        }

        return redirect()->route('admin.videos.index')
            ->with('success', 'Vidéo uploadée avec succès.');
    }
}