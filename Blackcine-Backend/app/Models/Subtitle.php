<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Subtitle extends Model
{
    protected $fillable = [
        'subtitlable_id', 'subtitlable_type', 'language', 'format', 'asset_id', 'url', 'is_closed_caption',
    ];

    public function subtitlable(): MorphTo
    {
        return $this->morphTo();
    }
}
