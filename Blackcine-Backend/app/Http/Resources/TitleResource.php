<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TitleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $poster = $this->images->first();
        $posterPath = $poster?->path ? asset('storage/' . $poster->path) : null;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'title' => $this->name,
            'slug' => $this->slug,
            'type' => $this->type,
            'synopsis' => $this->synopsis,
            'overview' => $this->synopsis,
            'release_date' => $this->release_date?->format('Y-m-d'),
            'year' => $this->release_date?->year,
            'runtime_minutes' => $this->runtime_minutes,
            'origin_country' => $this->origin_country ?? '',
            'country' => $this->origin_country ?? '',
            'original_language' => $this->original_language ?? '',
            'poster' => $posterPath,
            'genres' => $this->genres->pluck('name')->toArray(),
            'views_count' => $this->views_count ?? 0,
            'is_paid' => $this->is_paid ?? false,
            'price_cents' => $this->price_cents ?? 0,
            'average_rating' => $this->average_rating,
            'total_reviews' => $this->total_reviews,
            'status' => $this->status,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
