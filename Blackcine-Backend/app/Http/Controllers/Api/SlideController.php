<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slide;
use Illuminate\Http\Request;

class SlideController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'limit' => 'nullable|integer|min:1|max:50',
        ]);
        $limit = min((int) $request->input('limit', 10), 50);
        $slides = Slide::where('is_active', true)
            ->orderBy('display_order')
            ->limit($limit)
            ->get()
            ->map(fn($slide) => [
                'id' => $slide->id,
                'title' => $slide->title,
                'subtitle' => $slide->subtitle ?? '',
                'link' => $slide->link ?? '#',
                'image' => $slide->image_path ? asset('storage/' . $slide->image_path) : null,
                'display_order' => $slide->display_order,
            ]);

        return response()->json([
            'success' => true,
            'data' => $slides,
        ]);
    }

    public function films()
    {
        $slides = Slide::where('is_active', true)
            ->where('type', 'film')
            ->orderBy('display_order')
            ->limit(5)
            ->get()
            ->map(fn($slide) => [
                'id' => $slide->id,
                'title' => $slide->title,
                'subtitle' => $slide->subtitle ?? '',
                'link' => $slide->link ?? '#',
                'image' => $slide->image_path ? asset('storage/' . $slide->image_path) : null,
            ]);

        return response()->json([
            'success' => true,
            'data' => $slides,
        ]);
    }

    public function series()
    {
        $slides = Slide::where('is_active', true)
            ->where('type', 'series')
            ->orderBy('display_order')
            ->limit(5)
            ->get()
            ->map(fn($slide) => [
                'id' => $slide->id,
                'title' => $slide->title,
                'subtitle' => $slide->subtitle ?? '',
                'link' => $slide->link ?? '#',
                'image' => $slide->image_path ? asset('storage/' . $slide->image_path) : null,
            ]);

        return response()->json([
            'success' => true,
            'data' => $slides,
        ]);
    }
}
