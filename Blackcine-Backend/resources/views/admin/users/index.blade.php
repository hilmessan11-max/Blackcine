@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Utilisateurs</h1>
        <p class="text-gray-600 mt-2">Gérez les utilisateurs de la plateforme</p>
    </div>
    <div class="flex gap-3">
        <a href="#" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 flex items-center shadow-sm transition-colors">
            <span class="material-symbols-outlined mr-2">file_download</span>
            Exporter
        </a>
        <a href="#" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center shadow-sm transition-colors">
            <span class="material-symbols-outlined mr-2">add_circle</span>
            Nouvel utilisateur
        </a>
    </div>
</div>

<!-- Stats rapides -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total utilisateurs</p>
                <p class="text-2xl font-bold text-gray-900">{{ $users->total() }}</p>
            </div>
            <div class="bg-blue-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-blue-600">people</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Actifs (30j)</p>
                <p class="text-2xl font-bold text-gray-900">-</p>
            </div>
            <div class="bg-green-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-green-600">check_circle</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Nouveaux (7j)</p>
                <p class="text-2xl font-bold text-gray-900">-</p>
            </div>
            <div class="bg-purple-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-purple-600">trending_up</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Pro accounts</p>
                <p class="text-2xl font-bold text-gray-900">-</p>
            </div>
            <div class="bg-yellow-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-yellow-600">workspace_premium</span>
            </div>
        </div>
    </div>
</div>

<!-- Filtres et Recherche -->
<div class="bg-white rounded-lg shadow-sm p-4 mb-6 border border-gray-100">
    <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="material-symbols-outlined text-gray-400">search</span>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher par nom ou email..." 
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
        </div>
        <div class="w-full md:w-48">
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
                <option value="">Tous les statuts</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactif</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspendu</option>
            </select>
        </div>
        <div class="w-full md:w-48">
            <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
                <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Plus récents</option>
                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Plus anciens</option>
                <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Nom A-Z</option>
                <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Nom Z-A</option>
            </select>
        </div>
        <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-900 transition-colors">
            Filtrer
        </button>
    </form>
</div>

<div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100">
    <div class="table-container">
        <table class="w-full min-w-max">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Utilisateur</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Inscription</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="h-10 w-10 flex-shrink-0">
                                <img class="h-10 w-10 rounded-full border-2 border-gray-200 group-hover:border-red-300 transition-colors" 
                                    src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=DC2626&color=fff' }}" 
                                    alt="{{ $user->name }}">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                @if($user->is_pro ?? false)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 mt-1">
                                        <span class="material-symbols-outlined text-xs mr-1">workspace_premium</span>
                                        Pro
                                    </span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <span class="material-symbols-outlined text-gray-400 mr-2 text-base">email</span>
                            {{ $user->email }}
                        </div>
                        @if($user->email_verified_at)
                            <span class="inline-flex items-center text-xs text-green-600 mt-1">
                                <span class="material-symbols-outlined text-xs mr-1">verified</span>
                                Vérifié
                            </span>
                        @else
                            <span class="inline-flex items-center text-xs text-gray-400 mt-1">
                                <span class="material-symbols-outlined text-xs mr-1">error</span>
                                Non vérifié
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center text-sm text-gray-500">
                            <span class="material-symbols-outlined text-gray-400 mr-1 text-base">calendar_today</span>
                            {{ $user->created_at->format('d/m/Y') }}
                        </div>
                        <div class="text-xs text-gray-400 mt-1">
                            Il y a {{ $user->created_at->diffForHumans() }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($user->is_active ?? true)
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 border border-green-200">
                                Actif
                            </span>
                        @else
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                                Inactif
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('admin.users.show', $user) }}" class="p-1 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded transition-colors" title="Voir">
                                <span class="material-symbols-outlined">visibility</span>
                            </a>
                            <a href="#" class="p-1 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded transition-colors" title="Modifier">
                                <span class="material-symbols-outlined">edit</span>
                            </a>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1 text-red-600 hover:text-red-900 hover:bg-red-50 rounded transition-colors" title="Supprimer">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="bg-gray-100 p-4 rounded-full mb-3">
                                <span class="material-symbols-outlined text-3xl text-gray-400">people</span>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Aucun utilisateur</h3>
                            <p class="text-gray-500 mt-1">Aucun utilisateur ne correspond aux critères de recherche.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $users->links() }}
    </div>
</div>
@endsection
