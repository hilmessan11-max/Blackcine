<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AdminAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Modérateur', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'SuperAdmin', 'guard_name' => 'web']);
    }

    public function test_regular_user_cannot_access_admin_routes(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/admin/films', [
            'title' => 'Test Film',
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_user_can_access_admin_routes(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Admin');
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/admin/films');

        $response->assertDontSee('Accès réservé aux administrateurs');
    }

    public function test_unauthenticated_user_cannot_access_admin_routes(): void
    {
        $response = $this->postJson('/api/v1/admin/films', [
            'title' => 'Test Film',
        ]);

        $response->assertStatus(401);
    }

    public function test_user_with_wrong_role_cannot_access_admin_routes(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Modérateur');
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/admin/films', [
            'title' => 'Test Film',
        ]);

        $response->assertStatus(403);
    }
}
