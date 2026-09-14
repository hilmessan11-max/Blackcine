@extends('layouts.app')

@section('title', 'Modifier Film - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Modifier: {{ $film->title }}</h1>
        <p class="text-gray-600 mt-2">Mettez à jour les informations du film</p>
    </div>
    <a href="{{ route('admin.films.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
        <span class="material-symbols-outlined mr-1">arrow_back</span>
        Retour aux films
    </a>
</div>

<form method="POST" action="{{ route('admin.films.update', $film) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonne principale -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations de base -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informations du film</h3>
                
                <div class="space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Titre <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title', $film->title) }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm @error('title') border-red-500 @enderror">
                        @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $film->slug) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                    </div>

                    <div>
                        <label for="overview" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="overview" id="overview" rows="4"
                            class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">{{ old('overview', $film->overview) }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Pays</label>
                            <input type="text" name="country" id="country" value="{{ old('country', $film->country) }}"
                                class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                        </div>
                        <div>
                            <label for="language" class="block text-sm font-medium text-gray-700 mb-1">Langue</label>
                            <input type="text" name="language" id="language" value="{{ old('language', $film->language) }}"
                                class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Année</label>
                            <input type="number" name="year" id="year" value="{{ old('year', $film->year) }}" min="1900" max="2100"
                                class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                        </div>
                        <div>
                            <label for="duration_minutes" class="block text-sm font-medium text-gray-700 mb-1">Durée (min)</label>
                            <input type="number" name="duration_minutes" id="duration_minutes" value="{{ old('duration_minutes', $film->duration_minutes) }}" min="0"
                                class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Genres -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Genres</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach(['Action', 'Aventure', 'Animation', 'Comédie', 'Crime', 'Drame', 'Fantastique', 'Horreur', 'Romance', 'Thriller', 'Science-Fiction'] as $genre)
                        <label class="flex items-center p-2 hover:bg-gray-50 rounded cursor-pointer">
                            <input type="checkbox" name="genres[]" value="{{ $genre }}" class="w-4 h-4 text-red-600 rounded focus:ring-red-500"
                                @if(is_array(old('genres', $film->genres)) && in_array($genre, old('genres', $film->genres))) checked @endif>
                            <span class="ml-2 text-sm text-gray-700">{{ $genre }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Colonne latérale -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Catégorie -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Catégorie</h3>
                <select name="category" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                    <option value="tous" {{ old('category', $film->category) == 'tous' ? 'selected' : '' }}>Tous</option>
                    <option value="nouveaute" {{ old('category', $film->category) == 'nouveaute' ? 'selected' : '' }}>Nouveauté</option>
                    <option value="a_l_affiche" {{ old('category', $film->category) == 'a_l_affiche' ? 'selected' : '' }}>À l'Affiche</option>
                    <option value="a_venir" {{ old('category', $film->category) == 'a_venir' ? 'selected' : '' }}>À venir</option>
                    <option value="box_office" {{ old('category', $film->category) == 'box_office' ? 'selected' : '' }}>Box Office</option>
                </select>
            </div>

            <!-- Image -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Affiche</h3>
                
                @if($film->poster_url)
                <div class="mb-4 relative rounded-lg overflow-hidden bg-gray-100">
                    <img src="{{ $film->poster_url }}" alt="{{ $film->title }}" class="w-full object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-20 transition-all"></div>
                </div>
                @endif
                
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 transition-colors cursor-pointer relative">
                    <input type="file" name="poster" id="poster" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*">
                    <div class="space-y-2">
                        <span class="material-symbols-outlined text-4xl text-gray-400">photo_library</span>
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
                    <a href="{{ route('admin.films.index') }}" class="block w-full bg-gray-100 text-gray-700 py-2.5 px-4 rounded-lg hover:bg-gray-200 transition-all font-medium text-center">
                        Annuler
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection