<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Rôles et permissions
        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);

        // Utilisateur SuperAdmin par défaut (avant HomePageSeeder)
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        if (!$admin->hasRole('SuperAdmin')) {
            $admin->assignRole('SuperAdmin');
        }

        // HomePageSeeder après création de l'utilisateur
        $this->call([
            HomePageSeeder::class,
        ]);
    }
}
