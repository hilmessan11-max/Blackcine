<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\SanitizeHelper;
use App\Models\Emission;
use Illuminate\Http\Request;

class EmissionsApiController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'year' => 'nullable|integer|min:1900|max:2030',
            'status' => 'nullable|string|max:50',
            'is_live' => 'nullable|boolean',
            'sort' => 'nullable|in:title,views,duration,broadcast_date,created_at',
            'page' => 'nullable|integer|min:1',
        ]);

        $query = Emission::query();

        if ($request->filled('search')) {
            SanitizeHelper::whereLike($query, 'title', $request->search);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('country')) {
            SanitizeHelper::whereLike($query, 'country', $request->country);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('is_live')) {
            $query->where('is_live', $request->boolean('is_live'));
        }

        $sortBy = $request->get('sort', 'created_at');
        $sortColumn = match ($sortBy) {
            'title' => 'title',
            'views' => 'views',
            'duration' => 'duration',
            'broadcast_date' => 'broadcast_date',
            default => 'created_at',
        };
        $query->orderBy($sortColumn, $sortBy === 'views' ? 'desc' : 'desc');

        $emissions = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $emissions->items(),
            'pagination' => [
                'current_page' => $emissions->currentPage(),
                'last_page' => $emissions->lastPage(),
                'per_page' => $emissions->perPage(),
                'total' => $emissions->total(),
            ],
        ]);
    }

    public function show($id)
    {
        $emission = Emission::find($id);

        if (!$emission) {
            return response()->json([
                'success' => false,
                'message' => 'Émission non trouvée.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $emission,
        ]);
    }

    public function store(Request $request)
    {
        if (!auth()->check() || !auth()->user()->hasAnyRole(['SuperAdmin', 'Admin'])) {
            return response()->json(['success' => false, 'message' => 'Accès interdit : rôle administrateur requis.'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'duration' => 'nullable|integer|min:0',
            'presenter' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
            'language' => 'nullable|string|max:50',
            'year' => 'nullable|integer|min:1900|max:2030',
            'status' => 'nullable|in:draft,published,archived',
            'thumbnail' => 'nullable|string|max:500',
            'video_url' => 'nullable|url|max:500',
            'is_live' => 'nullable|boolean',
            'broadcast_date' => 'nullable|date'
        ]);

        $emission = Emission::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Émission créée avec succès.',
            'data' => $emission,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        if (!auth()->check() || !auth()->user()->hasAnyRole(['SuperAdmin', 'Admin'])) {
            return response()->json(['success' => false, 'message' => 'Accès interdit : rôle administrateur requis.'], 403);
        }

        $emission = Emission::find($id);

        if (!$emission) {
            return response()->json([
                'success' => false,
                'message' => 'Émission non trouvée.'
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'duration' => 'nullable|integer|min:0',
            'presenter' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
            'language' => 'nullable|string|max:50',
            'year' => 'nullable|integer|min:1900|max:2030',
            'status' => 'nullable|in:draft,published,archived',
            'thumbnail' => 'nullable|string|max:500',
            'video_url' => 'nullable|url|max:500',
            'is_live' => 'nullable|boolean',
            'broadcast_date' => 'nullable|date'
        ]);

        $emission->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Émission mise à jour.',
            'data' => $emission->fresh(),
        ]);
    }

    public function destroy($id)
    {
        if (!auth()->check() || !auth()->user()->hasAnyRole(['SuperAdmin', 'Admin'])) {
            return response()->json(['success' => false, 'message' => 'Accès interdit : rôle administrateur requis.'], 403);
        }

        $emission = Emission::find($id);

        if (!$emission) {
            return response()->json([
                'success' => false,
                'message' => 'Émission non trouvée.'
            ], 404);
        }

        $emission->delete();

        return response()->json([
            'success' => true,
            'message' => 'Émission supprimée avec succès.',
        ]);
    }

    public function upcoming()
    {
        $upcomingEmissions = Emission::where('broadcast_date', '>', now())
            ->orderBy('broadcast_date', 'asc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $upcomingEmissions,
        ]);
    }

    public function featured()
    {
        $featuredEmissions = Emission::orderBy('views', 'desc')
            ->limit(4)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $featuredEmissions,
        ]);
    }

    public function live()
    {
        $liveEmission = Emission::where('is_live', true)
            ->orderBy('views', 'desc')
            ->first();

        return response()->json([
            'success' => true,
            'data' => $liveEmission,
        ]);
    }
}