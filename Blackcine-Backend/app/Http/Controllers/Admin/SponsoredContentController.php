<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SponsoredContent;
use Illuminate\Http\Request;

class SponsoredContentController extends Controller
{
    public function index()
    {
        $sponsoredContents = SponsoredContent::latest()->paginate(20);
        return view('admin.advertising.sponsored.index', compact('sponsoredContents'));
    }

    public function create()
    {
        return view('admin.advertising.sponsored.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sponsor_name' => 'required|string|max:255',
            'campaign_name' => 'nullable|string|max:255',
            'legal_mention' => 'required|string|max:255',
            'agreed_amount' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,pending,ended',
            'contract_details' => 'nullable|string',
            'tracking_pixel_url' => 'nullable|url',
        ]);

        SponsoredContent::create($validated);

        return redirect()->route('admin.advertising.sponsored.index')
            ->with('success', 'Contenu sponsorisé ajouté avec succès.');
    }

    public function edit(SponsoredContent $sponsoredContent)
    {
        return view('admin.advertising.sponsored.edit', compact('sponsoredContent'));
    }

    public function update(Request $request, SponsoredContent $sponsoredContent)
    {
        $validated = $request->validate([
            'sponsor_name' => 'required|string|max:255',
            'campaign_name' => 'nullable|string|max:255',
            'legal_mention' => 'required|string|max:255',
            'agreed_amount' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,pending,ended',
            'contract_details' => 'nullable|string',
            'tracking_pixel_url' => 'nullable|url',
        ]);

        $sponsoredContent->update($validated);

        return redirect()->route('admin.advertising.sponsored.index')
            ->with('success', 'Contenu sponsorisé mis à jour.');
    }

    public function destroy(SponsoredContent $sponsoredContent)
    {
        $sponsoredContent->delete();
        return redirect()->route('admin.advertising.sponsored.index')
            ->with('success', 'Contenu sponsorisé supprimé.');
    }
}
