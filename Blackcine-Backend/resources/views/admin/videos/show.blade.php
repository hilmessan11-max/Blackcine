@extends('layouts.app')

@section('title', $video->title . ' - Détails Vidéo')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">{{ $video->title }}</h1>
        <p class="text-gray-600 mt-2">Détails de la vidéo</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('admin.videos.edit', $video->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Modifier
        </a>
        <a href="{{ route('admin.videos.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400">
            Retour
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Vidéo & Informations principales -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Lecteur vidéo -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Aperçu</h2>
            <div class="aspect-video bg-gray-900 rounded-lg overflow-hidden">
                @if($video->source_type === 'youtube' && $video->source_url)
                    @php
                        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^\&\?\/]+)/', $video->source_url, $matches);
                        $youtubeId = $matches[1] ?? null;
                    @endphp
                    @if($youtubeId)
                        <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $youtubeId }}" frameborder="0" allowfullscreen></iframe>
                    @else
                        <div class="flex items-center justify-center h-full text-white">
                            URL YouTube invalide
                        </div>
                    @endif
                @elseif($video->source_type === 'vimeo' && $video->source_url)
                    @php
                        preg_match('/vimeo\.com\/(\d+)/', $video->source_url, $matches);
                        $vimeoId = $matches[1] ?? null;
                    @endphp
                    @if($vimeoId)
                        <iframe class="w-full h-full" src="https://player.vimeo.com/video/{{ $vimeoId }}" frameborder="0" allowfullscreen></iframe>
                    @else
                        <div class="flex items-center justify-center h-full text-white">
                            URL Vimeo invalide
                        </div>
                    @endif
                @elseif($video->source_url)
                    <video class="w-full h-full" controls>
                        <source src="{{ $video->source_url }}" type="video/mp4">
                        Votre navigateur ne supporte pas la lecture vidéo.
                    </video>
                @elseif($video->asset_id)
                    <video class="w-full h-full" controls>
                        <source src="{{ asset('storage/' . $video->asset->path) }}" type="video/mp4">
                        Votre navigateur ne supporte pas la lecture vidéo.
                    </video>
                @else
                    <div class="flex items-center justify-center h-full text-white">
                        <svg class="w-24 h-24 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                @endif
            </div>
        </div>

        <!-- Informations détaillées -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Informations</h2>
            <div class="space-y-3">
                <div>
                    <span class="text-sm font-medium text-gray-600">Type de source :</span>
                    <span class="ml-2 text-gray-900">{{ ucfirst($video->source_type) }}</span>
                </div>
                @if($video->source_url)
                <div>
                    <span class="text-sm font-medium text-gray-600">URL :</span>
                    <a href="{{ $video->source_url }}" target="_blank" class="ml-2 text-blue-600 hover:text-blue-800 break-all">{{ $video->source_url }}</a>
                </div>
                @endif
                @if($video->duration_seconds)
                <div>
                    <span class="text-sm font-medium text-gray-600">Durée :</span>
                    <span class="ml-2 text-gray-900">{{ gmdate('H:i:s', $video->duration_seconds) }}</span>
                </div>
                @endif
                @if($video->description)
                <div>
                    <span class="text-sm font-medium text-gray-600">Description :</span>
                    <p class="mt-1 text-gray-900">{{ $video->description }}</p>
                </div>
                @endif
                <div>
                    <span class="text-sm font-medium text-gray-600">Vues :</span>
                    <span class="ml-2 text-gray-900">{{ number_format($video->views_count) }}</span>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-600">Date de création :</span>
                    <span class="ml-2 text-gray-900">{{ $video->created_at->format('d/m/Y à H:i') }}</span>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-600">Dernière mise à jour :</span>
                    <span class="ml-2 text-gray-900">{{ $video->updated_at->format('d/m/Y à H:i') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1">
        <!-- Statut -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Statut</h3>
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm text-gray-600">Publication</span>
                <span class="px-3 py-1 text-sm rounded-full {{ $video->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ $video->status === 'published' ? 'Publié' : 'Brouillon' }}
                </span>
            </div>
            @if($video->published_at)
            <div class="text-xs text-gray-500">
                Publié le {{ $video->published_at->format('d/m/Y à H:i') }}
            </div>
            @endif
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Actions</h3>
            <div class="space-y-2">
                <a href="{{ $video->source_url }}" target="_blank" class="block w-full text-center bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    Voir la vidéo
                </a>
                <a href="{{ route('admin.videos.edit', $video->id) }}" class="block w-full text-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Modifier
                </a>
                <form action="{{ route('admin.videos.destroy', $video->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette vidéo ?');" class="w-full">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Supprimer
                    </button>
                </form>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="bg-white rounded-lg shadow p-6 mt-6">
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
                    <span class="text-sm font-bold text-gray-900">{{ number_format($video->views_count) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
