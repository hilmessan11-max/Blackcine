@extends('layouts.app')

@section('title', 'Bandes-annonces - ' . $film->title)

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Bandes-annonces</h1>
            <p class="text-gray-600 mt-2">{{ $film->title }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.films.show', $film->id) }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400">
                ← Retour au film
            </a>
            <a href="{{ route('admin.films.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                 Liste films
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Liste des trailers -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lgsh shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">{{ $film->trailers->count() }} Bande(s)-annonce(s)</h2>
            </div>
            
            <div class="divide-y divide-gray-200">
                @forelse($film->trailers as $trailer)
                    <div class="p-6">
                        <div class="flex items-start space-x-4">
                            <!-- Icône source -->
                            <div class="flex-shrink-0">
                                @if($trailer->source_type === 'youtube')
                                    <div class="w-12 h-12 bg-red-600 rounded-lg flex items-center justify-center text-white text-xs font-bold">
                                        YT
                                    </div>
                                @elseif($trailer->source_type === 'vimeo')
                                    <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center text-white text-xs font-bold">
                                        VM
                                    </div>
                                @else
                                    <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center text-white text-xs font-bold">
                                        📁
                                    </div>
                                @endif
                            </div>

                            <!-- Contenu -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-2 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $trailer->title }}</h3>
                                    @if($trailer->is_official)
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">OFFICIEL</span>
                                    @endif
                                </div>
                                
                                <div class="text-sm text-gray-600 space-y-1">
                                    <p><strong>Type :</strong> {{ ucfirst($trailer->source_type) }}</p>
                                    @if($trailer->source_url)
                                        <p><strong>URL :</strong> <a href="{{ $trailer->source_url }}" target="_blank" class="text-blue-600 hover:underline">{{ Str::limit($trailer->source_url, 50) }}</a></p>
                                    @endif
                                    @if($trailer->asset_id)
                                        <p><strong>Asset ID :</strong> #{{ $trailer->asset_id }}</p>
                                    @endif
                                    <p class="text-xs text-gray-500">Ajouté le {{ $trailer->created_at->format('d/m/Y à H:i') }}</p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex-shrink-0">
                                <form action="{{ route('admin.films.trailers.destroy', ['id' => $film->id, 'trailerId' => $trailer->id]) }}" method="POST" onsubmit="return confirm('Supprimer cette bande-annonce ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Supprimer">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <p class="mt-4 text-lg text-gray-600">Aucune bande-annonce</p>
                        <p class="mt-2 text-sm text-gray-500">Ajoutez-en une via le formulaire ci-contre</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Formulaire d'ajout -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Ajouter une Bande-annonce</h3>
            
            <form action="{{ route('admin.films.trailers.store', $film->id) }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Titre *</label>
                    <input type="text" name="title" required value="{{ old('title') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Bande-annonce officielle">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type de source *</label>
                    <select name="source_type" id="sourceType" required onchange="toggleSourceFields()"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionnez...</option>
                        <option value="youtube">YouTube</option>
                        <option value="vimeo">Vimeo</option>
                        <option value="asset">Fichier local (Asset)</option>
                    </select>
                    @error('source_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div id="urlField" class="mb-4 hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">URL de la vidéo</label>
                    <input type="url" name="source_url"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="https://www.youtube.com/watch?v=...">
                    @error('source_url')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div id="assetField" class="mb-4 hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Asset ID</label>
                    <input type="number" name="asset_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="123">
                    @error('asset_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="is_official" value="1" {{ old('is_official') ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Bande-annonce officielle</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 font-semibold">
                    + Ajouter la Bande-annonce
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function toggleSourceFields() {
    const sourceType = document.getElementById('sourceType').value;
    const urlField = document.getElementById('urlField');
    const assetField = document.getElementById('assetField');
    
    urlField.classList.toggle('hidden', sourceType !== 'youtube' && sourceType !== 'vimeo');
    assetField.classList.toggle('hidden', sourceType !== 'asset');
}
</script>
@endsection
