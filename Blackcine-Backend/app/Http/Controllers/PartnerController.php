<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::latest()->paginate(15);

        return view('admin.partners.index', compact('partners'));
    }

    public function create()
    {
        return view('admin.partners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'website_url' => 'nullable|url',
            'logo_asset_id' => 'nullable|exists:assets,id',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        Partner::create($validated);

        return redirect()->route('admin.partners.index')
            ->with('success', 'Partenaire ajouté avec succès.');
    }

    public function edit(Partner $partner)
    {
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'website_url' => 'nullable|url',
            'logo_asset_id' => 'nullable|exists:assets,id',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        $partner->update($validated);

        return redirect()->route('admin.partners.index')
            ->with('success', 'Partenaire mis à jour avec succès.');
    }

    public function destroy(Partner $partner)
    {
        $partner->delete();

        return redirect()->route('admin.partners.index')
            ->with('success', 'Partenaire supprimé avec succès.');
    }
}

