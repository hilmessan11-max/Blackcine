@extends('layouts.app')

@section('title', $serie->title . ' - Gestion Séries')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">{{ $serie->title }}</h1>
        <p class="text-gray-600 mt-2">{{ $serie->overview }}</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('admin.series.edit', $serie->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Modifier
        </a>
        <a href="{{ route('admin.series.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400">
            Retour
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Informations principales -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Informations</h2>
            <div class="space-y-3">
                <div>
                    <span class="text-sm font-medium text-gray-600">Catégorie :</span>
                    <span class="ml-2 text-gray-900">{{ $serie->category }}</span>
                </div>
                @if($serie->seasons)
                <div>
                    <span class="text-sm font-medium text-gray-600">Saisons :</span>
                    <span class="ml-2 text-gray-900">{{ $serie->seasons }}</span>
                </div>
                @endif
                @if($serie->country)
                <div>
                    <span class="text-sm font-medium text-gray-600">Pays :</span>
                    <span class="ml-2 text-gray-900">{{ $serie->country }}</span>
                </div>
                @endif
                @if($serie->language)
                <div>
                    <span class="text-sm font-medium text-gray-600">Langue :</span>
                    <span class="ml-2 text-gray-900">{{ $serie->language }}</span>
                </div>
                @endif
            </div>
        </div>

        @if($serie->genres && count($serie->genres) > 0)
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Genres</h2>
            <div class="flex flex-wrap gap-2">
                @foreach($serie->genres as $genre)
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">{{ $genre }}</span>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Bandes-annonces & Trailers -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">Bandes-annonces & Trailers</h2>
                <button onclick="document.getElementById('add-trailer-form').classList.toggle('hidden')" class="text-sm text-blue-600 hover:text-blue-800">
                    + Ajouter
                </button>
            </div>

            <!-- Formulaire d'ajout (caché par défaut) -->
            <div id="add-trailer-form" class="hidden mb-6 p-4 bg-gray-50 rounded-lg">
                <form action="{{ route('admin.series.trailers.store', $serie->id) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
                            <input type="text" name="title" required class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Bande-annonce officielle">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type de source</label>
                            <select name="source_type" required class="w-full px-3 py-2 border border-gray-300 rounded-md" onchange="toggleSourceFields(this.value)">
                                <option value="youtube">YouTube</option>
                                <option value="vimeo">Vimeo</option>
                                <option value="asset">Fichier local</option>
                            </select>
                        </div>
                        <div id="url-field">
                            <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                            <input type="url" name="source_url" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="https://youtube.com/watch?v=...">
                        </div>
                        <div id="asset-field" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Asset ID</label>
                            <input type="number" name="asset_id" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_official" value="1" class="mr-2">
                                <span class="text-sm text-gray-700">Bande-annonce officielle</span>
                            </label>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">Ajouter</button>
                    </div>
                </form>
            </div>

            <!-- Liste des trailers -->
            <div class="space-y-3">
                @forelse($serie->trailers as $trailer)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $trailer->title }}</h3>
                            <p class="text-sm text-gray-600">
                                {{ ucfirst($trailer->source_type) }}
                                @if($trailer->source_url)
                                    - <a href="{{ $trailer->source_url }}" target="_blank" class="text-blue-600 hover:text-blue-800">Voir</a>
                                @endif
                                @if($trailer->is_official)
                                    <span class="ml-2 px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Officiel</span>
                                @endif
                            </p>
                        </div>
                        <form action="{{ route('admin.series.trailers.destroy', ['id' => $serie->id, 'trailerId' => $trailer->id]) }}" method="POST" onsubmit="return confirm('Supprimer cette bande-annonce ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Aucune bande-annonce ajoutée.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Actions</h3>
            <div class="space-y-2">
                <a href="{{ route('admin.series.edit', $serie->id) }}" class="block w-full text-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Modifier
                </a>
                <form action="{{ route('admin.series.destroy', $serie->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette série ?');" class="w-full">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleSourceFields(type) {
    const urlField = document.getElementById('url-field');
    const assetField = document.getElementById('asset-field');
    
    if (type === 'asset') {
        urlField.classList.add('hidden');
        assetField.classList.remove('hidden');
    } else {
        urlField.classList.remove('hidden');
        assetField.classList.add('hidden');
    }
}
</script>
@endsection
