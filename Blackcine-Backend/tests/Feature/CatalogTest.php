<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Genre;
use App\Models\Review;
use App\Models\User;
use App\Models\Title;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_films_page_lists_only_published_movies_and_exposes_posters(): void
    {
        $drama = Genre::create(['name' => 'Drame', 'slug' => 'drame']);
        $comedy = Genre::create(['name' => 'Comedie', 'slug' => 'comedie']);

        $poster = Asset::create([
            'disk' => 'public',
            'path' => 'posters/the-great-journey.jpg',
            'mime_type' => 'image/jpeg',
            'status' => 'ready',
        ]);

        $movie = $this->createTitle('The Great Journey', 'movie', $drama, $poster);
        $this->createTitle('River Queens', 'series', $drama);
        $this->createTitle('Hidden Cut', 'movie', $comedy, null, 'draft');

        $response = $this->get('/films');

        $response->assertOk()
            ->assertSee(route('catalog.title.show', $movie->slug), false)
            ->assertSee('The Great Journey')
            ->assertSee('posters/the-great-journey.jpg', false)
            ->assertDontSee('River Queens')
            ->assertDontSee('Hidden Cut');
    }

    public function test_series_page_lists_only_published_series(): void
    {
        $drama = Genre::create(['name' => 'Drame', 'slug' => 'drame']);

        $this->createTitle('The Great Journey', 'movie', $drama);
        $series = $this->createTitle('River Queens', 'series', $drama);

        $response = $this->get('/series');

        $response->assertOk()
            ->assertSee('River Queens')
            ->assertDontSee('The Great Journey')
            ->assertSee('Series');
    }

    public function test_genre_page_filters_titles_across_types(): void
    {
        $drama = Genre::create(['name' => 'Drame', 'slug' => 'drame']);
        $comedy = Genre::create(['name' => 'Comedie', 'slug' => 'comedie']);

        $dramaMovie = $this->createTitle('The Great Journey', 'movie', $drama);
        $dramaSeries = $this->createTitle('River Queens', 'series', $drama);
        $this->createTitle('Funny Nights', 'movie', $comedy);

        $response = $this->get('/catalog/genre/drame');

        $response->assertOk()
            ->assertSee('The Great Journey')
            ->assertSee('River Queens')
            ->assertDontSee('Funny Nights')
            ->assertSee('Genre : Drame');
    }

    public function test_title_show_page_exposes_core_story_information(): void
    {
        $drama = Genre::create(['name' => 'Drame', 'slug' => 'drame']);
        $poster = Asset::create([
            'disk' => 'public',
            'path' => 'posters/the-great-journey.jpg',
            'mime_type' => 'image/jpeg',
            'status' => 'ready',
        ]);

        $title = $this->createTitle('The Great Journey', 'movie', $drama, $poster);

        $response = $this->get('/titles/the-great-journey');

        $response->assertOk()
            ->assertSee('The Great Journey')
            ->assertSee('Apercu')
            ->assertSee('Drame')
            ->assertSee('posters/the-great-journey.jpg', false)
            ->assertSee('2025');
    }
    public function test_title_show_page_exposes_approved_reviews(): void
    {
        $drama = Genre::create(['name' => 'Drame', 'slug' => 'drame']);
        $title = $this->createTitle('The Great Journey', 'movie', $drama);
        $user = User::factory()->create();

        Review::create([
            'user_id' => $user->id,
            'reviewable_id' => $title->id,
            'reviewable_type' => Title::class,
            'rating' => 4,
            'title' => 'Très fort',
            'content' => 'Une fiche claire et utile.',
            'contains_spoiler' => false,
            'is_verified_purchase' => false,
            'status' => 'approved',
            'helpful_count' => 0,
            'not_helpful_count' => 0,
        ]);

        $response = $this->get('/titles/the-great-journey');

        $response->assertOk()
            ->assertSee('Avis')
            ->assertSee('Très fort')
            ->assertSee('Une fiche claire et utile.')
            ->assertSee('4/5');
    }

    public function test_authenticated_user_can_submit_review_for_title(): void
    {
        $drama = Genre::create(['name' => 'Drame', 'slug' => 'drame']);
        $title = $this->createTitle('The Great Journey', 'movie', $drama);
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('catalog.title.reviews.store', $title->slug), [
            'rating' => 5,
            'title' => 'Coup de cœur',
            'content' => 'Très belle fiche de lecture.',
            'contains_spoiler' => 1,
        ]);

        $response->assertRedirect(route('catalog.title.show', $title->slug));

        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'reviewable_id' => $title->id,
            'reviewable_type' => Title::class,
            'rating' => 5,
            'title' => 'Coup de cœur',
            'status' => 'pending',
        ]);
    }

    private function createTitle(
        string $name,
        string $type,
        Genre $genre,
        ?Asset $poster = null,
        string $status = 'published'
    ): Title {
        $title = Title::create([
            'name' => $name,
            'slug' => Str::slug($name),
            'type' => $type,
            'synopsis' => "{$name} synopsis.",
            'release_date' => '2025-01-15',
            'origin_country' => 'BJ',
            'original_language' => 'fr',
            'runtime_minutes' => 120,
            'is_paid' => false,
            'price_cents' => 0,
            'poster_asset_id' => $poster?->id,
            'status' => $status,
            'published_at' => $status === 'published' ? now()->subDay() : null,
            'views_count' => 120,
        ]);

        $title->genres()->sync([$genre->id]);

        return $title;
    }
}
