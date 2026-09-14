<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject',
        'content',
        'from_name',
        'from_email',
        'status',
        'scheduled_at',
        'sent_at',
        'total_recipients',
        'sent_count',
        'opened_count',
        'clicked_count',
        'bounced_count',
        'unsubscribed_count',
        'created_by',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'total_recipients' => 'integer',
        'sent_count' => 'integer',
        'opened_count' => 'integer',
        'clicked_count' => 'integer',
        'bounced_count' => 'integer',
        'unsubscribed_count' => 'integer',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getOpenRateAttribute()
    {
        return $this->sent_count > 0 
            ? round(($this->opened_count / $this->sent_count) * 100, 2) 
            : 0;
    }

    public function getClickRateAttribute()
    {
        return $this->opened_count > 0 
            ? round(($this->clicked_count / $this->opened_count) * 100, 2) 
            : 0;
    }
}
