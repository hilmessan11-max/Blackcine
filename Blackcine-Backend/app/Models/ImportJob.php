<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportJob extends Model
{
    protected $fillable = [
        'type', 'status', 'config', 'metrics', 'error_message', 'created_by', 'started_at', 'finished_at',
    ];

    protected $casts = [
        'config' => 'array',
        'metrics' => 'array',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
