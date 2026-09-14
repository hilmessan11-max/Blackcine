<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AffiliateProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'affiliate_id',
        'base_url',
        'commission_rate',
        'description',
        'is_active',
        'clicks_count',
        'total_earnings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'commission_rate' => 'decimal:2',
        'total_earnings' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($program) {
            if (empty($program->slug)) {
                $program->slug = Str::slug($program->name);
            }
        });
    }
}
