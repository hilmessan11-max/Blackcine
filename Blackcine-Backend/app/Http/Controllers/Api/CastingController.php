<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Casting;
use Illuminate\Http\Request;

class CastingController extends Controller
{
    public function index(Request $request)
    {
        try {
            $request->validate([
                'limit' => 'nullable|integer|min:1|max:50',
                'type' => 'nullable|string|max:50',
                'urgent' => 'nullable|string|max:10',
                'status' => 'nullable|string|max:50',
            ]);
            $query = Casting::query();
            
            // Filtres
            if ($request->has('type') && $request->type !== 'all') {
                $query->where('type', $request->type);
            }
            
            if ($request->has('urgent') && $request->urgent === 'true') {
                $query->where('is_urgent', true);
            }
            
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }
            
            $limit = min((int) $request->input('limit', 10), 50);
            $castings = $query->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $castings,
                'total' => $castings->count()
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
            $casting = Casting::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $casting
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Casting non trouvé'
            ], 404);
        }
    }
    
    public function apply(Request $request, $id)
    {
        try {
            $casting = Casting::findOrFail($id);
            
            // Incrémenter le compteur de candidatures
            $casting->increment('applications_count');
            
            return response()->json([
                'success' => true,
                'message' => 'Candidature envoyée avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la candidature'
            ], 500);
        }
    }
}
