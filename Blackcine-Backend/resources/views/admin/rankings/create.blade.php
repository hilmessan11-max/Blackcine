@extends('layouts.app')

@section('title', 'Nouveau Classement')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Nouveau Classement</h1>
    <p class="text-gray-600 mt-2">Créez un nouveau classement de titres</p>
</div>

<form action="{{ route('admin.rankings.store') }}" method="POST">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations de base -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Informations du Classement</h2>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nom du classement *</label>
                    <input type="text" name="name" required value="{{ old('name') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                        placeholder="Top 100 Films Africains">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description (optionnel)</label>
                    <textarea name="description" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Description du classement...">{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- Critères -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Critères de Classement</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                        <select name="type" required id="rankingType" onchange="toggleTypeFields()"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="general" {{ old('type') === 'general' ? 'selected' : '' }}>Général (tous les titres)</option>
                            <option value="genre" {{ old('type') === 'genre' ? 'selected' : '' }}>Par Genre</option>
                            <option value="country" {{ old('type') === 'country' ? 'selected' : '' }}>Par Pays</option>
                            <option value="year" {{ old('type') === 'year' ? 'selected' : '' }}>Par Année</option>
                            <option value="custom" {{ old('type') === 'custom' ? 'selected' : '' }}>Personnalisé</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Période *</label>
                        <select name="period" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="all_time" {{ old('period') === 'all_time' ? 'selected' : '' }}>Tout temps</option>
                            <option value="year" {{ old('period') === 'year' ? 'selected' : '' }}>Année en cours</option>
                            <option value="month" {{ old('period') === 'month' ? 'selected' : '' }}>Mois en cours</option>
                            <option value="week" {{ old('period') === 'week' ? 'selected' : '' }}>Semaine en cours</option>
                        </select>
                    </div>

                    <div id="genreField" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Genre</label>
                        <input type="text" name="genre" value="{{ old('genre') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Action, Drame, Comédie...">
                    </div>

                    <div id="countryField" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pays</label>
                        <input type="text" name="country" value="{{ old('country') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Sénégal, Nigeria...">
                    </div>

                    <div id="yearField" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Année</label>
                        <input type="number" name="year" value="{{ old('year', date('Y')) }}" min="1900" max="2100"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Paramètres</h3>
                
                <div class="mb-4">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Classement actif</span>
                    </label>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ordre d'affichage</label>
                    <input type="number" name="display_order" value="{{ old('display_order', 0) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 font-semibold mb-3">
                    ✓ Créer le Classement
                </button>
                <a href="{{ route('admin.rankings.index') }}" class="block w-full text-center bg-gray-300 text-gray-800 px-4 py-3 rounded-lg hover:bg-gray-400 font-semibold">
                    Annuler
                </a>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
                <h4 class="font-semibold text-blue-900 mb-2">💡 Info</h4>
                <p class="text-sm text-blue-800">Après création, vous pourrez ajouter des titres au classement et gérer leur position.</p>
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
// Initialize on page load
toggleTypeFields();
</script>
@endsection
