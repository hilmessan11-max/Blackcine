<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SeoMeta extends Model
{
    protected $fillable = [
        'seoable_type', 'seoable_id', 'meta_title', 'meta_description', 'meta_keywords',
        'og_title', 'og_description', 'og_image_asset_id', 'canonical_url',
    ];

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }
}
