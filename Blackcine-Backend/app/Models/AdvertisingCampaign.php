<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisingCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'client_name',
        'type',
        'placement',
        'image_url',
        'video_url',
        'target_url',
        'start_date',
        'end_date',
        'status',
        'impressions_count',
        'clicks_count',
        'price_cents',
        'currency',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price_cents' => 'integer',
        'impressions_count' => 'integer',
        'clicks_count' => 'integer',
    ];

    // Helper pour le statut
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'active' => 'green',
            'paused' => 'yellow',
            'ended' => 'gray',
            'draft' => 'blue',
            default => 'gray',
        };
    }
}
