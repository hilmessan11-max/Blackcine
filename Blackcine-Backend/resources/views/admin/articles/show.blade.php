@extends('layouts.app')

@section('title', $article->title . ' - Détails Article')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">{{ $article->title }}</h1>
        <p class="text-gray-600 mt-2">
            Par {{ $article->author->name ?? 'Anonyme' }} • {{ $article->published_at ? $article->published_at->format('d/m/Y') : 'Non publié' }}
        </p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('admin.articles.edit', $article->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Modifier
        </a>
        <a href="{{ route('admin.articles.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400">
            Retour
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Contenu principal -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Image à la une -->
        @if($article->featured_image)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <img src="{{ $article->featured_image }}" alt="{{ $article->title }}" class="w-full h-96 object-cover">
        </div>
        @endif

        <!-- Contenu de l'article -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Contenu</h2>
            <div class="prose max-w-none">
                {!! $article->content !!}
            </div>
        </div>

        <!-- Extrait -->
        @if($article->excerpt)
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Extrait</h2>
            <p class="text-gray-700">{{ $article->excerpt }}</p>
        </div>
        @endif

        <!-- Catégories & Tags -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Classification</h2>
            <div class="space-y-4">
                @if($article->category)
                <div>
                    <span class="text-sm font-medium text-gray-600">Catégorie :</span>
                    <span class="ml-2 px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm">{{ $article->category }}</span>
                </div>
                @endif
                
                @if($article->tags && count($article->tags) > 0)
                <div>
                    <span class="text-sm font-medium text-gray-600 block mb-2">Tags :</span>
                    <div class="flex flex-wrap gap-2">
                        @foreach($article->tags as $tag)
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1">
        <!-- Statut -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Publication</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Statut</span>
                    <span class="px-3 py-1 text-sm rounded-full {{ $article->status === 'published' ? 'bg-green-100 text-green-800' : ($article->status ===  'draft' ? 'bg-gray-100 text-gray-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ $article->status === 'published' ? 'Publié' : ($article->status === 'draft' ? 'Brouillon' : 'En attente') }}
                    </span>
                </div>
                
                @if($article->published_at)
                <div>
                    <span class="text-sm text-gray-600 block">Date de publication</span>
                    <span class="text-sm font-medium text-gray-900">{{ $article->published_at->format('d/m/Y à H:i') }}</span>
                </div>
                @endif
                
                <div>
                    <span class="text-sm text-gray-600 block">Créé le</span>
                    <span class="text-sm font-medium text-gray-900">{{ $article->created_at->format('d/m/Y à H:i') }}</span>
                </div>
                
                <div>
                    <span class="text-sm text-gray-600 block">Modifié le</span>
                    <span class="text-sm font-medium text-gray-900">{{ $article->updated_at->format('d/m/Y à H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Statistiques</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <span class="text-sm text-gray-600">Vues</span>
                    </div>
                    <span class="text-sm font-bold text-gray-900">{{ number_format($article->views_count ?? 0) }}</span>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <span class="text-sm text-gray-600">Commentaires</span>
                    </div>
                    <span class="text-sm font-bold text-gray-900">{{ $article->comments_count ?? 0 }}</span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Actions</h3>
            <div class="space-y-2">
                <a href="{{ route('admin.articles.edit', $article->id) }}" class="block w-full text-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Modifier
                </a>
                
                @if($article->status !== 'published')
                <form action="{{ route('admin.articles.update', $article->id) }}" method="POST" class="w-full">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="published">
                    <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Publier
                    </button>
                </form>
                @endif
                
                <button type="button" class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                    </svg>
                    Partager
                </button>
            </div>
        </div>

        <!-- Auteur -->
        @if($article->author)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Auteur</h3>
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                    {{ strtoupper(substr($article->author->name, 0, 2)) }}
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">{{ $article->author->name }}</p>
                    <p class="text-xs text-gray-500">{{ $article->author->email }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
