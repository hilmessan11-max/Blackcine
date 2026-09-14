<?php

namespace App\Http\Controllers;

use App\Helpers\SanitizeHelper;
use App\Models\Contest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContestController extends Controller
{
    public function index(Request $request)
    {
        $query = Contest::with('user');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('contest_type')) {
            $query->where('contest_type', $request->contest_type);
        }

        if ($request->has('search')) {
            SanitizeHelper::whereLike($query, 'title', $request->search);
        }

        $contests = $query->latest()->paginate(20);

        return view('admin.community.contests.index', compact('contests'));
    }

    public function create()
    {
        return view('admin.community.contests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'contest_type' => 'required|in:film_festival,short_film,script,acting,technical',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'registration_deadline' => 'required|date|before:start_date',
            'status' => 'required|in:draft,open,closed,judging,completed',
            'prize_description' => 'nullable|string',
            'jury_info' => 'nullable|string',
            'rules' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['user_id'] = auth()->id();
        $validated['is_active'] = $request->has('is_active');

        Contest::create($validated);

        return redirect()->route('admin.community.contests.index')
            ->with('success', 'Concours créé avec succès.');
    }

    public function edit($id)
    {
        $contest = Contest::findOrFail($id);

        return view('admin.community.contests.edit', compact('contest'));
    }

    public function update(Request $request, $id)
    {
        $contest = Contest::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'contest_type' => 'required|in:film_festival,short_film,script,acting,technical',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'registration_deadline' => 'required|date|before:start_date',
            'status' => 'required|in:draft,open,closed,judging,completed',
            'prize_description' => 'nullable|string',
            'jury_info' => 'nullable|string',
            'rules' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_active'] = $request->has('is_active');

        $contest->update($validated);

        return redirect()->route('admin.community.contests.index')
            ->with('success', 'Concours mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $contest = Contest::findOrFail($id);
        $contest->delete();

        return redirect()->route('admin.community.contests.index')
            ->with('success', 'Concours supprimé avec succès.');
    }
}

