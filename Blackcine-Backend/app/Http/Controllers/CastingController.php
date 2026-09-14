<?php

namespace App\Http\Controllers;

use App\Models\Casting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CastingController extends Controller
{
    public function index(Request $request)
    {
        $query = Casting::query();

        if ($request->has('country')) {
            $query->where('country', $request->country);
        }

        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $castings = $query->latest()->paginate(20);

        return view('admin.community.castings.index', compact('castings'));
    }

    public function create()
    {
        return view('admin.community.castings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'role' => 'required|string|max:100',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:draft,published,closed',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['user_id'] = auth()->id();

        Casting::create($validated);

        return redirect()->route('admin.community.castings.index')
            ->with('success', 'Casting créé avec succès.');
    }

    public function edit($id)
    {
        $casting = Casting::findOrFail($id);

        return view('admin.community.castings.edit', compact('casting'));
    }

    public function update(Request $request, $id)
    {
        $casting = Casting::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'role' => 'required|string|max:100',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:draft,published,closed',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        $casting->update($validated);

        return redirect()->route('admin.community.castings.index')
            ->with('success', 'Casting mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $casting = Casting::findOrFail($id);
        $casting->delete();

        return redirect()->route('admin.community.castings.index')
            ->with('success', 'Casting supprimé avec succès.');
    }
}

