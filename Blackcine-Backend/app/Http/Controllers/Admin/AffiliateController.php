<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AffiliateProgram;
use Illuminate\Http\Request;

class AffiliateController extends Controller
{
    public function index()
    {
        $programs = AffiliateProgram::latest()->paginate(20);
        return view('admin.advertising.affiliate.index', compact('programs'));
    }

    public function create()
    {
        return view('admin.advertising.affiliate.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'affiliate_id' => 'nullable|string|max:255',
            'base_url' => 'nullable|url',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        AffiliateProgram::create($validated);

        return redirect()->route('admin.advertising.affiliate.index')
            ->with('success', 'Programme d\'affiliation ajouté avec succès.');
    }

    public function edit(AffiliateProgram $program)
    {
        return view('admin.advertising.affiliate.edit', compact('program'));
    }

    public function update(Request $request, AffiliateProgram $program)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'affiliate_id' => 'nullable|string|max:255',
            'base_url' => 'nullable|url',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $program->update($validated);

        return redirect()->route('admin.advertising.affiliate.index')
            ->with('success', 'Programme d\'affiliation mis à jour.');
    }

    public function destroy(AffiliateProgram $program)
    {
        $program->delete();
        return redirect()->route('admin.advertising.affiliate.index')
            ->with('success', 'Programme d\'affiliation supprimé.');
    }
}
