<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @deprecated Legacy model — utiliser Title avec type='movie' (voir TitleController).
 * Gardé pour compatibilité read-only avec données historiques.
 */
class Film extends Model
{
    protected $fillable = ['title','slug','overview','country','language','genres','category','year','duration_minutes','rating','is_featured','poster_asset_id','poster','creator','status','first_air_date'];
    protected $casts = [
        'genres' => 'array',
        'duration_minutes' => 'integer',
        'rating' => 'float',
        'is_featured' => 'boolean',
        'year' => 'integer',
    ];

    public function trailers(): MorphMany
    {
        return $this->morphMany(Trailer::class, 'trailerable');
    }

    /** Alias pour compatibilité avec ancienne typo */
    public function trailersLegacy(): MorphMany
    {
        return $this->morphMany(Trailer::class, 'trailarable');
    }

    /**
     * Convertit ce Film legacy en attributs Title.
     */
    public function toTitleAttributes(): array
    {
        return [
            'name' => $this->title,
            'slug' => $this->slug,
            'type' => 'movie',
            'synopsis' => $this->overview,
            'origin_country' => $this->country,
            'original_language' => $this->language,
            'release_date' => $this->year ? sprintf('%04d-01-01', $this->year) : null,
            'runtime_minutes' => $this->duration_minutes,
            'status' => $this->status ?? 'published',
        ];
    }
}