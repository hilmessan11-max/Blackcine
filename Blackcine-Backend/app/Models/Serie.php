<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @deprecated Legacy model — utiliser Title avec type='series'.
 * Gardé pour compatibilité read-only.
 */
class Serie extends Model
{
    protected $table = 'series';
    protected $fillable = ['title','slug','overview','country','language','genres','category','seasons','creator','status','first_air_date','rating','is_featured','poster_asset_id','poster'];
    protected $casts = [
        'genres' => 'array',
        'seasons' => 'integer',
        'rating' => 'float',
        'is_featured' => 'boolean',
    ];

    public function trailers(): MorphMany
    {
        return $this->morphMany(Trailer::class, 'trailerable');
    }

    public function trailersLegacy(): MorphMany
    {
        return $this->morphMany(Trailer::class, 'trailarable');
    }

    public function toTitleAttributes(): array
    {
        return [
            'name' => $this->title,
            'slug' => $this->slug,
            'type' => 'series',
            'synopsis' => $this->overview,
            'origin_country' => $this->country,
            'original_language' => $this->language,
            'release_date' => $this->year ?? $this->first_air_date ? (is_numeric($this->year ?? '') ? sprintf('%04d-01-01', $this->year) : $this->first_air_date) : null,
            'status' => $this->status ?? 'published',
        ];
    }
}