<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Title;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class FavoriteApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_favorites(): void
    {
        $response = $this->getJson('/api/v1/favorites');

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_list_favorites(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/favorites');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
            ]);
    }

    public function test_authenticated_user_can_add_favorite(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/favorites', [
            'type' => 'film',
            'item_id' => 1,
            'name' => 'Test Film',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Ajouté aux favoris',
            ]);
    }

    public function test_favorite_requires_type_and_item_id(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/favorites', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['type', 'item_id']);
    }

    public function test_favorite_invalid_type(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/favorites', [
            'type' => 'invalid',
            'item_id' => 1,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['type']);
    }

    public function test_authenticated_user_can_delete_favorite(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $favorite = $user->favorites()->create([
            'type' => 'film',
            'item_id' => 1,
            'name' => 'Test Film',
        ]);

        $response = $this->deleteJson("/api/v1/favorites/{$favorite->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);
    }

    public function test_user_cannot_delete_other_user_favorite(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Sanctum::actingAs($user1);

        $favorite = $user2->favorites()->create([
            'type' => 'film',
            'item_id' => 1,
            'name' => 'Test Film',
        ]);

        $response = $this->deleteJson("/api/v1/favorites/{$favorite->id}");

        $response->assertStatus(404);
    }
}
