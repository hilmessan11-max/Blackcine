<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'reviewable_id',
        'reviewable_type',
        'rating',
        'title',
        'content',
        'is_verified_purchase',
        'contains_spoiler',
        'status',
        'helpful_count',
        'not_helpful_count',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'is_verified_purchase' => 'boolean',
        'contains_spoiler' => 'boolean',
        'approved_at' => 'datetime',
        'helpful_count' => 'integer',
        'not_helpful_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewable(): MorphTo
    {
        return $this->morphTo();
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeHighRated($query, $minRating = 4)
    {
        return $query->where('rating', '>=', $minRating);
    }

    // Helpers
    public function approve($adminId = null)
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $adminId,
        ]);
    }

    public function reject()
    {
        $this->update(['status' => 'rejected']);
    }

    public function getHelpfulPercentageAttribute()
    {
        $total = $this->helpful_count + $this->not_helpful_count;
        return $total > 0 ? round(($this->helpful_count / $total) * 100) : 0;
    }
}
