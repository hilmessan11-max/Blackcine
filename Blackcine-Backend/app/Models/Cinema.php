<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cinema extends Model
{
    protected $fillable = [
        'name', 'slug', 'company_name', 'email', 'phone', 'address_line1', 'address_line2', 'city', 'postal_code', 'country', 'commission_rate', 'is_active',
    ];

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }
}
