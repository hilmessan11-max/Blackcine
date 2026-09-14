<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\SanitizeHelper;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        try {
            $request->validate([
                'limit' => 'nullable|integer|min:1|max:50',
                'category' => 'nullable|string|max:100',
                'status' => 'nullable|string|max:100',
                'location' => 'nullable|string|max:100',
            ]);
            $query = Project::query();
            
            // Filtres
            if ($request->has('category')) {
                $query->where('category', $request->category);
            }
            
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->has('location')) {
                SanitizeHelper::whereLike($query, 'location', $request->location);
            }

            $limit = min((int) $request->input('limit', 10), 50);
            $projects = $query->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $projects,
                'total' => $projects->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function show($id)
    {
        try {
            $project = Project::findOrFail($id);
            
            // Incrémenter les vues
            $project->increment('views_count');
            
            return response()->json([
                'success' => true,
                'data' => $project
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Projet non trouvé'
            ], 404);
        }
    }
    
    public function apply(Request $request, $id)
    {
        try {
            $project = Project::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'message' => 'Demande de collaboration envoyée avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la demande'
            ], 500);
        }
    }
}
