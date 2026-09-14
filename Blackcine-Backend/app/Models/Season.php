<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Season extends Model
{
    protected $fillable = [
        'title_id', 'season_number', 'name', 'synopsis', 'release_year', 'poster_asset_id',
    ];

    public function title(): BelongsTo
    {
        return $this->belongsTo(Title::class);
    }

    public function episodes(): HasMany
    {
        return $this->hasMany(Episode::class);
    }

    protected $casts = [
        'season_number' => 'integer',
        'release_year' => 'integer',
    ];
}
