<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphedByMany;

class Tag extends Model
{
    protected $fillable = ['name', 'slug', 'type'];

    public function articles(): MorphedByMany
    {
        return $this->morphedByMany(Article::class, 'taggable');
    }

    public function videos(): MorphedByMany
    {
        return $this->morphedByMany(Video::class, 'taggable');
    }
}
