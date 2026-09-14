<?php

namespace App\Http\Controllers;

use App\Models\SeoMeta;
use App\Models\Title;
use App\Models\Article;
use Illuminate\Http\Request;

class SEOController extends Controller
{
    public function index()
    {
        $seoMetas = SeoMeta::latest()->paginate(20);
        
        $stats = [
            'total_meta' => SeoMeta::count(),
            'titles_with_seo' => Title::whereHas('seoMeta')->count(),
            'articles_with_seo' => Article::whereHas('seoMeta')->count(),
        ];

        return view('admin.seo.index', compact('seoMetas', 'stats'));
    }

    public function update(Request $request)
    {
        $allowedTypes = [
            'App\Models\Title' => \App\Models\Title::class,
            'App\Models\Article' => \App\Models\Article::class,
            'App\Models\Video' => \App\Models\Video::class,
        ];

        $validated = $request->validate([
            'seoable_type' => 'required|string|in:' . implode(',', array_keys($allowedTypes)),
            'seoable_id' => 'required|integer',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
            'og_title' => 'nullable|string|max:60',
            'og_description' => 'nullable|string|max:160',
            'og_image_asset_id' => 'nullable|exists:assets,id',
            'canonical_url' => 'nullable|url',
        ]);

        $seoableClass = $allowedTypes[$validated['seoable_type']];
        $seoable = $seoableClass::findOrFail($validated['seoable_id']);

        $seoMeta = SeoMeta::updateOrCreate(
            [
                'seoable_type' => $validated['seoable_type'],
                'seoable_id' => $validated['seoable_id'],
            ],
            $validated
        );

        return redirect()->route('admin.seo.index')
            ->with('success', 'Métadonnées SEO mises à jour avec succès.');
    }
}

