@extends('layouts.app')

@section('title', 'Créer un article - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Créer un article</h1>
        <p class="text-gray-600 mt-2">Rédigez et publiez un nouveau contenu</p>
    </div>
    <a href="{{ route('admin.articles.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
        <span class="material-symbols-outlined mr-1">arrow_back</span>
        Retour aux articles
    </a>
</div>

<form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonne principale (Contenu) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations de base -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Contenu de l'article</h3>
                
                <div class="space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Titre de l'article <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required placeholder="Ex: Les meilleures sorties cinéma de l'été"
                            class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-1">Extrait (Accroche)</label>
                        <textarea name="excerpt" id="excerpt" rows="3" placeholder="Un bref résumé pour donner envie de lire..."
                            class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">{{ old('excerpt') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Sera affiché dans les listes et les résultats de recherche.</p>
                    </div>

                    <div>
                        <label for="body" class="block text-sm font-medium text-gray-700 mb-1">Contenu complet <span class="text-red-500">*</span></label>
                        <textarea name="body" id="body" rows="15" required placeholder="Rédigez votre article ici..."
                            class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm font-mono text-sm">{{ old('body') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- SEO (Optionnel - Placeholder visuel) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h3 class="text-lg font-semibold text-gray-800">Optimisation SEO</h3>
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">Optionnel</span>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Méta-titre</label>
                        <input type="text" name="meta_title" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Méta-description</label>
                        <textarea name="meta_description" rows="2" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne latérale (Métadonnées) -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Publication -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Publication</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                        <select name="status" id="status" required
                            class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                            <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Brouillon</option>
                            <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Publié</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date de publication</label>
                        <input type="date" name="published_at" value="{{ date('Y-m-d') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                    </div>

                    <div class="pt-4 border-t mt-4">
                        <button type="submit" class="w-full bg-red-600 text-white py-2.5 px-4 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all shadow-md font-medium flex justify-center items-center">
                            <span class="material-symbols-outlined mr-2 text-sm">save</span>
                            Enregistrer l'article
                        </button>
                    </div>
                </div>
            </div>

            <!-- Image à la une -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Image à la une</h3>
                
                <div class="space-y-4">
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 transition-colors cursor-pointer relative">
                        <input type="file" name="image" id="image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*">
                        <div class="space-y-2">
                            <span class="material-symbols-outlined text-4xl text-gray-400">add_photo_alternate</span>
                            <p class="text-sm text-gray-500">Cliquez ou glissez une image ici</p>
                            <p class="text-xs text-gray-400">JPG, PNG jusqu'à 2MB</p>
                        </div>
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
                            <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'selected' : '' }} class="p-1">
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
                            <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }} class="p-1">
                                {{ $tag->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
