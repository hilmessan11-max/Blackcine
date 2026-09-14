@extends('layouts.app')

@section('title', 'Modifier la vidéo - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Modifier la vidéo</h1>
        <p class="text-gray-600 mt-2">Mettez à jour les informations de la vidéo</p>
    </div>
    <a href="{{ route('admin.videos.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
        <span class="material-symbols-outlined mr-1">arrow_back</span>
        Retour aux vidéos
    </a>
</div>

<form action="{{ route('admin.videos.update', $video->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonne principale -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations de base -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informations de la vidéo</h3>
                
                <div class="space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Titre de la vidéo <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title', $video->title) }}" required placeholder="Ex: Bande-annonce officielle..."
                            class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="description" rows="4" placeholder="Décrivez le contenu de la vidéo..."
                            class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">{{ old('description', $video->description ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Source de la vidéo -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Source de la vidéo</h3>
                
                <div class="space-y-6">
                    <div>
                        <label for="source_type" class="block text-sm font-medium text-gray-700 mb-1">Type de source <span class="text-red-500">*</span></label>
                        <select name="source_type" id="source_type" required
                            class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                            <option value="">-- Sélectionner --</option>
                            <option value="youtube" {{ old('source_type', $video->source_type) === 'youtube' ? 'selected' : '' }}>YouTube</option>
                            <option value="vimeo" {{ old('source_type', $video->source_type) === 'vimeo' ? 'selected' : '' }}>Vimeo</option>
                            <option value="external" {{ old('source_type', $video->source_type) === 'external' ? 'selected' : '' }}>Lien externe</option>
                            <option value="upload" {{ old('source_type', $video->source_type) === 'upload' ? 'selected' : '' }}>Upload fichier</option>
                        </select>
                    </div>

                    <div>
                        <label for="source_url" class="block text-sm font-medium text-gray-700 mb-1">URL de la vidéo</label>
                        <input type="url" name="source_url" id="source_url" value="{{ old('source_url', $video->source_url) }}"
                            placeholder="https://youtube.com/watch?v=... ou https://vimeo.com/..."
                            class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                        <p class="mt-1 text-xs text-gray-500">Pour YouTube, Vimeo ou lien externe</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="asset_id" class="block text-sm font-medium text-gray-700 mb-1">Asset ID (Fichier)</label>
                            <input type="number" name="asset_id" id="asset_id" value="{{ old('asset_id', $video->asset_id) }}"
                                placeholder="ID de l'asset"
                                class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                            @if($video->file)
                                <p class="text-xs text-green-600 mt-1 flex items-center">
                                    <span class="material-symbols-outlined text-xs mr-1">check_circle</span>
                                    {{ $video->file->filename ?? 'Asset #' . $video->asset_id }}
                                </p>
                            @endif
                        </div>

                        <div>
                            <label for="duration_seconds" class="block text-sm font-medium text-gray-700 mb-1">Durée (secondes)</label>
                            <input type="number" name="duration_seconds" id="duration_seconds" value="{{ old('duration_seconds', $video->duration_seconds) }}"
                                placeholder="120" min="0"
                                class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne latérale -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Publication -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Publication</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                        <select name="status" id="status" required
                            class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                            <option value="draft" {{ old('status', $video->status) === 'draft' ? 'selected' : '' }}>Brouillon</option>
                            <option value="published" {{ old('status', $video->status) === 'published' ? 'selected' : '' }}>Publié</option>
                        </select>
                    </div>

                    <div>
                        <label for="published_at" class="block text-sm font-medium text-gray-700 mb-1">Date de publication</label>
                        <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at', $video->published_at ? $video->published_at->format('Y-m-d\TH:i') : '') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                        <p class="mt-1 text-xs text-gray-500">Laissez vide pour publier immédiatement</p>
                    </div>

                    <div class="pt-4 border-t mt-4 flex gap-2">
                        <button type="submit" class="flex-1 bg-red-600 text-white py-2.5 px-4 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all shadow-md font-medium flex justify-center items-center">
                            <span class="material-symbols-outlined mr-2 text-sm">save</span>
                            Mettre à jour
                        </button>
                    </div>
                </div>
            </div>

            <!-- Miniature -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Miniature</h3>
                
                <div class="space-y-4">
                    @if($video->thumbnail_url)
                    <div class="relative rounded-lg overflow-hidden bg-gray-100 aspect-video">
                        <img src="{{ $video->thumbnail_url }}" alt="Miniature actuelle" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-20 transition-all flex items-center justify-center">
                            <span class="text-white opacity-0 hover:opacity-100 transition-opacity">Miniature actuelle</span>
                        </div>
                    </div>
                    @endif
                    
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 transition-colors cursor-pointer relative">
                        <input type="file" name="thumbnail" id="thumbnail" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*">
                        <div class="space-y-2">
                            <span class="material-symbols-outlined text-4xl text-gray-400">video_library</span>
                            <p class="text-sm text-gray-500">Cliquez ou glissez une nouvelle image</p>
                            <p class="text-xs text-gray-400">JPG, PNG jusqu'à 2MB</p>
                        </div>
                    </div>

                    <div>
                        <label for="thumbnail_asset_id" class="block text-sm font-medium text-gray-700 mb-1">Ou Asset ID miniature</label>
                        <input type="number" name="thumbnail_asset_id" id="thumbnail_asset_id" value="{{ old('thumbnail_asset_id', $video->thumbnail_asset_id) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                        @if($video->thumbnail)
                            <p class="text-xs text-green-600 mt-1 flex items-center">
                                <span class="material-symbols-outlined text-xs mr-1">check_circle</span>
                                {{ $video->thumbnail->filename ?? 'Asset #' . $video->thumbnail_asset_id }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Catégories et Tags -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Classement</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="categories" class="block text-sm font-medium text-gray-700 mb-1">Catégories</label>
                        <select name="categories[]" id="categories" multiple size="4"
                            class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm text-sm">
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', $video->categories->pluck('id')->toArray())) ? 'selected' : '' }} class="p-1">
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Maintenez Ctrl pour sélectionner plusieurs.</p>
                    </div>

                    <div>
                        <label for="tags" class="block text-sm font-medium text-gray-700 mb-1">Tags</label>
                        <select name="tags[]" id="tags" multiple size="4"
                            class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm text-sm">
                            @foreach($tags as $tag)
                            <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', $video->tags->pluck('id')->toArray())) ? 'selected' : '' }} class="p-1">
                                {{ $tag->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Zone danger -->
            <div class="bg-white rounded-xl shadow-sm border border-red-100 p-6">
                <h3 class="text-lg font-semibold text-red-800 mb-4 border-b border-red-200 pb-2">Zone dangereuse</h3>
                <button type="button" onclick="if(confirm('Supprimer cette vidéo ?')) { document.getElementById('delete-form').submit(); }"
                    class="w-full bg-red-50 text-red-700 py-2.5 px-4 rounded-lg hover:bg-red-100 transition-all font-medium flex justify-center items-center border border-red-200">
                    <span class="material-symbols-outlined mr-2 text-sm">delete</span>
                    Supprimer la vidéo
                </button>
            </div>
        </div>
    </div>
</form>

<!-- Formulaire de suppression caché -->
<form id="delete-form" action="{{ route('admin.videos.destroy', $video->id) }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>
@endsection