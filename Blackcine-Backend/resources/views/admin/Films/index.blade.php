@extends('layouts.app')

@section('title', 'Catalogue Films - BlackCiné Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Catalogue Films</h1>
        <p class="text-gray-600 mt-2">Gérez votre bibliothèque de films</p>
    </div>
    <a href="{{ route('admin.films.create') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center shadow-sm transition-colors">
        <span class="material-symbols-outlined mr-2">add_circle</span>
        Ajouter un film
    </a>
</div>

<!-- Stats rapides -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total</p>
                <p class="text-2xl font-bold text-gray-900">{{ $items->total() }}</p>
            </div>
            <div class="bg-blue-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-blue-600">movie</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Cette semaine</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['new_this_week'] ?? 0 }}</p>
            </div>
            <div class="bg-green-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-green-600">trending_up</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Mieux notés</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['top_rated'] ?? 0 }}</p>
            </div>
            <div class="bg-yellow-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-yellow-600">star</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">En vedette</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['featured'] ?? 0 }}</p>
            </div>
            <div class="bg-red-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-red-600">workspace_premium</span>
            </div>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-100">
    <form method="GET" id="filterForm">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-gray-400">search</span>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Titre, réalisateur..." 
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
                    <option value="">Toutes</option>
                    <option value="action" {{ request('category') == 'action' ? 'selected' : '' }}>Action</option>
                    <option value="comedie" {{ request('category') == 'comedie' ? 'selected' : '' }}>Comédie</option>
                    <option value="drame" {{ request('category') == 'drame' ? 'selected' : '' }}>Drame</option>
                    <option value="thriller" {{ request('category') == 'thriller' ? 'selected' : '' }}>Thriller</option>
                    <option value="science_fiction" {{ request('category') == 'science_fiction' ? 'selected' : '' }}>SF</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Année</label>
                <select name="year" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
                    <option value="">Toutes</option>
                    @for($y = date('Y'); $y >= 1950; $y--)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Trier par</label>
                <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Plus récents</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Plus anciens</option>
                    <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Titre A-Z</option>
                    <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Mieux notés</option>
                </select>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <div class="flex gap-2">
                <button type="button" onclick="setView('grid')" id="viewGrid" class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition flex items-center">
                    <span class="material-symbols-outlined mr-1">grid_view</span>
                    Grille
                </button>
                <button type="button" onclick="setView('list')" id="viewList" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition flex items-center">
                    <span class="material-symbols-outlined mr-1">view_list</span>
                    Liste
                </button>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.films.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                    Réinitialiser
                </a>
                <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                    Filtrer
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Vue Grille -->
<div id="gridView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @forelse($items as $film)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all group">
        <!-- Image -->
        <div class="relative h-64 bg-gray-100 overflow-hidden">
            @if($film->poster_url)
                <img src="{{ $film->poster_url }}" alt="{{ $film->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            @else
                <div class="w-full h-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-5xl text-gray-300">movie</span>
                </div>
            @endif
            
            @if($film->rating)
            <div class="absolute top-3 left-3 bg-yellow-400 text-yellow-900 px-2 py-1 rounded-lg font-bold text-sm flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">star</span>
                {{ number_format($film->rating, 1) }}
            </div>
            @endif

            @if($film->category)
            <div class="absolute top-3 right-3">
                <span class="px-2 py-1 bg-gray-900 bg-opacity-80 text-white text-xs font-medium rounded">
                    {{ str_replace('_', ' ', ucfirst($film->category)) }}
                </span>
            </div>
            @endif
        </div>

        <!-- Contenu -->
        <div class="p-4">
            <h3 class="font-bold text-gray-900 text-lg mb-2 line-clamp-1" title="{{ $film->title }}">
                {{ $film->title }}
            </h3>

            <div class="flex items-center gap-2 text-sm text-gray-600 mb-3">
                <span class="flex items-center">
                    <span class="material-symbols-outlined text-sm mr-1">calendar_today</span>
                    {{ $film->release_year ?? '-' }}
                </span>
                @if($film->duration_minutes)
                <span>•</span>
                <span>{{ $film->duration_minutes }} min</span>
                @endif
            </div>

            <!-- Actions -->
            <div class="flex gap-2 pt-3 border-t border-gray-100">
                <a href="{{ route('admin.films.show', $film->id) }}" class="flex-1 text-center px-3 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition text-sm font-medium flex items-center justify-center">
                    <span class="material-symbols-outlined text-sm mr-1">visibility</span>
                    Voir
                </a>
                <a href="{{ route('admin.films.edit', $film->id) }}" class="flex-1 text-center px-3 py-2 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition text-sm font-medium flex items-center justify-center">
                    <span class="material-symbols-outlined text-sm mr-1">edit</span>
                    Modifier
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="bg-gray-100 p-4 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                <span class="material-symbols-outlined text-3xl text-gray-400">movie</span>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Aucun film trouvé</h3>
            <p class="text-gray-500 mb-6">Commencez par ajouter votre premier film</p>
            <a href="{{ route('admin.films.create') }}" class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                <span class="material-symbols-outlined mr-2">add</span>
                Ajouter un film
            </a>
        </div>
    </div>
    @endforelse
</div>

<!-- Vue Liste -->
<div id="listView" class="hidden bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100">
    <div class="table-container">
        <table class="w-full min-w-max">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Film</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Catégorie</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Année</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Note</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($items as $film)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-12 h-16 bg-gray-100 rounded overflow-hidden mr-3">
                                @if($film->poster_url)
                                    <img src="{{ $film->poster_url }}" alt="{{ $film->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="material-symbols-outlined text-xl text-gray-300">movie</span>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">{{ $film->title }}</div>
                                <div class="text-sm text-gray-500">{{ $film->duration_minutes ?? '-' }} min</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded">
                            {{ str_replace('_', ' ', ucfirst($film->category ?? '-')) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $film->release_year ?? '-' }}</td>
                    <td class="px-6 py-4">
                        @if($film->rating)
                        <div class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-yellow-500 text-sm">star</span>
                            <span class="font-medium text-gray-900">{{ number_format($film->rating, 1) }}</span>
                        </div>
                        @else
                        <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('admin.films.show', $film->id) }}" class="p-1 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded transition-colors" title="Voir">
                                <span class="material-symbols-outlined">visibility</span>
                            </a>
                            <a href="{{ route('admin.films.edit', $film->id) }}" class="p-1 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded transition-colors" title="Modifier">
                                <span class="material-symbols-outlined">edit</span>
                            </a>
                            <form action="{{ route('admin.films.destroy', $film->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce film ?')">
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
                    <td colspan="5" class="px-6 py-12 text-center">
                        <span class="material-symbols-outlined text-4xl text-gray-300 mb-2 block">movie</span>
                        <p class="text-gray-500">Aucun film trouvé</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $items->appends(request()->query())->links() }}
</div>

<script>
let currentView = localStorage.getItem('filmViewMode') || 'grid';

function setView(mode) {
    currentView = mode;
    localStorage.setItem('filmViewMode', mode);
    
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');
    const gridBtn = document.getElementById('viewGrid');
    const listBtn = document.getElementById('viewList');
    
    if (mode === 'grid') {
        gridView.classList.remove('hidden');
        listView.classList.add('hidden');
        gridBtn.classList.add('bg-red-100', 'text-red-700');
        gridBtn.classList.remove('bg-gray-100', 'text-gray-700');
        listBtn.classList.add('bg-gray-100', 'text-gray-700');
        listBtn.classList.remove('bg-red-100', 'text-red-700');
    } else {
        gridView.classList.add('hidden');
        listView.classList.remove('hidden');
        listBtn.classList.add('bg-red-100', 'text-red-700');
        listBtn.classList.remove('bg-gray-100', 'text-gray-700');
        gridBtn.classList.add('bg-gray-100', 'text-gray-700');
        gridBtn.classList.remove('bg-red-100', 'text-red-700');
    }
}

window.addEventListener('DOMContentLoaded', () => {
    setView(currentView);
});
</script>
@endsection