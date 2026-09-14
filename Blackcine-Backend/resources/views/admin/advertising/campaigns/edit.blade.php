@extends('layouts.app')

@section('title', 'Modifier la Campagne')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-purple-50">
    <!-- Header avec dégradé -->
    <div class="bg-gradient-to-r from-purple-600 via-blue-600 to-indigo-600 shadow-lg">
        <div class="container mx-auto px-6 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Modifier la Campagne</h1>
                    <p class="text-blue-100">{{ $campaign->name }}</p>
                </div>
                <a href="{{ route('admin.advertising.campaigns.index') }}" class="bg-white/20 hover:bg-white/30 text-white px-6 py-2 rounded-lg font-semibold transition backdrop-blur-sm">
                    ← Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Formulaire  -->
    <div class="container mx-auto px-6 py-8">
        <form action="{{ route('admin.advertising.campaigns.update', $campaign) }}" method="POST" class="max-w-4xl mx-auto">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-xl shadow-xl overflow-hidden">
                <!-- Informations Principales -->
                <div class="bg-gradient-to-r from-purple-500 to-blue-500 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">Informations Principales</h2>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Nom de la campagne -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nom de la campagne *</label>
                        <input type="text" name="name" value="{{ old('name', $campaign->name) }}" required
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-purple-500 transition @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nom du client -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nom du client</label>
                        <input type="text" name="client_name" value="{{ old('client_name', $campaign->client_name) }}"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-purple-500 transition">
                    </div>

                    <!-- Type et Placement -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Type de publicité *</label>
                            <select name="type" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-purple-500 transition">
                                <option value="">Sélectionner...</option>
                                <option value="banner" {{ old('type', $campaign->type) == 'banner' ? 'selected' : '' }}>Bannière</option>
                                <option value="video" {{ old('type', $campaign->type) == 'video' ? 'selected' : '' }}>Vidéo</option>
                                <option value="sponsored_post" {{ old('type', $campaign->type) == 'sponsored_post' ? 'selected' : '' }}>Contenu Sponsorisé</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Placement *</label>
                            <input type="text" name="placement" value="{{ old('placement', $campaign->placement) }}" required placeholder="Ex: Homepage Header"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-purple-500 transition">
                        </div>
                    </div>
                </div>

                <!-- Médias et URLs -->
                <div class="bg-gradient-to-r from-blue-500 to-indigo-500 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">Médias et Liens</h2>
                </div>
                <div class="p-6 space-y-6">
                    <!-- URLs -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">URL de l'image/bannière</label>
                            <input type="text" name="image_url" value="{{ old('image_url', $campaign->image_url) }}" placeholder="https://..."
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 transition">
                            @if($campaign->image_url)
                                <div class="mt-2">
                                    <img src="{{ $campaign->image_url }}" alt="Preview" class="h-20 rounded border">
                                </div>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">URL de la vidéo</label>
                            <input type="text" name="video_url" value="{{ old('video_url', $campaign->video_url) }}" placeholder="https://..."
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 transition">
                        </div>
                    </div>

                    <!-- URL cible -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">URL cible (destination)</label>
                        <input type="url" name="target_url" value="{{ old('target_url', $campaign->target_url) }}" placeholder="https://..."
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 transition">
                    </div>
                </div>

                <!-- Statistiques en temps réel -->
                <div class="bg-gradient-to-r from-yellow-500 to-orange-500 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">Statistiques</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-blue-600">{{ number_format($campaign->impressions_count) }}</div>
                            <div class="text-sm text-blue-700 mt-1">Impressions</div>
                        </div>
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-green-600">{{ number_format($campaign->clicks_count) }}</div>
                            <div class="text-sm text-green-700 mt-1">Clics</div>
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-purple-600">
                                {{ $campaign->impressions_count > 0 ? number_format(($campaign->clicks_count / $campaign->impressions_count) * 100, 2) : 0 }}%
                            </div>
                            <div class="text-sm text-purple-700 mt-1">CTR</div>
                        </div>
                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-orange-600">{{ number_format($campaign->price_cents / 100, 2) }} €</div>
                            <div class="text-sm text-orange-700 mt-1">Budget</div>
                        </div>
                    </div>
                </div>

                <!-- Planification et Budget -->
                <div class="bg-gradient-to-r from-green-500 to-teal-500 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">Planification et Budget</h2>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Dates -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Date de début</label>
                            <input type="date" name="start_date" value="{{ old('start_date', $campaign->start_date?->format('Y-m-d')) }}"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-green-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Date de fin</label>
                            <input type="date" name="end_date" value="{{ old('end_date', $campaign->end_date?->format('Y-m-d')) }}"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-green-500 transition">
                        </div>
                    </div>

                    <!-- Budget et Statut -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Budget (en centimes)</label>
                            <input type="number" name="price_cents" value="{{ old('price_cents', $campaign->price_cents) }}" min="0"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-green-500 transition">
                            <p class="mt-1 text-xs text-gray-500">Exemple: 10000 = 100.00 €</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Statut *</label>
                            <select name="status" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-green-500 transition">
                                <option value="draft" {{ old('status', $campaign->status) == 'draft' ? 'selected' : '' }}>Brouillon</option>
                                <option value="active" {{ old('status', $campaign->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="paused" {{ old('status', $campaign->status) == 'paused' ? 'selected' : '' }}>En Pause</option>
                                <option value="ended" {{ old('status', $campaign->status) == 'ended' ? 'selected' : '' }}>Terminée</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="bg-gray-50 px-6 py-4 flex justify-between">
                    <form action="{{ route('admin.advertising.campaigns.destroy', $campaign) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette campagne ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition">
                            Supprimer
                        </button>
                    </form>
                    <div class="flex gap-4">
                        <a href="{{ route('admin.advertising.campaigns.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition">
                            Annuler
                        </a>
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg font-semibold hover:from-purple-700 hover:to-blue-700 transition shadow-lg">
                            Sauvegarder
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
