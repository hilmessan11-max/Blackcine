<?php

namespace App\Http\Controllers;

use App\Models\Selection;
use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SelectionController extends Controller
{
    public function index()
    {
        $selections = Selection::orderBy('display_order')->latest()->paginate(20);

        return view('admin.editorial.selections.index', compact('selections'));
    }

    public function create()
    {
        return view('admin.editorial.selections.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:coup_de_coeur,carousel,not_to_miss',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_active'] = $request->has('is_active');

        Selection::create($validated);

        return redirect()->route('admin.editorial.selections.index')
            ->with('success', 'Sélection créée avec succès.');
    }

    public function edit($id)
    {
        $selection = Selection::with('tags')->findOrFail($id);
        $titles = Title::where('status', 'published')->get();

        return view('admin.editorial.selections.edit', compact('selection', 'titles'));
    }

    public function update(Request $request, $id)
    {
        $selection = Selection::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:coup_de_coeur,carousel,not_to_miss',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_active'] = $request->has('is_active');

        $selection->update($validated);

        return redirect()->route('admin.editorial.selections.index')
            ->with('success', 'Sélection mise à jour avec succès.');
    }

    public function destroy($id)
    {
        $selection = Selection::findOrFail($id);
        $selection->delete();

        return redirect()->route('admin.editorial.selections.index')
            ->with('success', 'Sélection supprimée avec succès.');
    }
}

