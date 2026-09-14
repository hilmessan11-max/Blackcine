<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    protected $fillable = [
        'disk', 'path', 'mime_type', 'size_bytes', 'width', 'height',
        'duration_seconds', 'checksum', 'user_id', 'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(AssetVariant::class);
    }

    public function usages(): HasMany
    {
        return $this->hasMany(AssetUsage::class);
    }
}
