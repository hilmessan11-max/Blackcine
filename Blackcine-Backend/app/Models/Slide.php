<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Slide extends Model
{
    protected $fillable = [
        'title', 'category', 'title_id', 'cta_label', 'cta_url', 'asset_id', 'display_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    /**
     * Relation vers le Title mis en avant.
     * Renommé depuis title() pour éviter collision avec colonne `title` (string).
     * Utilisez $slide->featuredTitle pour la relation, $slide->title pour le texte.
     */
    public function featuredTitle(): BelongsTo
    {
        return $this->belongsTo(Title::class, 'title_id');
    }

    public function scopeFilms($query)
    {
        return $query->where('category', 'film');
    }

    public function scopeSeries($query)
    {
        return $query->where('category', 'series');
    }

    public function scopeGeneral($query)
    {
        return $query->where('category', 'general');
    }
}
