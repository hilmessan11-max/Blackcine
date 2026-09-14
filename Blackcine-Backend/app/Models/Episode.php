<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Episode extends Model
{
    protected $fillable = [
        'season_id', 'episode_number', 'name', 'synopsis', 'air_date', 'runtime_seconds', 'is_free', 'thumbnail_asset_id',
    ];

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function title(): BelongsTo
    {
        return $this->belongsTo(Title::class);
    }

    public function trailers(): MorphMany
    {
        return $this->morphMany(Trailer::class, 'trailerable');
    }

    /** @deprecated alias typo */
    public function trailersLegacy(): MorphMany
    {
        return $this->morphMany(Trailer::class, 'trailarable');
    }

    public function subtitles(): MorphMany
    {
        return $this->morphMany(Subtitle::class, 'subtitlable');
    }

    protected $casts = [
        'episode_number' => 'integer',
        'air_date' => 'date',
        'runtime_seconds' => 'integer',
        'is_free' => 'boolean',
    ];
}
