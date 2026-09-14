<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->get();
        $permissions = Permission::all()->groupBy(function($permission) {
            // Grouper par catégorie (première partie avant le point)
            $parts = explode('.', $permission->name);
            return $parts[0] ?? 'other';
        });
        
        return view('admin.settings.roles.index', compact('roles', 'permissions'));
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            $parts = explode('.', $permission->name);
            return $parts[0] ?? 'other';
        });
        
        return view('admin.settings.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name|max:255',
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return redirect()->route('admin.settings.roles.index')
            ->with('success', 'Rôle créé avec succès !');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            $parts = explode('.', $permission->name);
            return $parts[0] ?? 'other';
        });
        
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        
        return view('admin.settings.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // On ne permet pas de modifier le nom du rôle pour éviter les problèmes
        
        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('admin.settings.roles.index')
            ->with('success', 'Rôle mis à jour avec succès !');
    }

    public function destroy(Role $role)
    {
        // Vérifier que ce n'est pas un rôle système critique
        if (in_array($role->name, ['SuperAdmin', 'Admin'])) {
            return back()->with('error', 'Impossible de supprimer ce rôle système.');
        }

        $role->delete();

        return redirect()->route('admin.settings.roles.index')
            ->with('success', 'Rôle supprimé avec succès !');
    }
}
