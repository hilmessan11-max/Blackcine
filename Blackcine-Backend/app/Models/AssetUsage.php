<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AssetUsage extends Model
{
    protected $fillable = [
        'asset_id', 'usable_id', 'usable_type', 'context', 'created_by',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function usable(): MorphTo
    {
        return $this->morphTo();
    }
}
