<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'source_type' => $this->source_type ?? 'youtube',
            'source_url' => $this->source_url,
            'thumbnail' => $this->thumbnail?->path ? asset('storage/' . $this->thumbnail->path) : null,
            'views_count' => $this->views_count ?? 0,
            'duration' => $this->duration_seconds ?? 0,
            'status' => $this->status,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
