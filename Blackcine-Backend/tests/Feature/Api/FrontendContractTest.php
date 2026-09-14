<?php

namespace Tests\Feature\Api;

use App\Models\Article;
use App\Models\Casting;
use App\Models\Cinema;
use App\Models\Contest;
use App\Models\Festival;
use App\Models\Genre;
use App\Models\Partner;
use App\Models\Project;
use App\Models\Room;
use App\Models\Selection;
use App\Models\Showtime;
use App\Models\Title;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class FrontendContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_api_exposes_the_expected_sections(): void
    {
        $this->seedContractData();

        $response = $this->getJson('/api/v1/home');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'featured_articles',
                    'featured_videos',
                    'top_films',
                    'top_series',
                    'now_showing',
                    'editorial_selection',
                    'active_festival',
                    'active_castings',
                    'active_projects',
                    'active_contests',
                    'classics',
                    'partners',
                ],
            ])
            ->assertJsonPath('data.top_films.0.name', 'The Great Journey')
            ->assertJsonPath('data.top_series.0.title', 'River Queens')
            ->assertJsonPath('data.now_showing.0.show_date', now()->addDay()->format('Y-m-d'))
            ->assertJsonPath('data.active_castings.0.title', 'Casting Principal')
            ->assertJsonPath('data.active_projects.0.title', 'BlackCine Rising')
            ->assertJsonPath('data.active_contests.0.title', 'Prix BlackCine')
            ->assertJsonPath('data.partners.0.name', 'Africa Cinema Hub');
    }

    public function test_title_api_keeps_frontend_compatibility_fields(): void
    {
        $title = $this->createPublishedTitle();

        $response = $this->getJson("/api/v1/titles/{$title->id}");

        $response->assertOk()
            ->assertJsonPath('id', $title->id)
            ->assertJsonPath('name', 'The Great Journey')
            ->assertJsonPath('title', 'The Great Journey')
            ->assertJsonPath('year', 2025)
            ->assertJsonPath('country', 'BJ')
            ->assertJsonPath('overview', 'A road movie across West Africa.')
            ->assertJsonPath('genres.0', 'Drame');
    }

    public function test_title_list_endpoints_expose_compatibility_fields(): void
    {
        $movie = $this->createPublishedTitle('The Great Journey', 'movie');
        $series = $this->createPublishedTitle('River Queens', 'series');

        $films = $this->getJson('/api/v1/titles/films');
        $films->assertOk()
            ->assertJsonPath('data.0.id', $movie->id)
            ->assertJsonPath('data.0.title', 'The Great Journey')
            ->assertJsonPath('data.0.year', 2025)
            ->assertJsonPath('data.0.country', 'BJ')
            ->assertJsonPath('data.0.overview', 'A road movie across West Africa.');

        $seriesResponse = $this->getJson('/api/v1/titles/series');
        $seriesResponse->assertOk()
            ->assertJsonPath('data.0.id', $series->id)
            ->assertJsonPath('data.0.title', 'River Queens')
            ->assertJsonPath('data.0.year', 2025)
            ->assertJsonPath('data.0.country', 'BJ')
            ->assertJsonPath('data.0.overview', 'A road movie across West Africa.');
    }

    public function test_showtime_api_normalizes_frontend_fields(): void
    {
        $showtime = $this->createShowtime();

        $response = $this->getJson("/api/v1/showtimes/{$showtime->id}");

        $response->assertOk()
            ->assertJsonPath('id', $showtime->id)
            ->assertJsonPath('title', 'The Great Journey')
            ->assertJsonPath('cinema', 'Majestic Cotonou')
            ->assertJsonPath('city', 'Cotonou')
            ->assertJsonPath('show_date', now()->addDay()->format('Y-m-d'))
            ->assertJsonPath('show_time', '19:30')
            ->assertJsonPath('available_seats', 100)
            ->assertJsonPath('total_seats', 100);
    }

    private function seedContractData(): void
    {
        $user = User::factory()->create();

        DB::table('articles')->insert([
            'title' => 'BlackCine news',
            'slug' => 'blackcine-news',
            'excerpt' => 'Latest news from BlackCine.',
            'body' => 'Latest news from BlackCine.',
            'author_id' => $user->id,
            'status' => 'published',
            'published_at' => now(),
            'views_count' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Video::create([
            'title' => 'Trailer BlackCine',
            'slug' => 'trailer-blackcine',
            'source_type' => 'youtube',
            'source_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'duration_seconds' => 120,
            'views_count' => 42,
            'status' => 'published',
            'published_at' => now(),
        ]);

        Selection::create([
            'title' => 'Selection BlackCine',
            'slug' => 'selection-blackcine',
            'type' => 'editorial',
            'description' => 'Editorial picks.',
            'is_active' => true,
            'display_order' => 1,
        ]);

        Festival::create([
            'name' => 'Festival BlackCine',
            'slug' => 'festival-blackcine',
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDays(5),
            'city' => 'Cotonou',
            'country' => 'BJ',
        ]);

        Partner::create([
            'name' => 'Africa Cinema Hub',
            'slug' => 'africa-cinema-hub',
            'description' => 'Partner for the platform.',
            'website_url' => 'https://example.com',
            'contact_email' => 'hello@example.com',
            'contact_phone' => '+22900000000',
            'is_active' => true,
        ]);

        $this->createPublishedTitle('The Great Journey', 'movie');
        $this->createPublishedTitle('River Queens', 'series');
        $this->createPublishedTitle('Old Stories', 'classic');

        Casting::create([
            'title' => 'Casting Principal',
            'description' => 'Principal role casting.',
            'type' => 'film',
            'location' => 'Bamako, Mali',
            'shooting_start' => now()->addWeeks(2),
            'shooting_end' => now()->addWeeks(4),
            'compensation' => 'Negotiable',
            'roles_count' => 3,
            'requirements' => 'Experience required.',
            'deadline' => now()->addDays(15),
            'is_urgent' => true,
            'is_active' => true,
            'status' => 'open',
            'applications_count' => 2,
        ]);

        Project::create([
            'title' => 'BlackCine Rising',
            'pitch' => 'A feature film in development.',
            'category' => 'film',
            'director' => 'Awa Diop',
            'location' => 'Dakar, Senegal',
            'status' => 'development',
            'needs' => 'Co-production partners',
            'is_active' => true,
            'views_count' => 4,
        ]);

        Contest::create([
            'title' => 'Prix BlackCine',
            'description' => 'Competition for emerging filmmakers.',
            'type' => 'prix',
            'prize' => '5000 EUR',
            'deadline' => now()->addDays(30),
            'requirements' => 'Short film under 20 minutes.',
            'location' => 'Online',
            'status' => 'open',
            'is_active' => true,
            'participants_count' => 7,
            'organizer' => 'BlackCine',
        ]);

        $cinema = Cinema::create([
            'name' => 'Majestic Cotonou',
            'slug' => 'majestic-cotonou',
            'city' => 'Cotonou',
            'country' => 'BJ',
            'is_active' => true,
        ]);

        $room = Room::create([
            'cinema_id' => $cinema->id,
            'name' => 'Salle 1',
            'capacity' => 100,
            'is_active' => true,
        ]);

        Showtime::create([
            'room_id' => $room->id,
            'title_id' => Title::where('slug', 'the-great-journey')->firstOrFail()->id,
            'starts_at' => now()->addDay()->setTime(19, 30),
            'runtime_minutes' => 120,
            'base_price_cents' => 1000,
            'status' => 'scheduled',
        ]);
    }

    private function createShowtime(): Showtime
    {
        $title = $this->createPublishedTitle('The Great Journey', 'movie');

        $cinema = Cinema::create([
            'name' => 'Majestic Cotonou',
            'slug' => 'majestic-cotonou',
            'city' => 'Cotonou',
            'country' => 'BJ',
            'is_active' => true,
        ]);

        $room = Room::create([
            'cinema_id' => $cinema->id,
            'name' => 'Salle 1',
            'capacity' => 100,
            'is_active' => true,
        ]);

        return Showtime::create([
            'room_id' => $room->id,
            'title_id' => $title->id,
            'starts_at' => now()->addDay()->setTime(19, 30),
            'runtime_minutes' => 120,
            'base_price_cents' => 1000,
            'status' => 'scheduled',
        ]);
    }

    private function createPublishedTitle(string $name = 'The Great Journey', string $type = 'movie'): Title
    {
        $title = Title::create([
            'name' => $name,
            'slug' => Str::slug($name),
            'type' => $type,
            'synopsis' => 'A road movie across West Africa.',
            'release_date' => '2025-01-15',
            'origin_country' => 'BJ',
            'original_language' => 'fr',
            'runtime_minutes' => 120,
            'is_paid' => false,
            'price_cents' => 0,
            'status' => 'published',
            'published_at' => now()->subDay(),
            'views_count' => 120,
        ]);

        $genre = Genre::firstOrCreate(['slug' => 'drame'], ['name' => 'Drame']);
        $title->genres()->sync([$genre->id]);

        return $title;
    }
}
