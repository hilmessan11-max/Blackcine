@extends('layouts.app')

@section('title', 'Gérer la série - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">{{ $series->name }}</h1>
        <p class="text-gray-600 mt-2">Gestion des saisons et épisodes</p>
    </div>
    <a href="{{ route('admin.catalog.edit', $series->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
        Modifier la série
    </a>
</div>

<!-- Informations de la série -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Informations</h2>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <p class="text-sm text-gray-600">Synopsis</p>
            <p class="text-gray-900">{{ $series->synopsis ?? 'N/A' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Date de sortie</p>
            <p class="text-gray-900">{{ $series->release_date ? $series->release_date->format('Y') : 'N/A' }}</p>
        </div>
    </div>
</div>

<!-- Saisons -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-gray-800">Saisons</h2>
        <button onclick="document.getElementById('seasonModal').classList.remove('hidden')" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
            + Ajouter une saison
        </button>
    </div>

    @forelse($series->seasons as $season)
    <div class="border-b border-gray-200 pb-4 mb-4 last:border-0 last:pb-0 last:mb-0">
        <div class="flex justify-between items-start mb-2">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">
                    Saison {{ $season->season_number }}
                    @if($season->name)
                        - {{ $season->name }}
                    @endif
                </h3>
                @if($season->synopsis)
                    <p class="text-sm text-gray-600 mt-1">{{ $season->synopsis }}</p>
                @endif
            </div>
            <span class="text-sm text-gray-500">{{ $season->episodes->count() }} épisode(s)</span>
        </div>

        <!-- Épisodes de la saison -->
        <div class="ml-4 mt-3">
            <h4 class="text-sm font-medium text-gray-700 mb-2">Épisodes</h4>
            @if($season->episodes->count() > 0)
                <ul class="space-y-1">
                    @foreach($season->episodes->sortBy('episode_number') as $episode)
                    <li class="text-sm text-gray-600">
                        Épisode {{ $episode->episode_number }}: {{ $episode->name ?? 'Sans titre' }}
                        @if($episode->runtime_seconds)
                            ({{ gmdate('H:i', $episode->runtime_seconds) }})
                        @endif
                    </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-gray-400">Aucun épisode</p>
            @endif
            <button onclick="openEpisodeModal({{ $season->id }})" class="mt-2 text-sm text-blue-600 hover:text-blue-900">
                + Ajouter un épisode
            </button>
        </div>
    </div>
    @empty
    <p class="text-gray-500">Aucune saison créée</p>
    @endforelse
</div>

<!-- Modal Ajouter Saison -->
<div id="seasonModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Ajouter une saison</h3>
            <form action="{{ route('admin.catalog.series.seasons.store', $series->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="season_number">Numéro de saison *</label>
                    <input type="number" name="season_number" id="season_number" min="1" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Nom (optionnel)</label>
                    <input type="text" name="name" id="name"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="synopsis">Synopsis</label>
                    <textarea name="synopsis" id="synopsis" rows="3"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                </div>
                <div class="flex items-center justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('seasonModal').classList.add('hidden')" class="text-gray-600 hover:text-gray-800">
                        Annuler
                    </button>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                        Créer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ajouter Épisode -->
<div id="episodeModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Ajouter un épisode</h3>
            <form id="episodeForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="episode_number">Numéro d'épisode *</label>
                    <input type="number" name="episode_number" id="episode_number" min="1" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="episode_name">Nom (optionnel)</label>
                    <input type="text" name="name" id="episode_name"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="episode_synopsis">Synopsis</label>
                    <textarea name="synopsis" id="episode_synopsis" rows="3"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="runtime_seconds">Durée (secondes)</label>
                    <input type="number" name="runtime_seconds" id="runtime_seconds" min="0" placeholder="Ex: 3600 pour 1h"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="air_date">Date de diffusion</label>
                    <input type="date" name="air_date" id="air_date"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_free" value="1" class="mr-2">
                        <span class="text-gray-700 text-sm">Épisode gratuit</span>
                    </label>
                </div>
                <div class="flex items-center justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('episodeModal').classList.add('hidden')" class="text-gray-600 hover:text-gray-800">
                        Annuler
                    </button>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                        Créer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEpisodeModal(seasonId) {
    document.getElementById('episodeForm').action = '/admin/series/{{ $series->id }}/seasons/' + seasonId + '/episodes';
    document.getElementById('episodeModal').classList.remove('hidden');
}
</script>
@endsection

