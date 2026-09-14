<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\Title;
use App\Models\Cinema;
use App\Models\Room;
use App\Models\Showtime;
use App\Models\Casting;
use App\Models\Project;
use App\Models\Contest;
use App\Models\Genre;

class HomePageSeeder extends Seeder
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
                'body' => 'Le Festival de Cannes 2025 met à l\'honneur le cinéma africain...',
                'author_id' => 1,
                'status' => 'published',
                'published_at' => now(),
                'views_count' => rand(100, 1000)
            ],
            [
                'title' => 'Nouvelle vague du cinéma nigérian : Nollywood 3.0',
                'slug' => 'nollywood-3-nouvelle-vague',
                'excerpt' => 'L\'industrie cinématographique nigériane connaît une révolution technologique.',
                'body' => 'Nollywood entre dans une nouvelle ère avec des productions...',
                'author_id' => 1,
                'status' => 'published',
                'published_at' => now()->subDays(1),
                'views_count' => rand(100, 1000)
            ],
            [
                'title' => 'Les femmes réalisatrices africaines à l\'avant-garde',
                'slug' => 'femmes-realisatrices-africaines',
                'excerpt' => 'Portrait de ces femmes qui révolutionnent le 7ème art africain.',
                'body' => 'De plus en plus de femmes prennent la caméra en Afrique...',
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
            $film->genres()->sync($randomGenres->pluck('id'));
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
            ]
        ];

        foreach ($series as $serieData) {
            $serie = Title::firstOrCreate(['slug' => $serieData['slug']], $serieData);

            // Associer des genres aléatoires
            $randomGenres = Genre::inRandomOrder()->limit(rand(2, 3))->get();
            $serie->genres()->sync($randomGenres->pluck('id'));
        }

        // Créer des cinémas
        $cinemas = [
            [
                'name' => 'Majestic Cotonou',
                'slug' => 'majestic-cotonou',
                'address_line1' => 'Avenue Clozel',
                'city' => 'Cotonou',
                'country' => 'BJ',
                'phone' => '+229 21 30 45 67',
                'is_active' => true
            ],
            [
                'name' => 'Ciné Paris Calavi',
                'slug' => 'cine-paris-calavi',
                'address_line1' => 'Rue de Paris',
                'city' => 'Calavi',
                'country' => 'BJ',
                'phone' => '+229 21 35 78 90',
                'is_active' => true
            ]
        ];

        foreach ($cinemas as $cinemaData) {
            Cinema::firstOrCreate(['slug' => $cinemaData['slug']], $cinemaData);
        }

        // Créer des rooms pour chaque cinéma
        $cinemas = Cinema::all();
        foreach ($cinemas as $cinema) {
            for ($i = 1; $i <= 3; $i++) {
                Room::firstOrCreate([
                    'cinema_id' => $cinema->id,
                    'name' => "Salle $i",
                    'capacity' => 100,
                    'is_active' => true
                ]);
            }
        }

        // Créer des séances
        $titles = Title::where('type', 'movie')->get();
        $rooms = Room::all();

        foreach ($titles as $title) {
            foreach ($rooms as $room) {
                for ($i = 0; $i < 2; $i++) {
                    Showtime::firstOrCreate([
                        'room_id' => $room->id,
                        'title_id' => $title->id,
                        'starts_at' => now()->addDays(rand(0, 7))->setHour(intval(['14', '17', '19', '21'][rand(0, 3)]))->setMinute(30),
                    ], [
                        'runtime_minutes' => 120,
                        'base_price_cents' => 1000,
                        'status' => 'scheduled'
                    ]);
                }
            }
        }

        // Créer des castings (table vide - à compléter plus tard)
        /*
        $castings = [
            [
                'title' => 'Recherche acteur principal pour "Lagos Dreams"',
                'slug' => 'casting-lagos-dreams',
                'description' => 'Nous recherchons un acteur de 25-35 ans pour le rôle principal.',
                'role' => 'Acteur principal',
                'status' => 'open',
                'end_date' => now()->addDays(30),
                'country' => 'Nigeria',
                'city' => 'Lagos',
                'is_active' => true
            ],
            [
                'title' => 'Casting figurants pour série historique',
                'slug' => 'casting-figurants-serie',
                'description' => 'Recherche figurants pour une série se déroulant au 19ème siècle.',
                'role' => 'Figurants',
                'status' => 'open',
                'end_date' => now()->addDays(15),
                'country' => 'Sénégal',
                'city' => 'Dakar',
                'is_active' => true
            ]
        ];

        foreach ($castings as $castingData) {
            Casting::firstOrCreate(['slug' => $castingData['slug']], $castingData);
        }
        */

        // Créer des projets (table vide - à compléter plus tard)
        /*
        $projects = [
            [
                'title' => 'Documentaire sur l\'art contemporain africain',
                'slug' => 'doc-art-contemporain',
                'description' => 'Projet de documentaire explorant l\'art contemporain en Afrique.',
                'project_type' => 'documentary',
                'status' => 'recruiting',
                'end_date' => now()->addDays(45),
                'country' => 'Côte d\'Ivoire',
                'city' => 'Abidjan',
                'is_active' => true
            ]
        ];

        foreach ($projects as $projectData) {
            Project::firstOrCreate(['slug' => $projectData['slug']], $projectData);
        }
        */

        // Créer des concours (table vide - à compléter plus tard)
        /*
        $contests = [
            [
                'title' => 'Concours du meilleur court-métrage africain',
                'slug' => 'concours-court-metrage',
                'description' => 'Concours ouvert aux jeunes réalisateurs africains.',
                'contest_type' => 'short_film',
                'status' => 'open',
                'registration_deadline' => now()->addDays(60),
                'is_active' => true
            ]
        ];

        foreach ($contests as $contestData) {
            Contest::firstOrCreate(['slug' => $contestData['slug']], $contestData);
        }
        */
    }
}
