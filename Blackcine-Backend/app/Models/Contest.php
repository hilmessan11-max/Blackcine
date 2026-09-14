<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'type',
        'contest_type',
        'prize',
        'deadline',
        'registration_deadline',
        'requirements',
        'location',
        'status',
        'is_active',
        'participants_count',
        'organizer'
    ];

    protected $casts = [
        'deadline' => 'date',
        'registration_deadline' => 'date',
        'is_active' => 'boolean',
    ];
}