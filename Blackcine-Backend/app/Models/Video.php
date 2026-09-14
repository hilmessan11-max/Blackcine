<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Video extends Model
{
    protected $fillable = [
        'title', 'slug', 'source_type', 'source_url', 'asset_id', 'thumbnail_asset_id',
        'duration_seconds', 'views_count', 'status', 'published_at',
    ];

    public function file(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function thumbnail(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'thumbnail_asset_id');
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }

    public function assetUsages(): MorphMany
    {
        return $this->morphMany(AssetUsage::class, 'usable');
    }

    protected $casts = [
        'duration_seconds' => 'integer',
        'views_count' => 'integer',
        'published_at' => 'datetime',
    ];

    public function scopePublished($query)
    {
        return $query->where('status', 'published')->whereNotNull('published_at');
    }
}
