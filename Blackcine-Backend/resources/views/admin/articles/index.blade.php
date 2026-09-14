@extends('layouts.app')

@section('title', 'Articles - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Articles</h1>
        <p class="text-gray-600 mt-2">Gestion des actualités et contenus éditoriaux</p>
    </div>
    <a href="{{ route('admin.articles.create') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center shadow-sm transition-colors">
        <span class="material-symbols-outlined mr-2">add_circle</span>
        Nouvel article
    </a>
</div>

<!-- Filtres et Recherche -->
<div class="bg-white rounded-lg shadow-sm p-4 mb-6 border border-gray-100">
    <form method="GET" action="{{ route('admin.articles.index') }}" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="material-symbols-outlined text-gray-400">search</span>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un article..." 
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
        </div>
        <div class="w-full md:w-48">
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
                <option value="">Tous les statuts</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publié</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Brouillon</option>
            </select>
        </div>
        <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-900 transition-colors">
            Filtrer
        </button>
    </form>
</div>

<div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100">
    <div class="table-container">
        <table class="w-full min-w-max">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Article</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Auteur</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Stats</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($articles as $article)
                <tr class="hover:bg-gray-50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-start">
                            @if($article->image)
                                <img src="{{ asset('storage/' . $article->image) }}" alt="" class="h-12 w-16 object-cover rounded mr-3 shadow-sm">
                            @else
                                <div class="h-12 w-16 bg-gray-100 rounded mr-3 flex items-center justify-center text-gray-400">
                                    <span class="material-symbols-outlined">image</span>
                                </div>
                            @endif
                            <div>
                                <div class="text-sm font-medium text-gray-900 group-hover:text-red-600 transition-colors">{{ $article->title }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ Str::limit($article->excerpt, 60) }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <div class="flex items-center">
                            <div class="h-6 w-6 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600 mr-2">
                                {{ substr($article->author->name ?? 'A', 0, 1) }}
                            </div>
                            {{ $article->author->name ?? 'Inconnu' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($article->status === 'published')
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 border border-green-200">
                                Publié
                            </span>
                        @else
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                                Brouillon
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <div class="flex items-center space-x-3">
                            <span class="flex items-center text-xs" title="Vues">
                                <span class="material-symbols-outlined text-sm mr-1 text-gray-400">visibility</span>
                                {{ number_format($article->views_count) }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $article->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('admin.articles.edit', $article->id) }}" class="p-1 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded transition-colors" title="Modifier">
                                <span class="material-symbols-outlined">edit</span>
                            </a>
                            <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cet article ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1 text-red-600 hover:text-red-900 hover:bg-red-50 rounded transition-colors" title="Supprimer">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="bg-gray-100 p-4 rounded-full mb-3">
                                <span class="material-symbols-outlined text-3xl text-gray-400">article</span>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Aucun article</h3>
                            <p class="text-gray-500 mt-1">Commencez par créer votre premier article.</p>
                            <a href="{{ route('admin.articles.create') }}" class="mt-4 text-red-600 hover:text-red-700 font-medium">
                                Créer un article
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $articles->links() }}
    </div>
</div>
@endsection
