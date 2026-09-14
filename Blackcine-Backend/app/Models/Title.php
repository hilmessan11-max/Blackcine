<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Builder;

class Title extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'slug', 'type', 'synopsis', 'release_date', 'origin_country',
        'original_language', 'certification', 'runtime_minutes', 'is_paid',
        'price_cents', 'currency_id', 'poster_asset_id', 'backdrop_asset_id',
        'status', 'published_at', 'views_count',
    ];

    protected $casts = [
        'release_date' => 'date',
        'runtime_minutes' => 'integer',
        'is_paid' => 'boolean',
        'price_cents' => 'integer',
        'views_count' => 'integer',
        'published_at' => 'datetime',
    ];

    protected $hidden = [
        'cached_avg_rating',
        'cached_total_reviews',
        'cached_dist_1',
        'cached_dist_2',
        'cached_dist_3',
        'cached_dist_4',
        'cached_dist_5',
    ];

    public function seasons(): HasMany
    {
        return $this->hasMany(Season::class);
    }

    public function episodes(): HasMany
    {
        return $this->hasMany(Episode::class);
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'title_genre');
    }

    public function credits(): HasMany
    {
        return $this->hasMany(Credit::class);
    }

    public function trailers(): MorphMany
    {
        return $this->morphMany(Trailer::class, 'trailerable');
    }

    /** @deprecated alias typo — utiliser trailers() */
    public function trailersLegacy(): MorphMany
    {
        return $this->morphMany(Trailer::class, 'trailarable');
    }

    public function subtitles(): MorphMany
    {
        return $this->morphMany(Subtitle::class, 'subtitlable');
    }

    public function showtimes(): HasMany
    {
        return $this->hasMany(Showtime::class);
    }

    public function tickets(): HasManyThrough
    {
        return $this->hasManyThrough(Ticket::class, Showtime::class);
    }

    public function festivals(): BelongsToMany
    {
        return $this->belongsToMany(Festival::class, 'festival_title')
            ->withPivot(['section', 'year'])
            ->withTimestamps();
    }

    public function seoMeta(): MorphMany
    {
        return $this->morphMany(SeoMeta::class, 'seoable');
    }

    public function posterAsset()
    {
        return $this->belongsTo(Asset::class, 'poster_asset_id');
    }

    public function backdropAsset()
    {
        return $this->belongsTo(Asset::class, 'backdrop_asset_id');
    }

    public function images()
    {
        return $this->morphToMany(Asset::class, 'usable', 'asset_usages')
            ->withPivot('context', 'metadata')
            ->withTimestamps();
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function editorialReviews(): HasMany
    {
        return $this->hasMany(EditorialReview::class);
    }

    public function rankingEntries(): HasMany
    {
        return $this->hasMany(RankingEntry::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')->whereNotNull('published_at');
    }

    /**
     * Eager-load rating aggregates in a single query via correlated subselects.
     * Call this on any Title query to avoid N+1 on average_rating / total_reviews / rating_distribution.
     */
    public function scopeWithRatingAggregates(Builder $query): Builder
    {
        $reviewType = static::class;

        $approved = fn () => Review::query()
            ->whereColumn('reviewable_id', 'titles.id')
            ->where('reviewable_type', $reviewType)
            ->where('status', 'approved');

        return $query->addSelect([
            'cached_avg_rating'    => $approved()->selectRaw('COALESCE(AVG(rating), 0)'),
            'cached_total_reviews' => $approved()->selectRaw('COUNT(*)'),
            'cached_dist_1'        => $approved()->where('rating', 1)->selectRaw('COUNT(*)'),
            'cached_dist_2'        => $approved()->where('rating', 2)->selectRaw('COUNT(*)'),
            'cached_dist_3'        => $approved()->where('rating', 3)->selectRaw('COUNT(*)'),
            'cached_dist_4'        => $approved()->where('rating', 4)->selectRaw('COUNT(*)'),
            'cached_dist_5'        => $approved()->where('rating', 5)->selectRaw('COUNT(*)'),
        ]);
    }

    // Helper methods
    public function getAverageRatingAttribute()
    {
        return $this->getAttribute('cached_avg_rating')
            ?? $this->reviews()->where('status', 'approved')->avg('rating')
            ?? 0;
    }

    public function getTotalReviewsAttribute()
    {
        return $this->getAttribute('cached_total_reviews')
            ?? $this->reviews()->where('status', 'approved')->count();
    }

    public function getRatingDistributionAttribute()
    {
        if ($this->getAttribute('cached_dist_1') !== null) {
            return [
                1 => (int) $this->getAttribute('cached_dist_1'),
                2 => (int) $this->getAttribute('cached_dist_2'),
                3 => (int) $this->getAttribute('cached_dist_3'),
                4 => (int) $this->getAttribute('cached_dist_4'),
                5 => (int) $this->getAttribute('cached_dist_5'),
            ];
        }

        $distribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $distribution[$i] = $this->reviews()->where('status', 'approved')->where('rating', $i)->count();
        }
        return $distribution;
    }
}