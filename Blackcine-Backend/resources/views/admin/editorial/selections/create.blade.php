@extends('layouts.app')

@section('title', 'Créer une sélection - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Créer une sélection éditoriale</h1>
        <p class="text-gray-600 mt-2">Ajoutez un nouveau coup de cœur ou carrousel</p>
    </div>
    <a href="{{ route('admin.editorial.selections.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
        <span class="material-symbols-outlined mr-1">arrow_back</span>
        Retour aux sélections
    </a>
</div>

<form method="POST" action="{{ route('admin.editorial.selections.store') }}" enctype="multipart/form-data">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonne principale -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations de base -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informations de la sélection</h3>
                
                <div class="space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Titre <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                            placeholder="Ex: Nos coups de cœur du mois"
                            class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm @error('title') border-red-500 @enderror">
                        @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="description" rows="4" placeholder="Décrivez cette sélection..."
                            class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Contenu de la sélection -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Titres associés</h3>
                <p class="text-sm text-gray-500 mb-4">Sélectionnez les films/séries à inclure dans cette sélection</p>
                
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                    <span class="material-symbols-outlined text-5xl text-gray-300 mb-3 block">add_circle</span>
                    <p class="text-gray-500">Les titres pourront être ajoutés après la création</p>
                </div>
            </div>
        </div>

        <!-- Colonne latérale -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Type -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Type de sélection</h3>
                
                <div class="space-y-3">
                    <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                        <input type="radio" name="type" value="coup_de_coeur" class="h-4 w-4 text-red-600 focus:ring-red-500" {{ old('type') == 'coup_de_coeur' ? 'checked' : '' }}>
                        <div class="ml-3 flex items-center">
                            <span class="material-symbols-outlined text-red-600 mr-2">favorite</span>
                            <span class="text-sm font-medium text-gray-700">Coup de cœur</span>
                        </div>
                    </label>

                    <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                        <input type="radio" name="type" value="carousel" class="h-4 w-4 text-red-600 focus:ring-red-500" {{ old('type') == 'carousel' ? 'checked' : '' }}>
                        <div class="ml-3 flex items-center">
                            <span class="material-symbols-outlined text-purple-600 mr-2">view_carousel</span>
                            <span class="text-sm font-medium text-gray-700">Carousel</span>
                        </div>
                    </label>

                    <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                        <input type="radio" name="type" value="selection" class="h-4 w-4 text-red-600 focus:ring-red-500" {{ old('type', 'selection') == 'selection' ? 'checked' : '' }}>
                        <div class="ml-3 flex items-center">
                            <span class="material-symbols-outlined text-green-600 mr-2">stars</span>
                            <span class="text-sm font-medium text-gray-700">À ne pas manquer</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Paramètres -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Paramètres</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="display_order" class="block text-sm font-medium text-gray-700 mb-1">Ordre d'affichage</label>
                        <input type="number" name="display_order" id="display_order" value="{{ old('display_order', 0) }}" min="0"
                            class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                        <p class="text-xs text-gray-500 mt-1">0 = Priorité maximale</p>
                    </div>

                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <input type="checkbox" name="is_active" id="is_active" value="1" checked class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                        <label for="is_active" class="ml-2 text-sm text-gray-700 font-medium">Sélection active</label>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="space-y-3">
                    <button type="submit" class="w-full bg-red-600 text-white py-2.5 px-4 rounded-lg hover:bg-red-700 transition-all shadow-md font-medium flex justify-center items-center">
                        <span class="material-symbols-outlined mr-2 text-sm">save</span>
                        Créer la sélection
                    </button>
                    <a href="{{ route('admin.editorial.selections.index') }}" class="block w-full bg-gray-100 text-gray-700 py-2.5 px-4 rounded-lg hover:bg-gray-200 transition-all font-medium text-center">
                        Annuler
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
