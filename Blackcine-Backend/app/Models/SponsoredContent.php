<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SponsoredContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'sponsor_name',
        'campaign_name',
        'content_type',
        'content_id',
        'contract_details',
        'legal_mention',
        'tracking_pixel_url',
        'agreed_amount',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'agreed_amount' => 'decimal:2',
    ];

    public function content()
    {
        return $this->morphTo();
    }
}
