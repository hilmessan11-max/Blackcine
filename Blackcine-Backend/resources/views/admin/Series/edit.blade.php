@extends('layouts.app')

@section('title', 'Modifier Série - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Modifier: {{ $serie->title }}</h1>
        <p class="text-gray-600 mt-2">Mettez à jour les informations de la série</p>
    </div>
    <a href="{{ route('admin.series.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
        <span class="material-symbols-outlined mr-1">arrow_back</span>
        Retour aux séries
    </a>
</div>

<form method="POST" action="{{ route('admin.series.update', $serie) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonne principale -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations de base -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informations de la série</h3>
                
                <div class="space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Titre <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title', $serie->title) }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm @error('title') border-red-500 @enderror">
                        @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $serie->slug) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                    </div>

                    <div>
                        <label for="overview" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="overview" id="overview" rows="4"
                            class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">{{ old('overview', $serie->overview) }}</textarea>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label for="first_air_date" class="block text-sm font-medium text-gray-700 mb-1">Première diffusion</label>
                            <input type="date" name="first_air_date" id="first_air_date" value="{{ old('first_air_date', $serie->first_air_date ? $serie->first_air_date->format('Y-m-d') : '') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                        </div>
                        <div>
                            <label for="seasons" class="block text-sm font-medium text-gray-700 mb-1">Saisons</label>
                            <input type="number" name="seasons" id="seasons" value="{{ old('seasons', $serie->seasons) }}" min="1"
                                class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                        </div>
                        <div>
                            <label for="episodes" class="block text-sm font-medium text-gray-700 mb-1">Épisodes</label>
                            <input type="number" name="episodes" id="episodes" value="{{ old('episodes', $serie->episodes) }}" min="1"
                                class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Genres -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Genres</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach(['Drame', 'Comédie', 'Thriller', 'Science-Fiction', 'Fantastique', 'Policier', 'Action', 'Aventure', 'Animation', 'Romance', 'Horreur'] as $genre)
                        <label class="flex items-center p-2 hover:bg-gray-50 rounded cursor-pointer">
                            <input type="checkbox" name="genres[]" value="{{ $genre }}" class="w-4 h-4 text-red-600 rounded focus:ring-red-500"
                                @if(is_array(old('genres', $serie->genres)) && in_array($genre, old('genres', $serie->genres))) checked @endif>
                            <span class="ml-2 text-sm text-gray-700">{{ $genre }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Colonne latérale -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Statut -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Statut</h3>
                <select name="status" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                    <option value="ongoing" {{ old('status', $serie->status) == 'ongoing' ? 'selected' : '' }}>En cours</option>
                    <option value="completed" {{ old('status', $serie->status) == 'completed' ? 'selected' : '' }}>Terminée</option>
                    <option value="cancelled" {{ old('status', $serie->status) == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                </select>
            </div>

            <!-- Catégorie -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Catégorie</h3>
                <select name="category" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                    <option value="tous" {{ old('category', $serie->category) == 'tous' ? 'selected' : '' }}>Tous</option>
                    <option value="nouveaute" {{ old('category', $serie->category) == 'nouveaute' ? 'selected' : '' }}>Nouveauté</option>
                    <option value="populaire" {{ old('category', $serie->category) == 'populaire' ? 'selected' : '' }}>Populaire</option>
                    <option value="a_venir" {{ old('category', $serie->category) == 'a_venir' ? 'selected' : '' }}>À venir</option>
                </select>
            </div>

            <!-- Image -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Affiche</h3>
                
                @if($serie->poster_url)
                <div class="mb-4 relative rounded-lg overflow-hidden bg-gray-100">
                    <img src="{{ $serie->poster_url }}" alt="{{ $serie->title }}" class="w-full object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-20 transition-all"></div>
                </div>
                @endif
                
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 transition-colors cursor-pointer relative">
                    <input type="file" name="poster" id="poster" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*">
                    <div class="space-y-2">
                        <span class="material-symbols-outlined text-4xl text-gray-400">tv</span>
                        <p class="text-sm text-gray-500">Changer l'affiche</p>
                        <p class="text-xs text-gray-400">JPG, PNG jusqu'à 5MB</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="space-y-3">
                    <button type="submit" class="w-full bg-red-600 text-white py-2.5 px-4 rounded-lg hover:bg-red-700 transition-all shadow-md font-medium flex justify-center items-center">
                        <span class="material-symbols-outlined mr-2 text-sm">save</span>
                        Mettre à jour
                    </button>
                    <a href="{{ route('admin.series.index') }}" class="block w-full bg-gray-100 text-gray-700 py-2.5 px-4 rounded-lg hover:bg-gray-200 transition-all font-medium text-center">
                        Annuler
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection