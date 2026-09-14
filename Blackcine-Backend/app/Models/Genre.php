<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Genre extends Model
{
    protected $fillable = ['name', 'slug'];

    public function titles(): BelongsToMany
    {
        return $this->belongsToMany(Title::class, 'title_genre');
    }
}
