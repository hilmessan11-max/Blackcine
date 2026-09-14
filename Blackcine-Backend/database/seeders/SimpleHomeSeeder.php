<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\Title;
use App\Models\Genre;

class SimpleHomeSeeder extends Seeder
{
    public function run()
    {
        // Créer des genres
        $genres = [
            'Action', 'Drame', 'Comédie', 'Thriller', 'Romance', 
            'Documentaire', 'Historique', 'Fantaisie', 'Aventure', 'Crime'
        ];

        foreach ($genres as $genreName) {
            Genre::firstOrCreate(['name' => $genreName, 'slug' => \Str::slug($genreName)]);
        }

        // Créer des articles
        $articles = [
            [
                'title' => 'Le cinéma africain à l\'honneur au Festival de Cannes',
                'slug' => 'cinema-africain-cannes-2025',
                'excerpt' => 'Découvrez les films africains sélectionnés pour cette édition exceptionnelle.',
                'body' => 'Le Festival de Cannes 2025 met à l\'honneur le cinéma africain avec une sélection remarquable de films venus de tout le continent.',
                'author_id' => 1,
                'status' => 'published',
                'published_at' => now(),
                'views_count' => rand(100, 1000)
            ],
            [
                'title' => 'Nouvelle vague du cinéma nigérian : Nollywood 3.0',
                'slug' => 'nollywood-3-nouvelle-vague',
                'excerpt' => 'L\'industrie cinématographique nigériane connaît une révolution technologique.',
                'body' => 'Nollywood entre dans une nouvelle ère avec des productions de haute qualité et des technologies de pointe.',
                'author_id' => 1,
                'status' => 'published',
                'published_at' => now()->subDays(1),
                'views_count' => rand(100, 1000)
            ],
            [
                'title' => 'Les femmes réalisatrices africaines à l\'avant-garde',
                'slug' => 'femmes-realisatrices-africaines',
                'excerpt' => 'Portrait de ces femmes qui révolutionnent le 7ème art africain.',
                'body' => 'De plus en plus de femmes prennent la caméra en Afrique et apportent un regard nouveau sur le continent.',
                'author_id' => 1,
                'status' => 'published',
                'published_at' => now()->subDays(2),
                'views_count' => rand(100, 1000)
            ]
        ];

        foreach ($articles as $articleData) {
            Article::firstOrCreate(['slug' => $articleData['slug']], $articleData);
        }

        // Créer des films
        $films = [
            [
                'name' => 'King of Lagos',
                'slug' => 'king-of-lagos',
                'type' => 'movie',
                'synopsis' => 'L\'ascension d\'un jeune homme déterminé à conquérir le monde des affaires à Lagos.',
                'release_date' => '2025-01-15',
                'runtime_minutes' => 120,
                'status' => 'published',
                'views_count' => rand(1000, 5000),
                'is_paid' => true,
                'price_cents' => 500
            ],
            [
                'name' => 'The Lost Queen',
                'slug' => 'the-lost-queen',
                'type' => 'movie',
                'synopsis' => 'L\'histoire épique d\'une reine africaine oubliée par l\'histoire.',
                'release_date' => '2024-12-20',
                'runtime_minutes' => 135,
                'status' => 'published',
                'views_count' => rand(1000, 5000),
                'is_paid' => true,
                'price_cents' => 500
            ],
            [
                'name' => 'Red Moon',
                'slug' => 'red-moon',
                'type' => 'movie',
                'synopsis' => 'Un thriller mystérieux qui se déroule dans les rues de Dakar.',
                'release_date' => '2025-02-01',
                'runtime_minutes' => 110,
                'status' => 'published',
                'views_count' => rand(1000, 5000),
                'is_paid' => true,
                'price_cents' => 500
            ],
            [
                'name' => 'Desert Blood',
                'slug' => 'desert-blood',
                'type' => 'movie',
                'synopsis' => 'Une aventure épique dans le désert du Sahara.',
                'release_date' => '2025-03-15',
                'runtime_minutes' => 140,
                'status' => 'published',
                'views_count' => rand(1000, 5000),
                'is_paid' => true,
                'price_cents' => 500
            ]
        ];

        foreach ($films as $filmData) {
            $film = Title::firstOrCreate(['slug' => $filmData['slug']], $filmData);
            
            // Associer des genres aléatoires
            $randomGenres = Genre::inRandomOrder()->limit(rand(2, 3))->get();
            if ($randomGenres->count() > 0) {
                $film->genres()->sync($randomGenres->pluck('id'));
            }
        }

        // Créer des séries
        $series = [
            [
                'name' => 'Shadows of Dakar',
                'slug' => 'shadows-of-dakar',
                'type' => 'series',
                'synopsis' => 'Une série policière qui explore les mystères de Dakar.',
                'release_date' => '2025-01-01',
                'runtime_minutes' => 45,
                'status' => 'published',
                'views_count' => rand(1000, 5000),
                'is_paid' => true,
                'price_cents' => 300
            ],
            [
                'name' => 'Mothers of the Nile',
                'slug' => 'mothers-of-the-nile',
                'type' => 'series',
                'synopsis' => 'L\'histoire de femmes courageuses le long du Nil.',
                'release_date' => '2024-11-15',
                'runtime_minutes' => 50,
                'status' => 'published',
                'views_count' => rand(1000, 5000),
                'is_paid' => true,
                'price_cents' => 300
            ],
            [
                'name' => 'The Kingdom Rise',
                'slug' => 'the-kingdom-rise',
                'type' => 'series',
                'synopsis' => 'Une saga fantastique dans un royaume africain imaginaire.',
                'release_date' => '2025-02-15',
                'runtime_minutes' => 55,
                'status' => 'published',
                'views_count' => rand(1000, 5000),
                'is_paid' => true,
                'price_cents' => 300
            ],
            [
                'name' => 'African Royalty',
                'slug' => 'african-royalty',
                'type' => 'series',
                'synopsis' => 'Documentaire sur les familles royales africaines.',
                'release_date' => '2025-03-01',
                'runtime_minutes' => 40,
                'status' => 'published',
                'views_count' => rand(1000, 5000),
                'is_paid' => false,
                'price_cents' => 0
            ]
        ];

        foreach ($series as $serieData) {
            $serie = Title::firstOrCreate(['slug' => $serieData['slug']], $serieData);
            
            // Associer des genres aléatoires
            $randomGenres = Genre::inRandomOrder()->limit(rand(2, 3))->get();
            if ($randomGenres->count() > 0) {
                $serie->genres()->sync($randomGenres->pluck('id'));
            }
        }
    }
}