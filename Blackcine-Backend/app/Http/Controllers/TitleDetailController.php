<?php

namespace App\Http\Controllers;

use App\Helpers\SanitizeHelper;
use App\Models\Title;
use Illuminate\Http\Request;

class TitleDetailController extends Controller
{
    public function index(Request $request)
    {
        $query = Title::with(['genres', 'credits.person']);

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                SanitizeHelper::whereLike($q, 'name', $search);
                SanitizeHelper::orWhereLike($q, 'synopsis', $search);
            });
        }

        // Filtre par type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filtre par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Tri
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'views':
                $query->orderBy('views_count', 'desc');
                break;
            default:
                $query->latest();
        }

        $items = $query->paginate(20);

        // Statistiques
        $stats = [
            'films' => Title::where('type', 'film')->count(),
            'series' => Title::where('type', 'series')->count(),
            'published' => Title::where('status', 'published')->count(),
            'featured' => Title::where('is_featured', true)->count(),
        ];

        return view('admin.titles.index', compact('items', 'stats'));
    }

    public function create()
    {
        $types = ['movie' => 'Film', 'series' => 'Série', 'classic' => 'Classique'];
        $statuses = ['draft' => 'Brouillon', 'published' => 'Publié', 'archived' => 'Archivé'];
        
        return view('admin.titles.create', compact('types', 'statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:movie,series,classic',
            'synopsis' => 'nullable|string',
            'status' => 'required|in:draft,published,archived',
            'release_date' => 'nullable|date',
            'poster_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $title = Title::create($validated);

        return redirect()->route('admin.titles.show', $title->id)
            ->with('success', 'Titre créé avec succès.');
    }

    public function show($id)
    {
        $title = Title::with([
            'genres',
            'credits.person',
            'trailers',
            'subtitles',
            'seasons.episodes',
            'seoMeta',
            'images'
        ])->findOrFail($id);

        $persons = \App\Models\Person::orderBy('name')->get();

        return view('admin.titles.show', compact('title', 'persons'));
    }

    public function feature($id)
    {
        $title = Title::findOrFail($id);
        // Logique pour mettre en avant
        return redirect()->back()->with('success', 'Titre mis en avant.');
    }

    public function addToSelection(Request $request, $id)
    {
        $title = Title::findOrFail($id);
        // Logique pour ajouter à une sélection
        return redirect()->back()->with('success', 'Titre ajouté à la sélection.');
    }

    public function markNowShowing($id)
    {
        $title = Title::findOrFail($id);
        // Logique pour marquer "à l'affiche"
        return redirect()->back()->with('success', 'Titre marqué comme "à l\'affiche".');
    }

    public function storeCredit(Request $request, $id)
    {
        $title = Title::findOrFail($id);
        
        $validated = $request->validate([
            'person_id' => 'required|exists:people,id',
            'department' => 'required|string',
            'job' => 'required|string',
            'character_name' => 'nullable|string',
            'credit_order' => 'nullable|integer',
        ]);

        $title->credits()->create($validated);

        return redirect()->route('admin.titles.show', $title->id)
            ->with('success', 'Crédit ajouté avec succès.');
    }

    public function destroyCredit($id, $creditId)
    {
        $title = Title::findOrFail($id);
        $title->credits()->findOrFail($creditId)->delete();

        return redirect()->route('admin.titles.show', $title->id)
            ->with('success', 'Crédit supprimé avec succès.');
    }

    public function storeTrailer(Request $request, $id)
    {
        $title = Title::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'source_type' => 'required|in:youtube,vimeo,asset',
            'source_url' => 'required_if:source_type,youtube,vimeo|nullable|url',
            'asset_id' => 'required_if:source_type,asset|nullable|exists:assets,id',
            'is_official' => 'boolean',
        ]);

        $title->trailers()->create($validated);

        return redirect()->route('admin.titles.show', $title->id)
            ->with('success', 'Bande-annonce ajoutée avec succès.');
    }

    public function destroyTrailer($id, $trailerId)
    {
        $title = Title::findOrFail($id);
        $title->trailers()->findOrFail($trailerId)->delete();

        return redirect()->route('admin.titles.show', $title->id)
            ->with('success', 'Bande-annonce supprimée avec succès.');
    }

    public function storeSubtitle(Request $request, $id)
    {
        $title = Title::findOrFail($id);
        
        $validated = $request->validate([
            'language' => 'required|string|max:5',
            'format' => 'required|in:vtt,srt',
            'asset_id' => 'required|exists:assets,id',
        ]);

        $title->subtitles()->create($validated);

        return redirect()->route('admin.titles.show', $title->id)
            ->with('success', 'Sous-titre ajouté avec succès.');
    }

    public function destroySubtitle($id, $subtitleId)
    {
        $title = Title::findOrFail($id);
        $title->subtitles()->findOrFail($subtitleId)->delete();

        return redirect()->route('admin.titles.show', $title->id)
            ->with('success', 'Sous-titre supprimé avec succès.');
    }

    public function storeImage(Request $request, $id)
    {
        $title = Title::findOrFail($id);
        
        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
        ]);

        $title->images()->attach($validated['asset_id'], ['context' => 'gallery']);

        return redirect()->route('admin.titles.show', $title->id)
            ->with('success', 'Image ajoutée à la galerie.');
    }

    public function destroyImage($id, $assetId)
    {
        $title = Title::findOrFail($id);
        $title->images()->detach($assetId);

        return redirect()->route('admin.titles.show', $title->id)
            ->with('success', 'Image retirée de la galerie.');
    }

    public function edit($id)
    {
        $title = Title::findOrFail($id);
        $types = ['movie' => 'Film', 'series' => 'Série', 'classic' => 'Classique'];
        $statuses = ['draft' => 'Brouillon', 'published' => 'Publié', 'archived' => 'Archivé'];

        return view('admin.titles.edit', compact('title', 'types', 'statuses'));
    }

    public function update(Request $request, $id)
    {
        $title = Title::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:movie,series,classic',
            'synopsis' => 'nullable|string',
            'status' => 'required|in:draft,published,archived',
            'release_date' => 'nullable|date',
            'poster_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $title->update($validated);

        return redirect()->route('admin.titles.show', $title->id)
            ->with('success', 'Titre mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $title = Title::findOrFail($id);
        $title->delete();

        return redirect()->route('admin.titles.index')
            ->with('success', 'Titre supprimé avec succès.');
    }
}