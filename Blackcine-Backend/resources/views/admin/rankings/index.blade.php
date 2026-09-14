@extends('layouts.app')

@section('title', 'Classements & Rankings')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800 flex items-center">
            <span class="material-symbols-outlined mr-3 text-3xl text-red-600">emoji_events</span>
            Classements & Rankings
        </h1>
        <p class="text-gray-600 mt-2">Gérez les différents classements de titres</p>
    </div>
    <a href="{{ route('admin.rankings.create') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition shadow-md hover:shadow-lg flex items-center">
        <span class="material-symbols-outlined mr-2">add</span>
        Nouveau Classement
    </a>
</div>

<!-- Statistiques -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="card bg-white shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm flex items-center mb-1">
                    <span class="material-symbols-outlined mr-1 text-base text-gray-400">emoji_events</span>
                    Total Classements
                </p>
                <p class="text-3xl font-bold text-gray-900">{{ $rankings->total() }}</p>
            </div>
        </div>
    </div>

    <div class="card bg-white shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm flex items-center mb-1">
                    <span class="material-symbols-outlined mr-1 text-base text-green-500">check_circle</span>
                    Actifs
                </p>
                <p class="text-3xl font-bold text-green-600">{{ $rankings->where('is_active', true)->count() }}</p>
            </div>
        </div>
    </div>

    <div class="card bg-white shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm flex items-center mb-1">
                    <span class="material-symbols-outlined mr-1 text-base text-purple-500">category</span>
                    Par genre
                </p>
                <p class="text-3xl font-bold text-purple-600">{{ $rankings->where('type', 'genre')->count() }}</p>
            </div>
        </div>
    </div>

    <div class="card bg-white shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm flex items-center mb-1">
                    <span class="material-symbols-outlined mr-1 text-base text-blue-500">public</span>
                    Généraux
                </p>
                <p class="text-3xl font-bold text-blue-600">{{ $rankings->where('type', 'general')->count() }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Liste des classements -->
<div class="card bg-white shadow-lg overflow-hidden">
    <div class="table-container">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Période</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Titres</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($rankings as $ranking)
                <tr class="table-row">
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-medium text-gray-900">{{ $ranking->name }}</p>
                            @if($ranking->description)
                                <p class="text-sm text-gray-600">{{ Str::limit($ranking->description, 60) }}</p>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                            {{ $ranking->type === 'general' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $ranking->type === 'genre' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $ranking->type === 'country' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $ranking->type === 'year' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $ranking->type === 'custom' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ ucfirst($ranking->type) }}
                        </span>
                        @if($ranking->genre)
                            <span class="block text-xs text-gray-600 mt-1">{{ $ranking->genre }}</span>
                        @endif
                        @if($ranking->country)
                            <span class="block text-xs text-gray-600 mt-1 flex items-center">
                                <span class="material-symbols-outlined text-xs mr-1">public</span>
                                {{ $ranking->country }}
                            </span>
                        @endif
                        @if($ranking->year)
                            <span class="block text-xs text-gray-600 mt-1 flex items-center">
                                <span class="material-symbols-outlined text-xs mr-1">calendar_today</span>
                                {{ $ranking->year }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <span class="capitalize">{{ str_replace('_', ' ', $ranking->period) }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-2xl font-bold text-gray-900">{{ $ranking->entries->count() }}</span>
                        <span class="text-sm text-gray-600">titres</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full flex items-center w-fit {{ $ranking->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            <span class="material-symbols-outlined text-xs mr-1">{{ $ranking->is_active ? 'check_circle' : 'cancel' }}</span>
                            {{ $ranking->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center space-x-2">
                            <a href="{{ route('admin.rankings.show', $ranking->id) }}" class="text-green-600 hover:text-green-800 transition-colors" title="Voir">
                                <span class="material-symbols-outlined text-xl">visibility</span>
                            </a>
                            <a href="{{ route('admin.rankings.edit', $ranking->id) }}" class="text-blue-600 hover:text-blue-800 transition-colors" title="Modifier">
                                <span class="material-symbols-outlined text-xl">edit</span>
                            </a>
                            <form action="{{ route('admin.rankings.toggle-active', $ranking->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="{{ $ranking->is_active ? 'text-gray-600 hover:text-gray-800' : 'text-green-600 hover:text-green-800' }} transition-colors" title="{{ $ranking->is_active ? 'Désactiver' : 'Activer' }}">
                                    <span class="material-symbols-outlined text-xl">{{ $ranking->is_active ? 'toggle_on' : 'toggle_off' }}</span>
                                </button>
                            </form>
                            <form action="{{ route('admin.rankings.destroy', $ranking->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce classement ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 transition-colors" title="Supprimer">
                                    <span class="material-symbols-outlined text-xl">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <span class="material-symbols-outlined text-6xl text-gray-400 mb-4 block">emoji_events</span>
                        <p class="mt-4 text-lg text-gray-600">Aucun classement trouvé</p>
                        <a href="{{ route('admin.rankings.create') }}" class="mt-2 inline-block text-red-600 hover:text-red-800 font-semibold flex items-center justify-center">
                            <span class="material-symbols-outlined mr-2">add</span>
                            Créer votre premier classement
                        </a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    <!-- Pagination -->
    @if($rankings->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $rankings->links() }}
        </div>
    @endif
</div>
@endsection
