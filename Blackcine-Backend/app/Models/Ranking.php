<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ranking extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'genre',
        'country',
        'year',
        'period',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'year' => 'integer',
        'display_order' => 'integer',
    ];

    public function entries(): HasMany
    {
        return $this->hasMany(RankingEntry::class)->orderBy('position');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Helpers
    public function addTitle($titleId, $position, $score = null)
    {
        return $this->entries()->create([
            'title_id' => $titleId,
            'position' => $position,
            'score' => $score,
        ]);
    }

    public function updatePositions(array $titlePositions)
    {
        foreach ($titlePositions as $titleId => $position) {
            $this->entries()
                ->where('title_id', $titleId)
                ->update(['position' => $position]);
        }
    }

    public function recalculateScores()
    {
        // Logique de calcul des scores basée sur les avis, vues, etc.
        foreach ($this->entries as $entry) {
            $title = $entry->title;
            $score = $this->calculateScore($title);
            $entry->update(['score' => $score]);
        }
    }

    private function calculateScore($title)
    {
        // Exemple de calcul : moyenne pondérée
        $reviewScore = $title->reviews()->approved()->avg('rating') ?? 0;
        $viewsScore = min($title->views_count / 1000, 100); // Max 100 points
        
        return ($reviewScore * 0.7) + ($viewsScore * 0.3);
    }
}
