<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Selection;
use Illuminate\Http\Request;

class SelectionController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'limit' => 'nullable|integer|min:1|max:50',
        ]);
        $limit = min((int) $request->input('limit', 20), 50);
        $selections = Selection::where('is_active', true)
            ->orderBy('display_order')
            ->limit($limit)
            ->get()
            ->map(fn($s) => [
                'id' => $s->id,
                'title' => $s->title,
                'slug' => $s->slug,
                'description' => $s->description,
                'type' => $s->type,
                'display_order' => $s->display_order,
            ]);

        return response()->json([
            'success' => true,
            'data' => $selections,
        ]);
    }

    public function show($id)
    {
        $selection = Selection::find($id);

        if (!$selection) {
            return response()->json([
                'success' => false,
                'message' => 'Sélection non trouvée.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $selection->id,
                'title' => $selection->title,
                'slug' => $selection->slug,
                'description' => $selection->description,
                'type' => $selection->type,
                'display_order' => $selection->display_order,
            ],
        ]);
    }
}
