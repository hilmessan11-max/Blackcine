@extends('layouts.app')

@section('title', 'Gestion des Rôles & Permissions - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div><h1 class="text-3xl font-bold text-gray-800">Rôles & Permissions</h1><p class="text-gray-600 mt-2">Gérez les rôles et leurs accès</p></div>
    <a href="{{ route('admin.settings.roles.create') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center shadow-sm transition-colors"><span class="material-symbols-outlined mr-2">add_moderator</span>Nouveau Rôle</a>
</div>

<!-- Liste des rôles -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    @foreach($roles as $role)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <div class="flex items-center gap-3">
                @if($role->name == 'SuperAdmin')
                    <div class="bg-red-100 p-2 rounded-full"><span class="material-symbols-outlined text-red-600">admin_panel_settings</span></div>
                @elseif($role->name == 'Admin')
                    <div class="bg-blue-100 p-2 rounded-full"><span class="material-symbols-outlined text-blue-600">security</span></div>
                @else
                    <div class="bg-gray-100 p-2 rounded-full"><span class="material-symbols-outlined text-gray-600">group</span></div>
                @endif
                <h3 class="font-bold text-gray-800 text-lg">{{ $role->name }}</h3>
            </div>
            <span class="bg-white border border-gray-200 text-gray-600 text-xs px-2.5 py-1 rounded-full font-medium shadow-sm">{{ $role->users_count }} users</span>
        </div>

        <div class="p-6">
            @if($role->description)
                <div class="mb-4">
                    <p class="text-sm text-gray-600">{{ $role->description }}</p>
                </div>
            @endif

            <div class="mb-4">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2 flex items-center"><span class="material-symbols-outlined text-sm mr-1">vpn_key</span>Permissions</div>
                <div class="text-sm text-gray-900 mb-2">{{ $role->permissions->count() }} permission(s)</div>
                @if($role->permissions->count() > 0)
                    <div class="flex flex-wrap gap-1">
                        @foreach($role->permissions->take(3) as $permission)
                            <span class="inline-block bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded border border-gray-200">{{ $permission->name }}</span>
                        @endforeach
                        @if($role->permissions->count() > 3)
                            <span class="text-xs text-gray-400 flex items-center">+{{ $role->permissions->count() - 3 }}</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="px-6 py-3 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
            <a href="{{ route('admin.settings.roles.edit', $role) }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center transition-colors"><span class="material-symbols-outlined text-sm mr-1">edit</span>Modifier</a>
            
            @if(!in_array($role->name, ['SuperAdmin', 'Admin']))
                <form action="{{ route('admin.settings.roles.destroy', $role) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce rôle ?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium flex items-center transition-colors"><span class="material-symbols-outlined text-sm mr-1">delete</span>Supprimer</button>
                </form>
            @else
                <span class="text-xs text-gray-400 flex items-center"><span class="material-symbols-outlined text-sm mr-1">lock</span>Système</span>
            @endif
        </div>
    </div>
    @endforeach
</div>

<!-- Matrice des permissions -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <h2 class="text-lg font-bold text-gray-800 flex items-center"><span class="material-symbols-outlined mr-2 text-red-600">grid_view</span>Matrice des Permissions</h2>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($permissions as $category => $perms)
                <div class="border border-gray-200 rounded-lg p-4 hover:border-red-200 transition-colors">
                    <h4 class="font-bold text-gray-900 mb-3 capitalize flex items-center border-b pb-2"><span class="material-symbols-outlined mr-2 text-gray-400 text-sm">folder</span>{{ str_replace('_', ' ', $category) }}</h4>
                    <div class="space-y-2">
                        @foreach($perms as $permission)
                            <div class="flex items-center justify-between text-sm group">
                                <span class="text-gray-700 group-hover:text-red-600 transition-colors">{{ $permission->name }}</span>
                                <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">{{ $roles->filter(fn($role) => $role->hasPermissionTo($permission))->count() }} rôles</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
