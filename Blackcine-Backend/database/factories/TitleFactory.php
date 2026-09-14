<?php

namespace Database\Factories;

use App\Models\Title;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TitleFactory extends Factory
{
    protected $model = Title::class;

    public function definition(): array
    {
        $name = fake()->sentence(3);
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'type' => fake()->randomElement(['movie', 'series', 'classic']),
            'synopsis' => fake()->paragraph(),
            'release_date' => fake()->date('Y-m-d', '2030-01-01'),
            'origin_country' => fake()->countryCode(),
            'original_language' => fake()->randomElement(['fr', 'en', 'wo', 'bm']),
            'runtime_minutes' => fake()->numberBetween(60, 180),
            'is_paid' => false,
            'price_cents' => 0,
            'status' => 'published',
            'published_at' => now()->subDay(),
            'views_count' => fake()->numberBetween(0, 1000),
        ];
    }
}
