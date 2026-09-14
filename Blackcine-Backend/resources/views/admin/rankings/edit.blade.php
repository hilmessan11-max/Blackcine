@extends('layouts.app')

@section('title', 'Modifier Classement - ' . $ranking->name)

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Modifier le Classement</h1>
    <p class="text-gray-600 mt-2">{{ $ranking->name }}</p>
</div>

<form action="{{ route('admin.rankings.update', $ranking->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Informations du Classement</h2>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nom du classement *</label>
                    <input type="text" name="name" required value="{{ old('name', $ranking->name) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $ranking->description) }}</textarea>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Critères de Classement</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                        <select name="type" required id="rankingType" onchange="toggleTypeFields()"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="general" {{ old('type', $ranking->type) === 'general' ? 'selected' : '' }}>Général</option>
                            <option value="genre" {{ old('type', $ranking->type) === 'genre' ? 'selected' : '' }}>Par Genre</option>
                            <option value="country" {{ old('type', $ranking->type) === 'country' ? 'selected' : '' }}>Par Pays</option>
                            <option value="year" {{ old('type', $ranking->type) === 'year' ? 'selected' : '' }}>Par Année</option>
                            <option value="custom" {{ old('type', $ranking->type) === 'custom' ? 'selected' : '' }}>Personnalisé</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Période *</label>
                        <select name="period" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="all_time" {{ old('period', $ranking->period) === 'all_time' ? 'selected' : '' }}>Tout temps</option>
                            <option value="year" {{ old('period', $ranking->period) === 'year' ? 'selected' : '' }}>Année en cours</option>
                            <option value="month" {{ old('period', $ranking->period) === 'month' ? 'selected' : '' }}>Mois en cours</option>
                            <option value="week" {{ old('period', $ranking->period) === 'week' ? 'selected' : '' }}>Semaine en cours</option>
                        </select>
                    </div>

                    <div id="genreField" class="{{ old('type', $ranking->type) === 'genre' ? '' : 'hidden' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Genre</label>
                        <input type="text" name="genre" value="{{ old('genre', $ranking->genre) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div id="countryField" class="{{ old('type', $ranking->type) === 'country' ? '' : 'hidden' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pays</label>
                        <input type="text" name="country" value="{{ old('country', $ranking->country) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div id="yearField" class="{{ old('type', $ranking->type) === 'year' ? '' : 'hidden' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Année</label>
                        <input type="number" name="year" value="{{ old('year', $ranking->year) }}" min="1900" max="2100"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Paramètres</h3>
                
                <div class="mb-4">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $ranking->is_active) ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Classement actif</span>
                    </label>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ordre d'affichage</label>
                    <input type="number" name="display_order" value="{{ old('display_order', $ranking->display_order) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 font-semibold mb-3">
                    ✓ Mettre à Jour
                </button>
                <a href="{{ route('admin.rankings.show', $ranking->id) }}" class="block w-full text-center bg-gray-300 text-gray-800 px-4 py-3 rounded-lg hover:bg-gray-400 font-semibold">
                    Annuler
                </a>
            </div>
        </div>
    </div>
</form>

<script>
function toggleTypeFields() {
    const type = document.getElementById('rankingType').value;
    document.getElementById('genreField').classList.toggle('hidden', type !== 'genre');
    document.getElementById('countryField').classList.toggle('hidden', type !== 'country');
    document.getElementById('yearField').classList.toggle('hidden', type !== 'year');
}
</script>
@endsection
