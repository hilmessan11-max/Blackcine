<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use Illuminate\Http\Request;

class ContestController extends Controller
{
    public function index(Request $request)
    {
        try {
            $request->validate([
                'limit' => 'nullable|integer|min:1|max:50',
                'type' => 'nullable|string|max:100',
                'status' => 'nullable|string|max:100',
            ]);
            $query = Contest::query();
            
            // Filtres
            if ($request->has('type')) {
                $query->where('type', $request->type);
            }
            
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }
            
            // Trier par date limite (plus proche en premier)
            $limit = min((int) $request->input('limit', 10), 50);
            $contests = $query->orderBy('deadline', 'asc')
                ->limit($limit)
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $contests,
                'total' => $contests->count()
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
            $contest = Contest::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $contest
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Concours non trouvé'
            ], 404);
        }
    }
    
    public function register(Request $request, $id)
    {
        try {
            $contest = Contest::findOrFail($id);
            
            // Incrémenter le compteur de participants
            $contest->increment('participants_count');
            
            return response()->json([
                'success' => true,
                'message' => 'Inscription au concours réussie'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de l\'inscription'
            ], 500);
        }
    }
}
