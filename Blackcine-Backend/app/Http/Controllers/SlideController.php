<?php

namespace App\Http\Controllers;

use App\Models\Slide;
use App\Models\Asset;
use App\Models\Title;
use Illuminate\Http\Request;

class SlideController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category', 'all');
        $query = Slide::with(['asset', 'featuredTitle']);

        // Filtrage par catégorie
        if ($category === 'film') {
            $query->films();
        } elseif ($category === 'series') {
            $query->series();
        } elseif ($category === 'general') {
            $query->general();
        }

        $slides = $query->orderBy('display_order')->latest()->paginate(20)->withQueryString();

        // Statistiques par catégorie
        $stats = [
            'all' => Slide::count(),
            'film' => Slide::films()->count(),
            'series' => Slide::series()->count(),
            'general' => Slide::general()->count(),
        ];

        return view('admin.editorial.slides.index', compact('slides', 'category', 'stats'));
    }

    public function create()
    {
        $assets = Asset::where('mime_type', 'like', 'image/%')->latest()->get();
        $films = Title::where('type', 'movie')->published()->orderBy('name')->get();
        $series = Title::where('type', 'series')->published()->orderBy('name')->get();

        return view('admin.editorial.slides.create', compact('assets', 'films', 'series'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:film,series,general',
            'title_id' => 'nullable|required_if:category,film,series|exists:titles,id',
            'cta_label' => 'nullable|string|max:100',
            'cta_url' => 'nullable|url|max:500',
            'asset_id' => 'required|exists:assets,id',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Si category est general, title_id doit être null
        if ($validated['category'] === 'general') {
            $validated['title_id'] = null;
        }

        $validated['is_active'] = $request->has('is_active');

        Slide::create($validated);

        return redirect()->route('admin.editorial.slides.index', ['category' => $validated['category']])
            ->with('success', 'Slide créé avec succès.');
    }

    public function edit($id)
    {
        $slide = Slide::with(['asset', 'featuredTitle'])->findOrFail($id);
        $assets = Asset::where('mime_type', 'like', 'image/%')->latest()->get();
        $films = Title::where('type', 'movie')->published()->orderBy('name')->get();
        $series = Title::where('type', 'series')->published()->orderBy('name')->get();

        return view('admin.editorial.slides.edit', compact('slide', 'assets', 'films', 'series'));
    }

    public function update(Request $request, $id)
    {
        $slide = Slide::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:film,series,general',
            'title_id' => 'nullable|required_if:category,film,series|exists:titles,id',
            'cta_label' => 'nullable|string|max:100',
            'cta_url' => 'nullable|url|max:500',
            'asset_id' => 'required|exists:assets,id',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Si category est general, title_id doit être null
        if ($validated['category'] === 'general') {
            $validated['title_id'] = null;
        }

        $validated['is_active'] = $request->has('is_active');

        $slide->update($validated);

        return redirect()->route('admin.editorial.slides.index', ['category' => $validated['category']])
            ->with('success', 'Slide mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $slide = Slide::findOrFail($id);
        $slide->delete();

        return redirect()->route('admin.editorial.slides.index')
            ->with('success', 'Slide supprimé avec succès.');
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'slides' => 'required|array',
            'slides.*.id' => 'required|exists:slides,id',
            'slides.*.order' => 'required|integer|min:0',
        ]);

        foreach ($request->slides as $slideData) {
            Slide::where('id', $slideData['id'])->update(['display_order' => $slideData['order']]);
        }

        return response()->json(['success' => true]);
    }
}

