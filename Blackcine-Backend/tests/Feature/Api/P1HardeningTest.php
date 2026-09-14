<?php

namespace Tests\Feature\Api;

use App\Models\Article;
use App\Models\Title;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class P1HardeningTest extends TestCase
{
    use RefreshDatabase;

    // ---------- TMDB proxy ----------

    public function test_tmdb_proxy_rejects_non_whitelisted_path(): void
    {
        $response = $this->getJson('/api/v1/tmdb/account/123');

        $response->assertStatus(403)
            ->assertJson(['success' => false]);
    }

    public function test_tmdb_proxy_rejects_path_traversal(): void
    {
        $response = $this->getJson('/api/v1/tmdb/movie//account');

        $response->assertStatus(400)
            ->assertJson(['success' => false]);
    }

    public function test_tmdb_proxy_returns_503_when_unconfigured(): void
    {
        config(['services.tmdb.key' => null, 'services.tmdb.bearer' => null]);

        $response = $this->getJson('/api/v1/tmdb/movie/550');

        $response->assertStatus(503)
            ->assertJson(['success' => false]);
    }

    public function test_tmdb_proxy_forwards_and_caches_upstream(): void
    {
        config([
            'services.tmdb.key' => 'fake-test-key',
            'services.tmdb.bearer' => null,
            'services.tmdb.base_url' => 'https://tmdb.test/3',
        ]);

        Http::fake([
            'tmdb.test/*' => Http::response(['id' => 550, 'title' => 'Fight Club'], 200),
        ]);

        $first = $this->getJson('/api/v1/tmdb/movie/550');
        $first->assertStatus(200)->assertJson(['id' => 550]);

        $second = $this->getJson('/api/v1/tmdb/movie/550');
        $second->assertStatus(200)->assertJson(['id' => 550]);

        // 2 appels HTTP entrants, 1 seul sortant = cache 10min OK
        Http::assertSentCount(1);
        Http::assertSent(fn ($request) => $request['language'] === 'fr-FR'
            && $request['api_key'] === 'fake-test-key');
    }

    public function test_tmdb_image_config_exposes_base_url(): void
    {
        $response = $this->getJson('/api/v1/tmdb/image-config');

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'data' => ['image_base_url', 'poster_size']]);
    }

    // ---------- Search : LIKE échappé (fallback SQLite) ----------

    public function test_search_treats_underscore_as_literal(): void
    {
        Title::factory()->create(['name' => 'A_B Film', 'type' => 'movie', 'status' => 'published']);
        Title::factory()->create(['name' => 'AXB Film', 'type' => 'movie', 'status' => 'published']);

        $response = $this->getJson('/api/v1/search?q=A_B&type=films');

        $response->assertStatus(200)->assertJson(['total' => 1]);
        $names = collect($response->json('data.films'))->pluck('name')->all();
        $this->assertContains('A_B Film', $names);
        $this->assertNotContains('AXB Film', $names);
    }

    public function test_search_treats_percent_as_literal(): void
    {
        Title::factory()->create(['name' => '100% Cacao', 'type' => 'movie', 'status' => 'published']);
        Title::factory()->create(['name' => '1000 Bornes', 'type' => 'movie', 'status' => 'published']);

        $response = $this->getJson('/api/v1/search?q=100%25&type=films');

        $response->assertStatus(200)->assertJson(['total' => 1]);
        $names = collect($response->json('data.films'))->pluck('name')->all();
        $this->assertContains('100% Cacao', $names);
        $this->assertNotContains('1000 Bornes', $names);
    }

    public function test_titles_films_search_treats_underscore_as_literal(): void
    {
        Title::factory()->create(['name' => 'A_B Film', 'type' => 'movie', 'status' => 'published']);
        Title::factory()->create(['name' => 'AXB Film', 'type' => 'movie', 'status' => 'published']);

        $response = $this->getJson('/api/v1/titles/films?search=A_B');

        $response->assertStatus(200);
        $names = collect($response->json('data'))->pluck('name')->all();
        $this->assertContains('A_B Film', $names);
        $this->assertNotContains('AXB Film', $names);
    }

    public function test_search_finds_article_by_body(): void
    {
        Article::factory()->create([
            'title' => 'Titre Quelconque',
            'status' => 'published',
            'body' => 'Le mot unique zinzolin se trouve ici',
        ]);

        $response = $this->getJson('/api/v1/search?q=zinzolin&type=articles');

        $response->assertStatus(200)->assertJson(['total' => 1]);
    }

    public function test_search_type_filter_limits_sections(): void
    {
        $response = $this->getJson('/api/v1/search?q=xy&type=articles');

        $response->assertStatus(200);
        $this->assertArrayHasKey('articles', $response->json('data'));
        $this->assertArrayNotHasKey('films', $response->json('data'));
    }

    // ---------- limit max:50 (anti-OOM) ----------

    public function test_festivals_rejects_huge_limit(): void
    {
        $this->getJson('/api/v1/festivals?limit=500')->assertStatus(422)
            ->assertJsonValidationErrors(['limit']);
    }

    public function test_festivals_rejects_zero_limit(): void
    {
        $this->getJson('/api/v1/festivals?limit=0')->assertStatus(422)
            ->assertJsonValidationErrors(['limit']);
    }

    public function test_festivals_accepts_valid_limit(): void
    {
        $this->getJson('/api/v1/festivals?limit=5')->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    // ---------- Footer COUNT cachés ----------

    public function test_footer_stats_are_cached(): void
    {
        Title::factory()->create(['type' => 'movie']);

        $first = $this->getJson('/api/v1/footer');
        $first->assertStatus(200)
            ->assertJsonStructure(['data' => ['partners', 'stats']]);
        $this->assertSame(1, $first->json('data.stats.films'));

        // Insertion sans events (sinon l'observer invaliderait le cache)
        Title::withoutEvents(fn () => Title::factory()->create(['type' => 'movie']));

        // Cache hit : toujours 1
        $this->assertSame(1, $this->getJson('/api/v1/footer')->json('data.stats.films'));

        Cache::forget('footer_stats');

        $this->assertSame(2, $this->getJson('/api/v1/footer')->json('data.stats.films'));
    }

    // ---------- Home : invalidation cache à la sauvegarde ----------

    public function test_home_cache_invalidated_when_article_published(): void
    {
        $this->getJson('/api/v1/home')->assertStatus(200);

        Article::factory()->create([
            'title' => 'Article Invalidation Cache XYZ',
            'slug' => 'article-invalidation-cache-xyz',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->getJson('/api/v1/home');
        $response->assertStatus(200);
        $titles = collect($response->json('data.featured_articles'))->pluck('title')->all();
        $this->assertContains('Article Invalidation Cache XYZ', $titles);
    }
}
