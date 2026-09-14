<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Credit extends Model
{
    protected $fillable = [
        'title_id', 'person_id', 'department', 'job', 'character_name', 'credit_order',
    ];

    public function title(): BelongsTo
    {
        return $this->belongsTo(Title::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
