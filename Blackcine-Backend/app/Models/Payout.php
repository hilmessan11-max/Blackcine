<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    protected $fillable = [
        'payout_account_id', 'amount_cents', 'currency_id', 'status', 'processed_at',
    ];

    protected $casts = [
        'amount_cents' => 'integer',
        'processed_at' => 'datetime',
    ];
}
