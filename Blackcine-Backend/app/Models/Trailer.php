<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Trailer extends Model
{
    protected $fillable = [
        'trailerable_id', 'trailerable_type', 'trailarable_id', 'trailarable_type', 'title', 'source_type', 'source_url', 'asset_id', 'duration_seconds', 'is_official', 'display_order',
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
        'is_official' => 'boolean',
        'display_order' => 'integer',
    ];

    public function trailerable(): MorphTo
    {
        return $this->morphTo();
    }

    /** @deprecated alias typo — garde compatibilité avec anciennes données */
    public function trailarable(): MorphTo
    {
        return $this->morphTo('trailarable');
    }
}
