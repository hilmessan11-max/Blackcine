<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TalentProfile extends Model
{
    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'profession', 'specialties',
        'bio', 'experience_years', 'country', 'city', 'phone', 'email',
        'website', 'showreel_url', 'cv_asset_id', 'photo_asset_id',
        'is_verified', 'verification_status', 'verified_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cvAsset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'cv_asset_id');
    }

    public function photoAsset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'photo_asset_id');
    }

    protected $casts = [
        'experience_years' => 'integer',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];
}
