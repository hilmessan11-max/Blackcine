@extends('layouts.app')

@section('title', 'Créer un slide - BlackCine Admin')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Créer un slide</h1>
    <p class="text-gray-600 mt-2">Ajouter un nouveau slide pour la page d'accueil</p>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.editorial.slides.store') }}" method="POST" id="slideForm">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Titre *</label>
                <input type="text" name="title" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie *</label>
                <select name="category" id="category" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Sélectionner une catégorie</option>
                    <option value="film">Film</option>
                    <option value="series">Série</option>
                    <option value="general">Général</option>
                </select>
            </div>

            <div class="md:col-span-2" id="titleSelectContainer" style="display: none;">
                <label class="block text-sm font-medium text-gray-700 mb-2" id="titleLabel">Film/Série lié *</label>
                <select name="title_id" id="title_id" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Sélectionner un film/série</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Image *</label>
                <select name="asset_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Sélectionner une image</option>
                    @foreach($assets as $asset)
                        <option value="{{ $asset->id }}">{{ basename($asset->path) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Label CTA</label>
                <input type="text" name="cta_label" placeholder="Ex: Voir plus" class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">URL CTA</label>
                <input type="url" name="cta_url" placeholder="https://..." class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Ordre d'affichage</label>
                <input type="number" name="display_order" value="0" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" checked class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                <label for="is_active" class="ml-2 text-sm text-gray-700">Actif</label>
            </div>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.editorial.slides.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                Annuler
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Créer le slide
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category');
    const titleSelectContainer = document.getElementById('titleSelectContainer');
    const titleSelect = document.getElementById('title_id');
    const titleLabel = document.getElementById('titleLabel');
    
    const films = @json($films);
    const series = @json($series);

    categorySelect.addEventListener('change', function() {
        const category = this.value;
        titleSelect.innerHTML = '<option value="">Sélectionner un film/série</option>';
        
        if (category === 'film') {
            titleSelectContainer.style.display = 'block';
            titleLabel.textContent = 'Film lié *';
            films.forEach(film => {
                const option = document.createElement('option');
                option.value = film.id;
                option.textContent = film.name;
                titleSelect.appendChild(option);
            });
            titleSelect.required = true;
        } else if (category === 'series') {
            titleSelectContainer.style.display = 'block';
            titleLabel.textContent = 'Série liée *';
            series.forEach(serie => {
                const option = document.createElement('option');
                option.value = serie.id;
                option.textContent = serie.name;
                titleSelect.appendChild(option);
            });
            titleSelect.required = true;
        } else {
            titleSelectContainer.style.display = 'none';
            titleSelect.required = false;
            titleSelect.value = '';
        }
    });
});
</script>
@endsection

