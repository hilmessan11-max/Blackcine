<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetVariant extends Model
{
    protected $fillable = [
        'asset_id', 'variant_type', 'path', 'mime_type', 'size_bytes', 'width', 'height',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
