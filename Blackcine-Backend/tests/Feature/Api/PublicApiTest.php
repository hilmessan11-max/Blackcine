<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Title;
use App\Models\Article;
use App\Models\NewsletterSubscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PublicApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_endpoint_returns_data(): void
    {
        $response = $this->getJson('/api/v1/home');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'featured_articles',
                    'featured_videos',
                    'top_films',
                    'top_series',
                ],
            ]);
    }

    public function test_search_requires_query(): void
    {
        $response = $this->getJson('/api/v1/search');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['q']);
    }

    public function test_search_with_valid_query(): void
    {
        Title::factory()->create(['name' => 'Film Test', 'status' => 'published']);

        $response = $this->getJson('/api/v1/search?q=Film');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'query',
                'total',
                'data',
            ]);
    }

    public function test_articles_endpoint(): void
    {
        Article::factory()->count(3)->create(['status' => 'published']);

        $response = $this->getJson('/api/v1/articles');

        $response->assertStatus(200);
    }

    public function test_newsletter_subscribe(): void
    {
        $response = $this->postJson('/api/v1/newsletter/subscribe', [
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'test@example.com',
        ]);
    }

    public function test_newsletter_requires_email(): void
    {
        $response = $this->postJson('/api/v1/newsletter/subscribe', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_newsletter_invalid_email(): void
    {
        $response = $this->postJson('/api/v1/newsletter/subscribe', [
            'email' => 'not-an-email',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
