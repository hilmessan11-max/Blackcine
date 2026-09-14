<?php

namespace App\Http\Controllers;

use App\Helpers\SanitizeHelper;
use App\Models\Festival;
use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FestivalController extends Controller
{
    public function index(Request $request)
    {
        $query = Festival::with('titles');

        if ($request->has('country')) {
            $query->where('country', $request->country);
        }

        if ($request->has('search')) {
            SanitizeHelper::whereLike($query, 'name', $request->search);
        }

        $festivals = $query->latest('starts_at')->paginate(20);

        return view('admin.partners.festivals.index', compact('festivals'));
    }

    public function create()
    {
        return view('admin.partners.festivals.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Festival::create($validated);

        return redirect()->route('admin.partners.festivals.index')
            ->with('success', 'Festival créé avec succès.');
    }

    public function edit($id)
    {
        $festival = Festival::with('titles')->findOrFail($id);
        $titles = Title::where('status', 'published')->get();

        return view('admin.partners.festivals.edit', compact('festival', 'titles'));
    }

    public function update(Request $request, $id)
    {
        $festival = Festival::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $festival->update($validated);

        return redirect()->route('admin.partners.festivals.index')
            ->with('success', 'Festival mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $festival = Festival::findOrFail($id);
        $festival->delete();

        return redirect()->route('admin.partners.festivals.index')
            ->with('success', 'Festival supprimé avec succès.');
    }

    public function attachTitle(Request $request, $id)
    {
        $festival = Festival::findOrFail($id);

        $validated = $request->validate([
            'title_id' => 'required|exists:titles,id',
            'section' => 'nullable|string|max:100',
            'year' => 'nullable|integer',
        ]);

        $festival->titles()->attach($validated['title_id'], [
            'section' => $validated['section'] ?? null,
            'year' => $validated['year'] ?? date('Y'),
        ]);

        return redirect()->back()
            ->with('success', 'Film ajouté au festival.');
    }
}

