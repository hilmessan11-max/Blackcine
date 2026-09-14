<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'order_id', 'invoice_number', 'total_cents', 'tax_cents', 'subtotal_cents',
        'currency_id', 'status', 'issued_at', 'due_at',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    protected $casts = [
        'total_cents' => 'integer',
        'tax_cents' => 'integer',
        'subtotal_cents' => 'integer',
        'issued_at' => 'datetime',
        'due_at' => 'datetime',
    ];
}
