<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CinemaController extends Controller
{
    public function index()
    {
        $cinemas = Cinema::with('rooms')->latest()->paginate(20);

        return view('admin.boxoffice.cinemas.index', compact('cinemas'));
    }

    public function create()
    {
        return view('admin.boxoffice.cinemas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        Cinema::create($validated);

        return redirect()->route('admin.boxoffice.cinemas.index')
            ->with('success', 'Cinéma créé avec succès.');
    }

    public function edit($id)
    {
        $cinema = Cinema::with('rooms')->findOrFail($id);

        return view('admin.boxoffice.cinemas.edit', compact('cinema'));
    }

    public function update(Request $request, $id)
    {
        $cinema = Cinema::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        $cinema->update($validated);

        return redirect()->route('admin.boxoffice.cinemas.index')
            ->with('success', 'Cinéma mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $cinema = Cinema::findOrFail($id);
        $cinema->delete();

        return redirect()->route('admin.boxoffice.cinemas.index')
            ->with('success', 'Cinéma supprimé avec succès.');
    }

    public function rooms($id)
    {
        $cinema = Cinema::findOrFail($id);
        $rooms = $cinema->rooms()->latest()->get();

        if (request()->wantsJson()) {
            return response()->json($rooms);
        }

        return view('admin.boxoffice.cinemas.rooms', compact('cinema', 'rooms'));
    }

    public function storeRoom(Request $request, $id)
    {
        $cinema = Cinema::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'screen_type' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $validated['cinema_id'] = $cinema->id;
        $validated['is_active'] = $request->has('is_active');

        Room::create($validated);

        return redirect()->route('admin.boxoffice.cinemas.rooms', $cinema->id)
            ->with('success', 'Salle créée avec succès.');
    }
}

