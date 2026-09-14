<?php

namespace Tests\Feature\Admin;

use App\Models\Film;
use App\Models\Serie;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class LegacyCatalogScreensTest extends TestCase
{
    use RefreshDatabase;

    public function test_film_admin_index_and_show_are_accessible(): void
    {
        $user = $this->createSuperAdmin();
        $film = Film::create([
            'title' => 'The Great Journey',
            'slug' => Str::slug('The Great Journey'),
            'overview' => 'A road movie across West Africa.',
            'country' => 'BJ',
            'language' => 'fr',
            'category' => 'tous',
            'year' => 2025,
        ]);

        $this->actingAs($user)
            ->get(route('admin.films.index'))
            ->assertOk()
            ->assertSee('Catalogue Films');

        $this->actingAs($user)
            ->get(route('admin.films.show', $film->id))
            ->assertOk()
            ->assertSee('The Great Journey');
    }

    public function test_series_admin_index_and_show_are_accessible(): void
    {
        $user = $this->createSuperAdmin();
        $serie = Serie::create([
            'title' => 'River Queens',
            'slug' => Str::slug('River Queens'),
            'overview' => 'A coastal family drama.',
            'country' => 'BJ',
            'language' => 'fr',
            'category' => 'toutes',
            'seasons' => 2,
        ]);

        $this->actingAs($user)
            ->get(route('admin.series.index'))
            ->assertOk()
            ->assertSee('Catalogue Séries');

        $this->actingAs($user)
            ->get(route('admin.series.show', $serie->id))
            ->assertOk()
            ->assertSee('River Queens');
    }

    public function test_film_admin_store_persists_poster_and_extra_fields(): void
    {
        $user = $this->createSuperAdmin();
        Storage::fake('public');

        $response = $this->actingAs($user)->post(route('admin.films.store'), [
            'title' => 'The Great Journey',
            'slug' => 'the-great-journey',
            'overview' => 'A road movie across West Africa.',
            'country' => 'BJ',
            'language' => 'fr',
            'genres' => ['Drame', 'Aventure'],
            'category' => 'tous',
            'year' => 2025,
            'duration_minutes' => 120,
            'rating' => 8.4,
            'is_featured' => 1,
            'poster' => UploadedFile::fake()->create('poster.jpg', 100, 'image/jpeg'),
        ]);

        $response->assertRedirect(route('admin.films.index'));

        $film = Film::firstOrFail();

        $this->assertSame(120, $film->duration_minutes);
        $this->assertSame(8.4, (float) $film->rating);
        $this->assertTrue((bool) $film->is_featured);
        $this->assertNotNull($film->poster_asset_id);
        $this->assertDatabaseHas('assets', [
            'id' => $film->poster_asset_id,
            'disk' => 'public',
        ]);
    }

    public function test_series_admin_store_persists_poster_status_and_rating(): void
    {
        $user = $this->createSuperAdmin();
        Storage::fake('public');

        $response = $this->actingAs($user)->post(route('admin.series.store'), [
            'title' => 'River Queens',
            'slug' => 'river-queens',
            'overview' => 'A coastal family drama.',
            'creator' => 'Awa Diop',
            'country' => 'BJ',
            'language' => 'fr',
            'genres' => ['Drame'],
            'category' => 'toutes',
            'seasons' => 2,
            'status' => 'ongoing',
            'first_air_date' => '2025-01-15',
            'rating' => 7.9,
            'is_featured' => 1,
            'poster' => UploadedFile::fake()->create('poster.jpg', 100, 'image/jpeg'),
        ]);

        $response->assertRedirect(route('admin.series.index'));

        $serie = Serie::firstOrFail();

        $this->assertSame('ongoing', $serie->status);
        $this->assertSame('Awa Diop', $serie->creator);
        $this->assertSame(7.9, (float) $serie->rating);
        $this->assertTrue((bool) $serie->is_featured);
        $this->assertNotNull($serie->poster_asset_id);
        $this->assertDatabaseHas('assets', [
            'id' => $serie->poster_asset_id,
            'disk' => 'public',
        ]);
    }

    private function createSuperAdmin(): User
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $role = Role::firstOrCreate([
            'name' => 'SuperAdmin',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }
}
