<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Person extends Model
{
    protected $fillable = [
        'name', 'slug', 'birth_date', 'birth_place', 'imdb_id', 'photo_asset_id',
    ];

    public function credits(): HasMany
    {
        return $this->hasMany(Credit::class);
    }
}
