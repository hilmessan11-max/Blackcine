<?php

namespace App\Http\Controllers;

use App\Helpers\SanitizeHelper;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with('user');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('project_type')) {
            $query->where('project_type', $request->project_type);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                SanitizeHelper::whereLike($q, 'title', $search);
                SanitizeHelper::orWhereLike($q, 'description', $search);
            });
        }

        $projects = $query->latest()->paginate(20);

        return view('admin.community.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.community.projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'project_type' => 'required|in:film,series,documentary,short_film,commercial',
            'status' => 'required|in:draft,casting,pre_production,production,post_production,completed',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'budget_range' => 'nullable|string|max:50',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'needs_crew' => 'boolean',
            'needs_equipment' => 'boolean',
            'needs_location' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['user_id'] = auth()->id();
        $validated['is_active'] = $request->has('is_active');
        $validated['needs_crew'] = $request->has('needs_crew');
        $validated['needs_equipment'] = $request->has('needs_equipment');
        $validated['needs_location'] = $request->has('needs_location');

        Project::create($validated);

        return redirect()->route('admin.community.projects.index')
            ->with('success', 'Projet créé avec succès.');
    }

    public function edit($id)
    {
        $project = Project::findOrFail($id);

        return view('admin.community.projects.edit', compact('project'));
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'project_type' => 'required|in:film,series,documentary,short_film,commercial',
            'status' => 'required|in:draft,casting,pre_production,production,post_production,completed',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'budget_range' => 'nullable|string|max:50',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'needs_crew' => 'boolean',
            'needs_equipment' => 'boolean',
            'needs_location' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_active'] = $request->has('is_active');
        $validated['needs_crew'] = $request->has('needs_crew');
        $validated['needs_equipment'] = $request->has('needs_equipment');
        $validated['needs_location'] = $request->has('needs_location');

        $project->update($validated);

        return redirect()->route('admin.community.projects.index')
            ->with('success', 'Projet mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->route('admin.community.projects.index')
            ->with('success', 'Projet supprimé avec succès.');
    }
}

