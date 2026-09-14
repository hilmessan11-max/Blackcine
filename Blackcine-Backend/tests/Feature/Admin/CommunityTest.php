<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class CommunityTest extends TestCase
{
    use RefreshDatabase;

    public function test_community_dashboard_is_accessible_for_community_manager(): void
    {
        $manager = $this->createCommunityManager();

        $response = $this->actingAs($manager)->get(route('admin.community.index'));

        $response->assertOk()
            ->assertSee('Gestion de la communauté')
            ->assertSee('Total utilisateurs');
    }

    public function test_ban_user_persists_ban_fields(): void
    {
        $manager = $this->createCommunityManager();
        $target = User::factory()->create();

        $response = $this->actingAs($manager)->post(route('admin.community.ban', $target->id), [
            'reason' => 'Violation des règles',
            'duration_days' => 7,
        ]);

        $response->assertRedirect(route('admin.community.index'));

        $target->refresh();

        $this->assertNotNull($target->banned_at);
        $this->assertNotNull($target->banned_until);
        $this->assertSame('Violation des règles', $target->ban_reason);
    }

    private function createCommunityManager(): User
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permission = Permission::firstOrCreate([
            'name' => 'manage community',
            'guard_name' => 'web',
        ]);

        $role = Role::firstOrCreate([
            'name' => 'CommunityMgr',
            'guard_name' => 'web',
        ]);

        if (! $role->hasPermissionTo($permission)) {
            $role->givePermissionTo($permission);
        }

        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }
}
