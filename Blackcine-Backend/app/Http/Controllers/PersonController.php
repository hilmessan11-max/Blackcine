<?php

namespace App\Http\Controllers;

use App\Helpers\SanitizeHelper;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PersonController extends Controller
{
    public function index(Request $request)
    {
        $query = Person::query();

        if ($request->has('search')) {
            SanitizeHelper::whereLike($query, 'name', $request->search);
        }

        $persons = $query->latest()->paginate(20);

        return view('admin.persons.index', compact('persons'));
    }

    public function create()
    {
        return view('admin.persons.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:255',
            'biography' => 'nullable|string',
            'photo_asset_id' => 'nullable|exists:assets,id',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $person = Person::create($validated);

        return redirect()->route('admin.persons.index')
            ->with('success', 'Personne ajoutée avec succès.');
    }

    public function edit($id)
    {
        $person = Person::findOrFail($id);
        return view('admin.persons.edit', compact('person'));
    }

    public function update(Request $request, $id)
    {
        $person = Person::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:255',
            'biography' => 'nullable|string',
            'photo_asset_id' => 'nullable|exists:assets,id',
        ]);

        if ($person->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $person->update($validated);

        return redirect()->route('admin.persons.index')
            ->with('success', 'Personne mise à jour avec succès.');
    }

    public function destroy($id)
    {
        $person = Person::findOrFail($id);
        $person->delete();

        return redirect()->route('admin.persons.index')
            ->with('success', 'Personne supprimée avec succès.');
    }
}
