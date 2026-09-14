<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Showtime extends Model
{
    protected $fillable = [
        'room_id', 'title_id', 'starts_at', 'runtime_minutes', 'base_price_cents', 'currency_id', 'status',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function title(): BelongsTo
    {
        return $this->belongsTo(Title::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    protected $casts = [
        'starts_at' => 'datetime',
        'runtime_minutes' => 'integer',
        'base_price_cents' => 'integer',
    ];

    public function scopeUpcoming($query)
    {
        return $query->where('starts_at', '>', now());
    }
}
