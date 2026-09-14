<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Title;

class CatalogApiController extends Controller
{
    public function byGenre(string $slug)
    {
        $titles = Title::with('genres')
            ->whereHas('genres', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })
            ->published()
            ->latest('published_at')
            ->get()
            ->map(fn (Title $title) => $this->mapTitle($title));

        return response()->json([
            'genre' => $slug,
            'data' => $titles,
        ]);
    }

    private function mapTitle(Title $title): array
    {
        return [
            'id' => $title->id,
            'name' => $title->name,
            'title' => $title->name,
            'slug' => $title->slug,
            'type' => $title->type,
            'release_date' => $title->release_date?->format('Y-m-d'),
            'runtime_minutes' => $title->runtime_minutes,
            'genres' => $title->genres->pluck('name')->values(),
        ];
    }
}
