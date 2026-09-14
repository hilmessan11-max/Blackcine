<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // ================================
        // Permissions de base
        // ================================
        $permissions = [
            'manage users',       // gérer les utilisateurs
            'edit articles',      // rédiger/modifier articles
            'view finances',      // consulter finances
            'manage tickets',     // gérer tickets
            'manage partners',    // gérer partenaires
            'manage community',   // gérer communauté
            'manage seo',         // gérer SEO
            'manage marketing',   // gérer marketing
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // ================================
        // Création des rôles
        // ================================
        $roles = [
            'SuperAdmin'  => $permissions, // accès total
            'Admin'       => ['manage users', 'edit articles', 'view finances'],
            'Rédacteur'   => ['edit articles'],
            'Modérateur'  => ['manage community'],
            'TicketMgr'   => ['manage tickets'],
            'PartnerMgr'  => ['manage partners'],
            'Finance'     => ['view finances'],
            'CommunityMgr'=> ['manage community'],
            'SEO'         => ['manage seo'],
            'Marketing'   => ['manage marketing'],
        ];

        foreach ($roles as $roleName => $rolePerms) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePerms);
        }

        $this->command->info('✅ Rôles et permissions créés avec succès.');
    }
}
