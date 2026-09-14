<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'item_id' => $this->item_id,
            'name' => $this->name,
            'poster' => $this->poster,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
