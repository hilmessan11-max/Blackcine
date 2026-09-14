<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'pitch',
        'description',
        'project_type',
        'category',
        'director',
        'location',
        'country',
        'city',
        'status',
        'needs',
        'is_active',
        'end_date',
        'thumbnail',
        'views_count'
    ];

    protected $casts = [
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];
}