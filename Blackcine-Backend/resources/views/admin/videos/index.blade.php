@extends('layouts.app')

@section('title', 'Vidéos - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Vidéos</h1>
        <p class="text-gray-600 mt-2">Gestion de la vidéothèque et des contenus multimédias</p>
    </div>
    <a href="{{ route('admin.videos.create') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center shadow-sm transition-colors">
        <span class="material-symbols-outlined mr-2">add_circle</span>
        Ajouter une vidéo
    </a>
</div>

<!-- Filtres et Recherche -->
<div class="bg-white rounded-lg shadow-sm p-4 mb-6 border border-gray-100">
    <form method="GET" action="{{ route('admin.videos.index') }}" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="material-symbols-outlined text-gray-400">search</span>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher une vidéo..." 
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
        </div>
        <div class="w-full md:w-48">
            <select name="source_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
                <option value="">Toutes les sources</option>
                <option value="youtube" {{ request('source_type') === 'youtube' ? 'selected' : '' }}>YouTube</option>
                <option value="vimeo" {{ request('source_type') === 'vimeo' ? 'selected' : '' }}>Vimeo</option>
                <option value="upload" {{ request('source_type') === 'upload' ? 'selected' : '' }}>Upload</option>
            </select>
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
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Vidéo</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Source</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Durée</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Stats</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($videos as $video)
                <tr class="hover:bg-gray-50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-start">
                            <div class="relative h-16 w-28 flex-shrink-0 mr-3 rounded overflow-hidden bg-gray-100 group-hover:shadow-md transition-all">
                                @if($video->thumbnail_url)
                                    <img src="{{ $video->thumbnail_url }}" alt="" class="h-full w-full object-cover">
                                @else
                                    <div class="h-full w-full flex items-center justify-center text-gray-400">
                                        <span class="material-symbols-outlined text-2xl">play_circle</span>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all flex items-center justify-center">
                                    <span class="material-symbols-outlined text-white opacity-0 group-hover:opacity-100 transition-opacity">play_arrow</span>
                                </div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900 group-hover:text-red-600 transition-colors line-clamp-2">{{ $video->title }}</div>
                                <div class="text-xs text-gray-500 mt-1">{{ Str::limit($video->description, 50) }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            @if($video->source_type === 'youtube')
                                <span class="text-red-600 mr-1 font-bold">YT</span>
                            @elseif($video->source_type === 'vimeo')
                                <span class="text-blue-500 mr-1 font-bold">V</span>
                            @else
                                <span class="material-symbols-outlined text-xs mr-1">upload_file</span>
                            @endif
                            {{ ucfirst($video->source_type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-mono">
                        {{ $video->duration_seconds ? gmdate('H:i:s', $video->duration_seconds) : '--:--' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($video->status === 'published')
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 border border-green-200">
                                Publié
                            </span>
                        @else
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                                Brouillon
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        <div class="flex items-center" title="Vues">
                            <span class="material-symbols-outlined text-sm mr-1 text-gray-400">visibility</span>
                            {{ number_format($video->views_count) }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $video->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('admin.videos.edit', $video->id) }}" class="p-1 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded transition-colors" title="Modifier">
                                <span class="material-symbols-outlined">edit</span>
                            </a>
                            <form action="{{ route('admin.videos.destroy', $video->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cette vidéo ?');">
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
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="bg-gray-100 p-4 rounded-full mb-3">
                                <span class="material-symbols-outlined text-3xl text-gray-400">video_library</span>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Aucune vidéo</h3>
                            <p class="text-gray-500 mt-1">Ajoutez votre première vidéo pour commencer.</p>
                            <a href="{{ route('admin.videos.create') }}" class="mt-4 text-red-600 hover:text-red-700 font-medium">
                                Ajouter une vidéo
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $videos->links() }}
    </div>
</div>
@endsection
