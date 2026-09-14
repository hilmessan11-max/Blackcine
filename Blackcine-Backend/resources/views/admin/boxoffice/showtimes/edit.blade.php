@extends('layouts.app')

@section('title', 'Modifier la Séance - BlackCine Admin')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Modifier la Séance</h1>
            <p class="text-gray-600 mt-2">{{ $showtime->title->title ?? 'Séance' }}</p>
        </div>
        <a href="{{ route('admin.boxoffice.showtimes.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
            ← Retour
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.boxoffice.showtimes.update', $showtime->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Titre -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Film / Série *</label>
                <select name="title_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Sélectionner...</option>
                    @foreach($titles as $title)
                        <option value="{{ $title->id }}" {{ old('title_id', $showtime->title_id) == $title->id ? 'selected' : '' }}>
                            {{ $title->title }} ({{ $title->release_year }})
                        </option>
                    @endforeach
                </select>
                @error('title_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Cinéma -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cinéma *</label>
                <select name="cinema_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md" id="cinema-select">
                    <option value="">Sélectionner...</option>
                    @foreach($cinemas as $cinema)
                        <option value="{{ $cinema->id }}" {{ old('cinema_id', $showtime->cinema_id) == $cinema->id ? 'selected' : '' }}>
                            {{ $cinema->name }} - {{ $cinema->city }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Salle -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Salle</label>
                <input type="text" name="room" value="{{ old('room', $showtime->room) }}" 
                    placeholder="Salle 1" class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>

            <!-- Date et heure -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date de la séance *</label>
                <input type="date" name="show_date" value="{{ old('show_date', $showtime->show_date?->format('Y-m-d')) }}" required 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Heure de début *</label>
                <input type="time" name="show_time" value="{{ old('show_time', $showtime->show_time?->format('H:i')) }}" required 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>

            <!-- Prix et places -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Prix (centimes) *</label>
                <input type="number" name="price_cents" value="{{ old('price_cents', $showtime->price_cents) }}" required min="0" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md">
                <p class="text-xs text-gray-500 mt-1">1000 = 10,00 €</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Places disponibles *</label>
                <input type="number" name="available_seats" value="{{ old('available_seats', $showtime->available_seats) }}" required min="0" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>

            <!-- Langues et formats -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Langue</label>
                <select name="language" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Sélectionner...</option>
                    <option value="fr" {{ old('language', $showtime->language) == 'fr' ? 'selected' : '' }}>Français</option>
                    <option value="en" {{ old('language', $showtime->language) == 'en' ? 'selected' : '' }}>Anglais</option>
                    <option value="ar" {{ old('language', $showtime->language) == 'ar' ? 'selected' : '' }}>Arabe</option>
                    <option value="other" {{ old('language', $showtime->language) == 'other' ? 'selected' : '' }}>Autre</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sous-titres</label>
                <select name="subtitles" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Aucun</option>
                    <option value="fr" {{ old('subtitles', $showtime->subtitles) == 'fr' ? 'selected' : '' }}>Français</option>
                    <option value="en" {{ old('subtitles', $showtime->subtitles) == 'en' ? 'selected' : '' }}>Anglais</option>
                    <option value="ar" {{ old('subtitles', $showtime->subtitles) == 'ar' ? 'selected' : '' }}>Arabe</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Version</label>
                <select name="version" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Sélectionner...</option>
                    <option value="vf" {{ old('version', $showtime->version) == 'vf' ? 'selected' : '' }}>VF (Version Française)</option>
                    <option value="vo" {{ old('version', $showtime->version) == 'vo' ? 'selected' : '' }}>VO (Version Originale)</option>
                    <option value="vost" {{ old('version', $showtime->version) == 'vost' ? 'selected' : '' }}>VOST (VO Sous-titrée)</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Format</label>
                <select name="format" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Standard</option>
                    <option value="3d" {{ old('format', $showtime->format) == '3d' ? 'selected' : '' }}>3D</option>
                    <option value="imax" {{ old('format', $showtime->format) == 'imax' ? 'selected' : '' }}>IMAX</option>
                    <option value="4dx" {{ old('format', $showtime->format) == '4dx' ? 'selected' : '' }}>4DX</option>
                    <option value="dolby_atmos" {{ old('format', $showtime->format) == 'dolby_atmos' ? 'selected' : '' }}>Dolby Atmos</option>
                </select>
            </div>

            <!-- Statut -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut *</label>
                <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="scheduled" {{ old('status', $showtime->status) == 'scheduled' ? 'selected' : '' }}>Programmée</option>
                    <option value="on_sale" {{ old('status', $showtime->status) == 'on_sale' ? 'selected' : '' }}>En vente</option>
                    <option value="sold_out" {{ old('status', $showtime->status) == 'sold_out' ? 'selected' : '' }}>Complet</option>
                    <option value="cancelled" {{ old('status', $showtime->status) == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                    <option value="completed" {{ old('status', $showtime->status) == 'completed' ? 'selected' : '' }}>Terminée</option>
                </select>
            </div>
        </div>

        <div class="mt-6 flex justify-between">
            <form action="{{ route('admin.boxoffice.showtimes.destroy', $showtime->id) }}" method="POST" 
                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette séance ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Supprimer
                </button>
            </form>

            <div class="flex space-x-3">
                <a href="{{ route('admin.boxoffice.showtimes.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Annuler
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Mettre à jour
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
