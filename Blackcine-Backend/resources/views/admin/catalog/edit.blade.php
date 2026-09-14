@extends('layouts.app')

@section('title', 'Modifier le titre - BlackCine Admin')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Modifier le titre</h1>
    <p class="text-gray-600 mt-2">Modifiez les informations du titre</p>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.catalog.update', $title->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Titre *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $title->name) }}" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="type">Type *</label>
                <select name="type" id="type" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @foreach($types as $key => $label)
                        <option value="{{ $key }}" {{ old('type', $title->type) === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="status">Statut *</label>
                <select name="status" id="status" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="draft" {{ old('status', $title->status) === 'draft' ? 'selected' : '' }}>Brouillon</option>
                    <option value="published" {{ old('status', $title->status) === 'published' ? 'selected' : '' }}>Publié</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="synopsis">Synopsis</label>
                <textarea name="synopsis" id="synopsis" rows="4"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('synopsis', $title->synopsis) }}</textarea>
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="release_date">Date de sortie</label>
                <input type="date" name="release_date" id="release_date" value="{{ old('release_date', $title->release_date?->format('Y-m-d')) }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="origin_country">Pays d'origine</label>
                <input type="text" name="origin_country" id="origin_country" value="{{ old('origin_country', $title->origin_country) }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="original_language">Langue originale</label>
                <input type="text" name="original_language" id="original_language" value="{{ old('original_language', $title->original_language) }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="runtime_minutes">Durée (minutes)</label>
                <input type="number" name="runtime_minutes" id="runtime_minutes" value="{{ old('runtime_minutes', $title->runtime_minutes) }}" min="0"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="certification">Classification</label>
                <input type="text" name="certification" id="certification" value="{{ old('certification', $title->certification) }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="md:col-span-2">
                <label class="flex items-center">
                    <input type="checkbox" name="is_paid" value="1" {{ old('is_paid', $title->is_paid) ? 'checked' : '' }} class="mr-2">
                    <span class="text-gray-700 text-sm">Contenu payant</span>
                </label>
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="price_cents">Prix (centimes)</label>
                <input type="number" name="price_cents" id="price_cents" value="{{ old('price_cents', $title->price_cents) }}" min="0"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="md:col-span-2">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="genres">Genres</label>
                <select name="genres[]" id="genres" multiple size="5"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @foreach($genres as $genre)
                        <option value="{{ $genre->id }}" {{ $title->genres->contains($genre->id) ? 'selected' : '' }}>
                            {{ $genre->name }}
                        </option>
                    @endforeach
                </select>
                <p class="text-gray-500 text-xs mt-1">Maintenez Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs genres</p>
            </div>
        </div>

        <div class="flex items-center justify-between mt-6">
            <a href="{{ route('admin.catalog.index') }}" class="text-gray-600 hover:text-gray-800">Annuler</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection

