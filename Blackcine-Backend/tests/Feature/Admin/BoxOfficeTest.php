<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class BoxOfficeTest extends TestCase
{
    use RefreshDatabase;

    public function test_boxoffice_dashboard_is_accessible(): void
    {
        $user = $this->createAdminUser();

        $response = $this->actingAs($user)->get(route('admin.boxoffice.index'));

        $response->assertOk();
    }

    private function createAdminUser(): User
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permission = Permission::firstOrCreate(['name' => 'manage tickets', 'guard_name' => 'web']);

        $role = Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);

        $role->givePermissionTo($permission);

        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }
}
