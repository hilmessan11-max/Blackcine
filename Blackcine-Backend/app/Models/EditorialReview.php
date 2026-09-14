<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EditorialReview extends Model
{
    protected $fillable = [
        'title_id',
        'author_id',
        'headline',
        'content',
        'rating',
        'critic_name',
        'publication',
        'publication_date',
        'external_url',
        'status',
        'is_featured',
        'display_order',
        'published_at',
    ];

    protected $casts = [
        'publication_date' => 'date',
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'rating' => 'integer',
        'display_order' => 'integer',
    ];

    public function title(): BelongsTo
    {
        return $this->belongsTo(Title::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('created_at', 'desc');
    }

    // Helpers
    public function publish()
    {
        $this->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function unpublish()
    {
        $this->update(['status' => 'draft']);
    }

    public function feature()
    {
        $this->update(['is_featured' => true]);
    }

    public function unfeature()
    {
        $this->update(['is_featured' => false]);
    }
}
