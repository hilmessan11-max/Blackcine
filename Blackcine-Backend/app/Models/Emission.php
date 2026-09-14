<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emission extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'duration',
        'presenter',
        'country',
        'language',
        'year',
        'status',
        'views',
        'thumbnail',
        'video_url',
        'is_live',
        'broadcast_date'
    ];

    protected $casts = [
        'is_live' => 'boolean',
        'broadcast_date' => 'datetime',
        'views' => 'integer'
    ];
}