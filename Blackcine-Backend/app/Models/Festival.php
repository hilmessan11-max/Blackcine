<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Festival extends Model
{
	protected $fillable = [
		'name', 'slug', 'starts_at', 'ends_at', 'city', 'country', 'description',
	];

	protected $casts = [
		'starts_at' => 'datetime',
		'ends_at' => 'datetime',
	];

	public function titles(): BelongsToMany
	{
		return $this->belongsToMany(Title::class, 'festival_title')
			->withPivot(['section', 'year'])
			->withTimestamps();
	}
}
