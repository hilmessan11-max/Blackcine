@extends('layouts.app')

@section('title', 'Festivals - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Festivals</h1>
        <p class="text-gray-600 mt-2">Gestion des festivals partenaires</p>
    </div>
    <a href="{{ route('admin.partners.festivals.create') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center shadow-sm transition-colors">
        <span class="material-symbols-outlined mr-2">add_circle</span>
        Créer un festival
    </a>
</div>

<!-- Stats rapides -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total festivals</p>
                <p class="text-2xl font-bold text-gray-900">{{ $festivals->total() }}</p>
            </div>
            <div class="bg-purple-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-purple-600">celebration</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">En cours</p>
                <p class="text-2xl font-bold text-gray-900">{{ $festivals->filter(fn($f) => $f->starts_at <= now() && $f->ends_at >= now())->count() }}</p>
            </div>
            <div class="bg-green-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-green-600">event_available</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Films associés</p>
                <p class="text-2xl font-bold text-gray-900">{{ $festivals->sum(fn($f) => $f->titles->count()) }}</p>
            </div>
            <div class="bg-blue-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-blue-600">movie</span>
            </div>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="bg-white rounded-lg shadow-sm p-4 mb-6 border border-gray-100">
    <form method="GET" action="{{ route('admin.partners.festivals.index') }}" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="material-symbols-outlined text-gray-400">search</span>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un festival..." 
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
        </div>
        <div class="w-full md:w-48">
            <input type="text" name="country" value="{{ request('country') }}" placeholder="Pays..." 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
        </div>
        <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-900 transition-colors">
            Filtrer
        </button>
    </form>
</div>

<!-- Liste des festivals -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100">
    <div class="table-container">
        <table class="w-full min-w-max">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Festival</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Localisation</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Dates</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Films</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($festivals as $festival)
                <tr class="hover:bg-gray-50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="bg-purple-50 group-hover:bg-purple-100 p-2 rounded-lg mr-3 transition-colors">
                                <span class="material-symbols-outlined text-purple-600">celebration</span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $festival->name }}</div>
                                @if($festival->starts_at && $festival->ends_at)
                                    @if($festival->starts_at <= now() && $festival->ends_at >= now())
                                        <span class="text-xs text-green-600 font-medium">En cours</span>
                                    @elseif($festival->starts_at > now())
                                        <span class="text-xs text-blue-600 font-medium">À venir</span>
                                    @else
                                        <span class="text-xs text-gray-400 font-medium">Terminé</span>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <span class="material-symbols-outlined text-gray-400 mr-2 text-base">location_on</span>
                            @if($festival->city && $festival->country)
                                {{ $festival->city }}, {{ $festival->country }}
                            @elseif($festival->country)
                                {{ $festival->country }}
                            @else
                                N/A
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        @if($festival->starts_at && $festival->ends_at)
                            <div class="flex items-center">
                                <span class="material-symbols-outlined text-gray-400 mr-1 text-base">event</span>
                                <div>
                                    <div>{{ $festival->starts_at->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-400">au {{ $festival->ends_at->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined text-blue-500 mr-1 text-base">movie</span>
                            <span class="text-sm font-medium text-gray-900">{{ $festival->titles->count() }}</span>
                            <span class="text-xs text-gray-500 ml-1">film(s)</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('admin.partners.festivals.edit', $festival->id) }}" class="p-1 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded transition-colors" title="Modifier">
                                <span class="material-symbols-outlined">edit</span>
                            </a>
                            <form action="{{ route('admin.partners.festivals.destroy', $festival->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce festival ?');">
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
                                <span class="material-symbols-outlined text-3xl text-gray-400">celebration</span>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Aucun festival</h3>
                            <p class="text-gray-500 mt-1">Commencez par ajouter votre premier festival.</p>
                            <a href="{{ route('admin.partners.festivals.create') }}" class="mt-4 text-red-600 hover:text-red-700 font-medium">
                                Créer un festival
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $festivals->links() }}
    </div>
</div>
@endsection
