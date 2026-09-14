<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $favorites = Favorite::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $favorites
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:film,series,article,emission',
            'item_id' => 'required|integer',
            'name' => 'nullable|string|max:255',
            'poster' => 'nullable|string|max:500',
        ]);

        $favorite = Favorite::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'type' => $request->type,
                'item_id' => $request->item_id,
            ],
            [
                'name' => $request->name,
                'poster' => $request->poster,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Ajouté aux favoris',
            'data' => $favorite
        ], 201);
    }

    public function destroy(Request $request, $id)
    {
        $favorite = Favorite::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->first();

        if (!$favorite) {
            return response()->json([
                'success' => false,
                'message' => 'Favori non trouvé'
            ], 404);
        }

        $favorite->delete();

        return response()->json([
            'success' => true,
            'message' => 'Retiré des favoris'
        ]);
    }

    public function check(Request $request)
    {
        $request->validate([
            'type' => 'required|in:film,series,article,emission',
            'item_id' => 'required|integer',
        ]);

        $exists = Favorite::where('user_id', $request->user()->id)
            ->where('type', $request->type)
            ->where('item_id', $request->item_id)
            ->exists();

        return response()->json([
            'success' => true,
            'is_favorite' => $exists
        ]);
    }
}
