<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Festival;
use Illuminate\Http\Request;

class FestivalController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'limit' => 'nullable|integer|min:1|max:50',
        ]);
        $limit = min((int) $request->input('limit', 20), 50);
        $festivals = Festival::orderBy('starts_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(fn($f) => [
                'id' => $f->id,
                'name' => $f->name,
                'slug' => $f->slug,
                'description' => $f->description ?? '',
                'starts_at' => $f->starts_at?->format('Y-m-d'),
                'ends_at' => $f->ends_at?->format('Y-m-d'),
                'country' => $f->country ?? '',
                'city' => $f->city ?? '',
                'website_url' => $f->website_url ?? '',
                'status' => $f->status ?? 'active',
            ]);

        return response()->json([
            'success' => true,
            'data' => $festivals,
        ]);
    }

    public function active()
    {
        $festival = Festival::where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->first();

        if (!$festival) {
            return response()->json([
                'success' => true,
                'data' => null,
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $festival->id,
                'name' => $festival->name,
                'slug' => $festival->slug,
                'description' => $festival->description ?? '',
                'starts_at' => $festival->starts_at?->format('Y-m-d'),
                'ends_at' => $festival->ends_at?->format('Y-m-d'),
                'country' => $festival->country ?? '',
                'city' => $festival->city ?? '',
            ],
        ]);
    }

    public function show($id)
    {
        $festival = Festival::find($id);

        if (!$festival) {
            return response()->json([
                'success' => false,
                'message' => 'Festival non trouvé.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $festival->id,
                'name' => $festival->name,
                'slug' => $festival->slug,
                'description' => $festival->description ?? '',
                'starts_at' => $festival->starts_at?->format('Y-m-d'),
                'ends_at' => $festival->ends_at?->format('Y-m-d'),
                'country' => $festival->country ?? '',
                'city' => $festival->city ?? '',
                'website_url' => $festival->website_url ?? '',
            ],
        ]);
    }
}
