<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphedByMany;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'type', 'parent_id', 'display_order', 'is_active'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function articles(): MorphedByMany
    {
        return $this->morphedByMany(Article::class, 'categorizable');
    }

    public function videos(): MorphedByMany
    {
        return $this->morphedByMany(Video::class, 'categorizable');
    }
}
