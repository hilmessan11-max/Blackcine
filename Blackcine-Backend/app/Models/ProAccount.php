<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProAccount extends Model
{
    protected $fillable = [
        'user_id', 'company_name', 'company_type', 'tax_id', 'registration_number',
        'address_line1', 'address_line2', 'city', 'postal_code', 'country',
        'phone', 'email', 'website', 'verification_status', 'verified_at',
        'id_document_asset_id', 'business_document_asset_id', 'kyc_status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function idDocument(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'id_document_asset_id');
    }

    public function businessDocument(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'business_document_asset_id');
    }

    protected $casts = [
        'verified_at' => 'datetime',
    ];
}
