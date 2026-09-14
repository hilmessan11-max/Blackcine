<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RankingEntry extends Model
{
    protected $fillable = [
        'ranking_id',
        'title_id',
        'position',
        'previous_position',
        'score',
        'votes_count',
        'average_rating',
    ];

    protected $casts = [
        'position' => 'integer',
        'previous_position' => 'integer',
        'score' => 'decimal:2',
        'votes_count' => 'integer',
        'average_rating' => 'decimal:2',
    ];

    public function ranking(): BelongsTo
    {
        return $this->belongsTo(Ranking::class);
    }

    public function title(): BelongsTo
    {
        return $this->belongsTo(Title::class);
    }

    // Helpers
    public function getPositionChangeAttribute()
    {
        if ($this->previous_position === null) {
            return 'new';
        }
        
        $change = $this->previous_position - $this->position;
        
        if ($change > 0) {
            return "+{$change}";
        } elseif ($change < 0) {
            return "{$change}";
        }
        
        return '=';
    }

    public function updatePosition($newPosition)
    {
        $this->update([
            'previous_position' => $this->position,
            'position' => $newPosition,
        ]);
    }
}
