@extends('layouts.app')

@section('title', 'Modifier un slide - BlackCine Admin')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Modifier un slide</h1>
    <p class="text-gray-600 mt-2">Modifier les informations du slide</p>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.editorial.slides.update', $slide->id) }}" method="POST" id="slideForm">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Titre *</label>
                <input type="text" name="title" value="{{ old('title', $slide->title) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie *</label>
                <select name="category" id="category" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Sélectionner une catégorie</option>
                    <option value="film" {{ old('category', $slide->category) === 'film' ? 'selected' : '' }}>Film</option>
                    <option value="series" {{ old('category', $slide->category) === 'series' ? 'selected' : '' }}>Série</option>
                    <option value="general" {{ old('category', $slide->category) === 'general' ? 'selected' : '' }}>Général</option>
                </select>
            </div>

            <div class="md:col-span-2" id="titleSelectContainer" style="display: {{ in_array(old('category', $slide->category), ['film', 'series']) ? 'block' : 'none' }};">
                <label class="block text-sm font-medium text-gray-700 mb-2" id="titleLabel">
                    {{ old('category', $slide->category) === 'film' ? 'Film lié *' : (old('category', $slide->category) === 'series' ? 'Série liée *' : 'Film/Série lié *') }}
                </label>
                <select name="title_id" id="title_id" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Sélectionner un film/série</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Image *</label>
                <select name="asset_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Sélectionner une image</option>
                    @foreach($assets as $asset)
                        <option value="{{ $asset->id }}" {{ old('asset_id', $slide->asset_id) == $asset->id ? 'selected' : '' }}>
                            {{ basename($asset->path) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Label CTA</label>
                <input type="text" name="cta_label" value="{{ old('cta_label', $slide->cta_label) }}" placeholder="Ex: Voir plus" class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">URL CTA</label>
                <input type="url" name="cta_url" value="{{ old('cta_url', $slide->cta_url) }}" placeholder="https://..." class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Ordre d'affichage</label>
                <input type="number" name="display_order" value="{{ old('display_order', $slide->display_order ?? 0) }}" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $slide->is_active) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                <label for="is_active" class="ml-2 text-sm text-gray-700">Actif</label>
            </div>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.editorial.slides.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                Annuler
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Mettre à jour
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
    const currentTitleId = {{ $slide->title_id ?? 'null' }};
    const currentCategory = '{{ old("category", $slide->category) }}';

    function populateTitles(category) {
        titleSelect.innerHTML = '<option value="">Sélectionner un film/série</option>';
        
        if (category === 'film') {
            films.forEach(film => {
                const option = document.createElement('option');
                option.value = film.id;
                option.textContent = film.name;
                if (currentTitleId && film.id == currentTitleId) {
                    option.selected = true;
                }
                titleSelect.appendChild(option);
            });
        } else if (category === 'series') {
            series.forEach(serie => {
                const option = document.createElement('option');
                option.value = serie.id;
                option.textContent = serie.name;
                if (currentTitleId && serie.id == currentTitleId) {
                    option.selected = true;
                }
                titleSelect.appendChild(option);
            });
        }
    }

    // Initialiser avec la catégorie actuelle
    if (currentCategory === 'film' || currentCategory === 'series') {
        populateTitles(currentCategory);
    }

    categorySelect.addEventListener('change', function() {
        const category = this.value;
        
        if (category === 'film') {
            titleSelectContainer.style.display = 'block';
            titleLabel.textContent = 'Film lié *';
            populateTitles('film');
            titleSelect.required = true;
        } else if (category === 'series') {
            titleSelectContainer.style.display = 'block';
            titleLabel.textContent = 'Série liée *';
            populateTitles('series');
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

