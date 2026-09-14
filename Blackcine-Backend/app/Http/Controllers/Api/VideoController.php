<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\SanitizeHelper;
use App\Http\Resources\VideoResource;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:100',
            'type' => 'nullable|string|max:50',
            'page' => 'nullable|integer|min:1',
        ]);

        $query = Video::where('status', 'published')
            ->with(['tags', 'categories']);

        if ($request->filled('search')) {
            SanitizeHelper::whereLike($query, 'title', $request->search);
        }

        if ($request->filled('type')) {
            $query->where('source_type', $request->type);
        }

        $videos = $query->orderBy('published_at', 'desc')
            ->paginate(20);

        return VideoResource::collection($videos);
    }

    public function featured()
    {
        $videos = Video::where('status', 'published')
            ->with(['tags', 'categories'])
            ->orderByDesc('views_count')
            ->limit(6)
            ->get();

        return response()->json([
            'success' => true,
            'data' => VideoResource::collection($videos),
        ]);
    }

    public function show($id)
    {
        $video = Video::with(['tags', 'categories'])->find($id);

        if (!$video) {
            return response()->json([
                'success' => false,
                'message' => 'Vidéo non trouvée.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new VideoResource($video),
        ]);
    }
}
