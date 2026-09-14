<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $fillable = [
        'order_id', 'amount_cents', 'currency_id', 'status', 'reason',
    ];

    protected $casts = [
        'amount_cents' => 'integer',
    ];
}
