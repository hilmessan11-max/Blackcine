@extends('layouts.app')

@section('title', $ranking->name . ' - Gestion Classement')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $ranking->name }}</h1>
            <p class="text-gray-600 mt-2">
                {{ ucfirst($ranking->type) }} • {{ ucfirst(str_replace('_', ' ', $ranking->period)) }}
                @if($ranking->genre) • {{ $ranking->genre }} @endif
                @if($ranking->country) • {{ $ranking->country }} @endif
                @if($ranking->year) • {{ $ranking->year }} @endif
            </p>
        </div>
        <div class="flex space-x-3">
            <form action="{{ route('admin.rankings.recalculate', $ranking->id) }}" method="POST">
                @csrf
                <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                    🔄 Recalculer Scores
                </button>
            </form>
            <a href="{{ route('admin.rankings.edit', $ranking->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                ✏️ Modifier
            </a>
            <a href="{{ route('admin.rankings.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400">
                ← Retour
            </a>
        </div>
    </div>
</div>

<!-- Description -->
@if($ranking->description)
<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
    <p class="text-blue-900">{{ $ranking->description }}</p>
</div>
@endif

<!-- Statistiques -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Titres</p>
                <p class="text-3xl font-bold text-gray-900">{{ $ranking->entries->count() }}</p>
            </div>
            <span class="text-4xl">🎬</span>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Statut</p>
                <p class="text-xl font-bold {{ $ranking->is_active ? 'text-green-600' : 'text-gray-600' }}">
                    {{ $ranking->is_active ? 'Actif' : 'Inactif' }}
                </p>
            </div>
            <span class="text-4xl">{{ $ranking->is_active ? '✓' : '⏸️' }}</span>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Score Moyen</p>
                <p class="text-3xl font-bold text-gray-900">
                    {{ $ranking->entries->count() > 0 ? number_format($ranking->entries->avg('score'), 1) : '0' }}
                </p>
            </div>
            <span class="text-4xl">📊</span>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Créé le</p>
                <p class="text-sm font-bold text-gray-900">{{ $ranking->created_at->format('d/m/Y') }}</p>
            </div>
            <span class="text-4xl">📅</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Liste des titres classés -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Classement</h2>
            </div>
            
            <div id="rankingList" class="divide-y divide-gray-200">
                @forelse($ranking->entries as $entry)
                    <div class="p-6 hover:bg-gray-50" data-entry-id="{{ $entry->id }}">
                        <div class="flex items-center space-x-4">
                            <!-- Position -->
                            <div class="flex-shrink-0 w-16 h-16 {{ $entry->position <= 3 ? 'bg-gradient-to-br from-yellow-400 to-yellow-600' : 'bg-gray-200' }} rounded-lg flex items-center justify-center text-2xl font-bold {{ $entry->position <= 3 ? 'text-white' : 'text-gray-700' }}">
                                {{ $entry->position }}
                            </div>

                            <!-- Évolution -->
                            <div class="flex-shrink-0 w-12 text-center">
                                @if($entry->position_change === 'new')
                                    <span class="text-xs font-semibold text-blue-600 bg-blue-100 px-2 py-1 rounded-full">NEW</span>
                                @elseif(is_numeric($entry->position_change) && $entry->position_change > 0)
                                    <span class="text-green-600 font-bold">▲ {{ $entry->position_change }}</span>
                                @elseif(is_numeric($entry->position_change) && $entry->position_change < 0)
                                    <span class="text-red-600 font-bold">▼ {{ abs($entry->position_change) }}</span>
                                @else
                                    <span class="text-gray-400">━</span>
                                @endif
                            </div>

                            <!-- Titre -->
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $entry->title->name }}</h3>
                                <div class="flex items-center space-x-3 mt-1 text-sm text-gray-600">
                                    <span>{{ $entry->title->type }}</span>
                                    @if($entry->title->release_date)
                                        <span>• {{ $entry->title->release_date->format('Y') }}</span>
                                    @endif
                                    @if($entry->score)
                                        <span>• Score: <strong>{{ number_format($entry->score, 1) }}</strong></span>
                                    @endif
                                    @if($entry->votes_count)
                                        <span>• {{ $entry->votes_count }} votes</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.titles.show', $entry->title_id) }}" class="text-blue-600 hover:text-blue-800" title="Voir le titre">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.rankings.titles.remove', ['rankingId' => $ranking->id, 'entryId' => $entry->id]) }}" method="POST" onsubmit="return confirm('Retirer ce titre du classement ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Retirer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <p class="mt-4 text-lg text-gray-600">Aucun titre dans ce classement</p>
                        <p class="mt-2 text-sm text-gray-500">Ajoutez des titres via le formulaire ci-contre</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Sidebar - Ajouter un titre -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Ajouter un Titre</h3>
            
            <form action="{{ route('admin.rankings.titles.add', $ranking->id) }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Titre *</label>
                    <select name="title_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionnez un titre...</option>
                        @foreach($availableTitles as $title)
                            <option value="{{ $title->id }}">{{ $title->name }}</option>
                        @endforeach
                    </select>
                    @error('title_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Position *</label>
                    <input type="number" name="position" required min="1" value="{{ $ranking->entries->count() + 1 }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Position dans le classement (1 = premier)</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Score (optionnel)</label>
                    <input type="number" name="score" step="0.01" min="0" max="100"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Laissez vide pour calcul automatique</p>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 font-semibold">
                    + Ajouter au Classement
                </button>
            </form>
        </div>

        <!-- Actions rapides -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Actions</h3>
            <div class="space-y-2">
                <form action="{{ route('admin.rankings.toggle-active', $ranking->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full {{ $ranking->is_active ? 'bg-gray-600' : 'bg-green-600' }} text-white px-4 py-2 rounded-lg hover:opacity-90">
                        {{ $ranking->is_active ? '⏸️ Désactiver' : '▶️ Activer' }}
                    </button>
                </form>
                
                <form action="{{ route('admin.rankings.destroy', $ranking->id) }}" method="POST" onsubmit="return confirm('Supprimer définitivement ce classement ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                        🗑️ Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
