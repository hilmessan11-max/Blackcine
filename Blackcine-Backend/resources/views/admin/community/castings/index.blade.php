@extends('layouts.app')

@section('title', 'Castings - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Castings</h1>
        <p class="text-gray-600 mt-2">Gérez les offres de casting</p>
    </div>
    <a href="{{ route('admin.community.castings.create') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center shadow-sm transition-colors">
        <span class="material-symbols-outlined mr-2">add_circle</span>
        Créer un casting
    </a>
</div>

<!-- Stats rapides -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total castings</p>
                <p class="text-2xl font-bold text-gray-900">{{ $castings->total() }}</p>
            </div>
            <div class="bg-purple-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-purple-600">folder_shared</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Publiés</p>
                <p class="text-2xl font-bold text-gray-900">{{ $castings->where('status', 'published')->count() }}</p>
            </div>
            <div class="bg-green-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-green-600">check_circle</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Fermés</p>
                <p class="text-2xl font-bold text-gray-900">{{ $castings->where('status', 'closed')->count() }}</p>
            </div>
            <div class="bg-gray-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-gray-600">lock</span>
            </div>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="bg-white rounded-lg shadow-sm p-4 mb-6 border border-gray-100">
    <form method="GET" action="{{ route('admin.community.castings.index') }}" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="material-symbols-outlined text-gray-400">search</span>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..." 
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
        </div>
        <div class="w-full md:w-48">
            <input type="text" name="country" value="{{ request('country') }}" placeholder="Pays..." 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
        </div>
        <div class="w-full md:w-48">
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
                <option value="">Tous les statuts</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Brouillon</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publié</option>
                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Fermé</option>
            </select>
        </div>
        <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-900 transition-colors">
            Filtrer
        </button>
    </form>
</div>

<!-- Liste -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100">
    <div class="table-container">
        <table class="w-full min-w-max">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Casting</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Rôle</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Localisation</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Dates</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($castings as $casting)
                <tr class="hover:bg-gray-50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-start">
                            <div class="bg-purple-50 group-hover:bg-purple-100 p-2 rounded-lg mr-3 transition-colors">
                                <span class="material-symbols-outlined text-purple-600">folder_shared</span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $casting->title }}</div>
                                @if($casting->description)
                                    <div class="text-xs text-gray-500 mt-0.5">{{ Str::limit($casting->description, 60) }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $casting->role }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <span class="material-symbols-outlined text-gray-400 mr-1 text-base">location_on</span>
                            @if($casting->city && $casting->country)
                                {{ $casting->city }}, {{ $casting->country }}
                            @elseif($casting->country)
                                {{ $casting->country }}
                            @else
                                N/A
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        @if($casting->start_date && $casting->end_date)
                            <div class="flex items-center">
                                <span class="material-symbols-outlined text-gray-400 mr-1 text-base">event</span>
                                <div>
                                    <div>{{ $casting->start_date->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-400">au {{ $casting->end_date->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        @elseif($casting->start_date)
                            Dès le {{ $casting->start_date->format('d/m/Y') }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($casting->status === 'published')
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 border border-green-200">Publié</span>
                        @elseif($casting->status === 'draft')
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 border border-gray-200">Brouillon</span>
                        @elseif($casting->status === 'closed')
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 border border-red-200">Fermé</span>
                        @else
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 border border-gray-200">{{ ucfirst($casting->status) }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('admin.community.castings.edit', $casting->id) }}" class="p-1 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded transition-colors" title="Modifier">
                                <span class="material-symbols-outlined">edit</span>
                            </a>
                            <form action="{{ route('admin.community.castings.destroy', $casting->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce casting ?');">
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
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="bg-gray-100 p-4 rounded-full mb-3">
                                <span class="material-symbols-outlined text-3xl text-gray-400">folder_shared</span>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Aucun casting</h3>
                            <p class="text-gray-500 mt-1">Créez votre première offre de casting.</p>
                            <a href="{{ route('admin.community.castings.create') }}" class="mt-4 text-red-600 hover:text-red-700 font-medium">
                                Créer un casting
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $castings->links() }}
    </div>
</div>
@endsection
