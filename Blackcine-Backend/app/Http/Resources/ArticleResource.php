<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            // Compat frontend : la colonne s'appelle `body`, pas `content`
            'content' => $this->body,
            'body' => $this->body,
            'author' => $this->author ?? 'Rédaction BlackCiné',
            'published_at' => $this->published_at?->format('Y-m-d'),
            'views_count' => $this->views_count ?? $this->views ?? 0,
            'status' => $this->status,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
