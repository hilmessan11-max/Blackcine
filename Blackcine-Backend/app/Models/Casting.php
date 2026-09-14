<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Casting extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'type',
        'role',
        'location',
        'country',
        'city',
        'shooting_start',
        'shooting_end',
        'compensation',
        'roles_count',
        'requirements',
        'deadline',
        'is_active',
        'is_urgent',
        'status',
        'applications_count'
    ];

    protected $casts = [
        'shooting_start' => 'date',
        'shooting_end' => 'date',
        'deadline' => 'date',
        'is_active' => 'boolean',
        'is_urgent' => 'boolean',
    ];
}