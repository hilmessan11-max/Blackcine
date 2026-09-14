<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'website_url', 'logo_asset_id',
        'contact_email', 'contact_phone', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
