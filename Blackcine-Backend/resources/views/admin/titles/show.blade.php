@extends('layouts.app')

@section('title', $title->name . ' - Fiche Titre - BlackCiné Admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-indigo-50 to-purple-50">
    <!-- Header Premium -->
    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 shadow-xl mb-8">
        <div class="container mx-auto px-6 py-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="px-3 py-1 bg-white/20 backdrop-blur-sm text-white text-sm rounded-full">
                            {{ ucfirst($title->type) }}
                        </span>
                        @if($title->status === 'published')
                        <span class="px-3 py-1 bg-green-500/90 text-white text-sm rounded-full">
                            ✓ Publié
                        </span>
                        @endif
                    </div>
                    <h1 class="text-4xl font-bold text-white mb-2">{{ $title->name }}</h1>
                    <p class="text-indigo-100">
                        @if($title->release_date)
                            📅 {{ $title->release_date->format('Y') }}
                        @endif
                        @if($title->runtime_minutes)
                            • ⏱️ {{ $title->runtime_minutes }} min
                        @endif
                        • 👁️ {{ number_format($title->views_count ?? 0) }} vues
                    </p>
                </div>

                <!-- Actions rapides -->
                <div class="flex flex-wrap gap-2">
                    <form action="{{ route('admin.titles.feature', $title->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-lg hover:bg-white/30 transition">
                            ⭐ En avant
                        </button>
                    </form>
                    <form action="{{ route('admin.titles.now-showing', $title->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-green-500/90 text-white rounded-lg hover:bg-green-600 transition">
                            🔴 À l'affiche
                        </button>
                    </form>
                    <a href="{{ route('admin.titles.edit', $title->id) }}" class="px-4 py-2 bg-white text-indigo-600 rounded-lg hover:bg-indigo-50 transition font-semibold">
                        ✏️ Modifier
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 pb-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Colonne principale -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informations -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                        📋 Informations
                    </h2>
                    @if($title->synopsis)
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-700 mb-2">Synopsis</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $title->synopsis }}</p>
                    </div>
                    @endif
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Type</p>
                            <p class="text-lg font-bold text-indigo-600">{{ ucfirst($title->type) }}</p>
                        </div>
                        @if($title->release_date)
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Sortie</p>
                            <p class="text-lg font-bold text-green-600">{{ $title->release_date->format('d/m/Y') }}</p>
                        </div>
                        @endif
                        @if($title->runtime_minutes)
                        <div class="bg-gradient-to-br from-purple-50 to-pink-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Durée</p>
                            <p class="text-lg font-bold text-purple-600">{{ $title->runtime_minutes }} min</p>
                        </div>
                        @endif
                        <div class="bg-gradient-to-br from-yellow-50 to-orange-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Vues</p>
                            <p class="text-lg font-bold text-orange-600">{{ number_format($title->views_count ?? 0) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Genres -->
                @if($title->genres->count() > 0)
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                        🏷️ Genres
                    </h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($title->genres as $genre)
                        <span class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-full text-sm font-medium shadow-md transform hover:scale-105 transition">
                            {{ $genre->name }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Cast & Crédits -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                            🎭 Cast & Crédits
                        </h2>
                        <button onclick="toggleForm('add-credit-form')" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-500 text-white rounded-lg hover:from-blue-600 hover:to-indigo-600 transition text-sm font-semibold">
                            + Ajouter
                        </button>
                    </div>

                    <!-- Formulaire d'ajout -->
                    <div id="add-credit-form" class="hidden mb-6 p-6 bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl border border-blue-200">
                        <form action="{{ route('admin.titles.credits.store', $title->id) }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Personne</label>
                                    <select name="person_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                        <option value="">Sélectionner...</option>
                                        @foreach($persons as $person)
                                        <option value="{{ $person->id }}">{{ $person->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Département</label>
                                    <select name="department" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                        <option value="Acting">🎬 Acting</option>
                                        <option value="Directing">🎥 Directing</option>
                                        <option value="Writing">✍️ Writing</option>
                                        <option value="Production">📹 Production</option>
                                        <option value="Editing">✂️ Editing</option>
                                        <option value="Sound">🎵 Sound</option>
                                        <option value="Camera">📷 Camera</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Job / Rôle</label>
                                    <input type="text" name="job" placeholder="ex: Director, Actor" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nom du personnage (si acteur)</label>
                                    <input type="text" name="character_name" placeholder="ex: John Wick" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                </div>
                            </div>
                            <div class="mt-4 flex justify-end">
                                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-lg hover:from-green-600 hover:to-emerald-600 transition font-semibold">
                                    ✓ Ajouter
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Liste des crédits -->
                    <div class="space-y-3">
                        @forelse($title->credits as $credit)
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-indigo-50 rounded-lg hover:shadow-md transition">
                            <div class="flex items-center gap-4">
                                <div class="flex-shrink-0 h-12 w-12 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-lg">
                                    {{ substr($credit->person->name ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $credit->person->name ?? 'Inconnu' }}</p>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">{{ $credit->job }}</span>
                                        @if($credit->character_name)
                                        <span class="text-indigo-600">as <em>{{ $credit->character_name }}</em></span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <form action="{{ route('admin.titles.credits.destroy', ['id' => $title->id, 'creditId' => $credit->id]) }}" method="POST" onsubmit="return confirm('Supprimer ce crédit ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 p-2 hover:bg-red-50 rounded-lg transition">
                                    🗑️
                                </button>
                            </form>
                        </div>
                        @empty
                        <div class="text-center py-12 bg-gray-50 rounded-lg">
                            <div class="text-4xl mb-2">🎭</div>
                            <p class="text-gray-500">Aucun crédit ajouté</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Bandes-annonces -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                            🎬 Bandes-annonces
                        </h2>
                        <button onclick="toggleForm('add-trailer-form')" class="px-4 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-lg hover:from-red-600 hover:to-pink-600 transition text-sm font-semibold">
                            + Ajouter
                        </button>
                    </div>

                    <!-- Formulaire d'ajout -->
                    <div id="add-trailer-form" class="hidden mb-6 p-6 bg-gradient-to-br from-gray-50 to-red-50 rounded-xl border border-red-200">
                        <form action="{{ route('admin.titles.trailers.store', $title->id) }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Titre</label>
                                    <input type="text" name="title" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Source</label>
                                        <select name="source_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                                            <option value="youtube">📺 YouTube</option>
                                            <option value="vimeo">🎥 Vimeo</option>
                                        </select>
                                    </div>
                                    <div class="flex items-center">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="checkbox" name="is_official" value="1" class="h-5 w-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                            <span class="ml-2 text-sm font-semibold text-gray-700">✓ Officiel</span>
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">URL</label>
                                    <input type="url" name="source_url" required placeholder="https://..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                                </div>
                            </div>
                            <div class="mt-4 flex justify-end">
                                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-lg hover:from-green-600 hover:to-emerald-600 transition font-semibold">
                                    ✓ Ajouter
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Liste des trailers -->
                    <div class="space-y-3">
                        @forelse($title->trailers as $trailer)
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-red-50 rounded-lg hover:shadow-md transition">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $trailer->title }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <a href="{{ $trailer->source_url }}" target="_blank" class="text-sm text-blue-600 hover:underline flex items-center gap-1">
                                        🔗 {{ ucfirst($trailer->source_type) }}
                                    </a>
                                    @if($trailer->is_official)
                                    <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full font-medium">✓ Officiel</span>
                                    @endif
                                </div>
                            </div>
                            <form action="{{ route('admin.titles.trailers.destroy', ['id' => $title->id, 'trailerId' => $trailer->id]) }}" method="POST" onsubmit="return confirm('Supprimer ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 p-2 hover:bg-red-50 rounded-lg transition">
                                    🗑️
                                </button>
                            </form>
                        </div>
                        @empty
                        <div class="text-center py-12 bg-gray-50 rounded-lg">
                            <div class="text-4xl mb-2">🎬</div>
                            <p class="text-gray-500">Aucune bande-annonce</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Statistiques -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                        📊 Statistiques
                    </h2>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-700">👁️  Vues</span>
                            <span class="text-lg font-bold text-indigo-600">{{ number_format($title->views_count ?? 0) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-700">💬 Commentaires</span>
                            <span class="text-lg font-bold text-green-600">{{ number_format($title->comments_count ?? 0) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-700">⭐ Note Moyenne</span>
                            <span class="text-lg font-bold text-purple-600">{{ number_format($title->rating ?? 0, 1) }}/5</span>
                        </div>
                        <div class="p-3 bg-gradient-to-r from-gray-50 to-slate-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-700 block mb-2">📌 Statut</span>
                            <span class="px-3 py-1 text-sm rounded-full font-semibold {{ $title->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $title->status === 'published' ? '✓ Publié' : '📝 Brouillon' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                        ⚡ Actions Rapides
                    </h2>
                    <div class="space-y-2">
                        <form action="{{ route('admin.titles.selection', $title->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-3 bg-gradient-to-r from-purple-50 to-pink-50 hover:from-purple-100 hover:to-pink-100 rounded-lg transition font-medium text-gray-700">
                                ⭐ Ajouter à sélection
                            </button>
                        </form>
                        <a href="{{ route('admin.titles.reviews.index', $title->id) }}" class="block w-full text-left px-4 py-3 bg-gradient-to-r from-blue-50 to-indigo-50 hover:from-blue-100 hover:to-indigo-100 rounded-lg transition font-medium text-gray-700">
                            💬 Gérer les avis
                        </a>
                        <a href="{{ route('admin.titles.edit', $title->id) }}" class="block w-full text-left px-4 py-3 bg-gradient-to-r from-green-50 to-emerald-50 hover:from-green-100 hover:to-emerald-100 rounded-lg transition font-medium text-gray-700">
                            ✏️ Modifier le titre
                        </a>
                    </div>
                </div>

                <!-- Infos supplémentaires -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                        ℹ️ Infos Supplémentaires
                    </h2>
                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="text-gray-600">Créé le</span>
                            <p class="font-semibold text-gray-900">{{ $title->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Modifié le</span>
                            <p class="font-semibold text-gray-900">{{ $title->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">ID</span>
                            <p class="font-mono text-gray-900">#{{ $title->id }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleForm(id) {
    const form = document.getElementById(id);
    form.classList.toggle('hidden');
}
</script>
@endsection
