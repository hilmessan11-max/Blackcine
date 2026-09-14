@extends('layouts.app')

@section('title', 'Slider & Bannières - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Slider & Bannières</h1>
        <p class="text-gray-600 mt-2">Gérer les slides de la page d'accueil</p>
    </div>
    <a href="{{ route('admin.editorial.slides.create') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center shadow-sm transition-colors">
        <span class="material-symbols-outlined mr-2">add_circle</span>
        Créer un slide
    </a>
</div>

<!-- Filtres par catégorie -->
<div class="mb-6 bg-white rounded-lg shadow-sm border border-gray-100 p-4">
    <div class="flex items-center space-x-4">
        <span class="text-sm font-medium text-gray-700">Filtrer par catégorie :</span>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.editorial.slides.index', ['category' => 'all']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $category === 'all' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Tous ({{ $stats['all'] }})
            </a>
            <a href="{{ route('admin.editorial.slides.index', ['category' => 'film']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $category === 'film' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Films ({{ $stats['film'] }})
            </a>
            <a href="{{ route('admin.editorial.slides.index', ['category' => 'series']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $category === 'series' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Séries ({{ $stats['series'] }})
            </a>
            <a href="{{ route('admin.editorial.slides.index', ['category' => 'general']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $category === 'general' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Général ({{ $stats['general'] }})
            </a>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100">
    <div class="table-container">
        <table class="w-full min-w-max">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Slide</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Catégorie</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Contenu lié</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">CTA</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ordre</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($slides as $slide)
                <tr class="hover:bg-gray-50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            @if($slide->asset)
                                <img src="{{ asset('storage/' . $slide->asset->path) }}" alt="{{ $slide->title }}" class="w-20 h-12 object-cover rounded mr-3 group-hover:shadow-md transition-shadow">
                            @else
                                <div class="w-20 h-12 bg-gray-100 rounded mr-3 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-gray-300">image</span>
                                </div>
                            @endif
                            <div class="font-medium text-gray-900">{{ $slide->title }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($slide->category === 'film')
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800 border border-purple-200">
                                Film
                            </span>
                        @elseif($slide->category === 'series')
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-indigo-100 text-indigo-800 border border-indigo-200">
                                Série
                            </span>
                        @else
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                                Général
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        @if($slide->title)
                            <a href="{{ route('admin.titles.show', $slide->title->id) }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                                <span class="material-symbols-outlined text-sm mr-1">movie</span>
                                {{ $slide->title->name }}
                            </a>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm">
                        @if($slide->cta_label && $slide->cta_url)
                            <a href="{{ $slide->cta_url }}" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center">
                                <span class="material-symbols-outlined text-sm mr-1">link</span>
                                {{ $slide->cta_label }}
                            </a>
                        @else
                            <span class="text-gray-400">N/A</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2 py-1 rounded bg-gray-100 text-gray-700 text-sm font-mono">
                            #{{ $slide->display_order ?? 0 }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($slide->is_active)
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 border border-green-200">
                                Actif
                            </span>
                        @else
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                                Inactif
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('admin.editorial.slides.edit', $slide->id) }}" class="p-1 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded transition-colors" title="Modifier">
                                <span class="material-symbols-outlined">edit</span>
                            </a>
                            <form action="{{ route('admin.editorial.slides.destroy', $slide->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce slide ?');">
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
                                <span class="material-symbols-outlined text-3xl text-gray-400">slideshow</span>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Aucun slide</h3>
                            <p class="text-gray-500 mt-1">Aucun slide trouvé pour cette catégorie.</p>
                            <a href="{{ route('admin.editorial.slides.create') }}" class="mt-4 text-red-600 hover:text-red-700 font-medium">
                                Créer un slide
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $slides->links() }}
    </div>
</div>
@endsection
