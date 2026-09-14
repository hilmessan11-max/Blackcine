<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'image_url',
        'target_url',
        'target_audience',
        'target_criteria',
        'scheduled_at',
        'sent_at',
        'status',
        'success_count',
        'failure_count',
        'created_by',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'target_criteria' => 'array',
        'success_count' => 'integer',
        'failure_count' => 'integer',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
