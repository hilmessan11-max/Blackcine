@extends('layouts.app')

@section('title', 'Catalogue Titres - BlackCiné Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Catalogue Titres</h1>
        <p class="text-gray-600 mt-2">Gérez tous vos films et séries</p>
    </div>
    <a href="{{ route('admin.titles.create') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center shadow-sm transition-colors">
        <span class="material-symbols-outlined mr-2">add_circle</span>
        Ajouter un titre
    </a>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4"><div class="flex items-center justify-between"><div><p class="text-sm text-gray-500">Total</p><p class="text-2xl font-bold text-gray-900">{{ $items->total() }}</p></div><div class="bg-blue-50 p-3 rounded-full"><span class="material-symbols-outlined text-blue-600">movie</span></div></div></div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4"><div class="flex items-center justify-between"><div><p class="text-sm text-gray-500">Films</p><p class="text-2xl font-bold text-gray-900">{{ $stats['films'] ?? 0 }}</p></div><div class="bg-purple-50 p-3 rounded-full"><span class="material-symbols-outlined text-purple-600">theaters</span></div></div></div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4"><div class="flex items-center justify-between"><div><p class="text-sm text-gray-500">Séries</p><p class="text-2xl font-bold text-gray-900">{{ $stats['series'] ?? 0 }}</p></div><div class="bg-pink-50 p-3 rounded-full"><span class="material-symbols-outlined text-pink-600">tv</span></div></div></div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4"><div class="flex items-center justify-between"><div><p class="text-sm text-gray-500">Publiés</p><p class="text-2xl font-bold text-gray-900">{{ $stats['published'] ?? 0 }}</p></div><div class="bg-green-50 p-3 rounded-full"><span class="material-symbols-outlined text-green-600">check_circle</span></div></div></div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4"><div class="flex items-center justify-between"><div><p class="text-sm text-gray-500">En avant</p><p class="text-2xl font-bold text-gray-900">{{ $stats['featured'] ?? 0 }}</p></div><div class="bg-yellow-50 p-3 rounded-full"><span class="material-symbols-outlined text-yellow-600">star</span></div></div></div>
</div>

<!-- Filtres -->
<div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 mb-6">
    <form method="GET" id="filterForm">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
            <div class="md:col-span-2 relative"><span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><span class="material-symbols-outlined text-gray-400">search</span></span><input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors"></div>
            <div><select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors"><option value="">Tous les types</option><option value="film" {{ request('type') == 'film' ? 'selected' : '' }}>Films</option><option value="series" {{ request('type') == 'series' ? 'selected' : '' }}>Séries</option></select></div>
            <div><select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors"><option value="">Tous statuts</option><option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Publié</option><option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Brouillon</option></select></div>
        </div>
        <div class="flex flex-wrap gap-3">
            <select name="sort" class="flex-1 md:flex-none md:w-48 px-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors"><option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Plus récents</option><option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Plus anciens</option><option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Titre A-Z</option><option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Titre Z-A</option><option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Plus vus</option></select>
            <div class="flex gap-2"><button type="button" onclick="setView('grid')" id="viewGrid" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors flex items-center"><span class="material-symbols-outlined mr-1">grid_view</span>Grille</button><button type="button" onclick="setView('list')" id="viewList" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors flex items-center"><span class="material-symbols-outlined mr-1">list</span>Liste</button></div>
            <button type="submit" class="flex-1 md:flex-none bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-900 transition-colors">Filtrer</button>
            <a href="{{ route('admin.titles.index') }}" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">Réinitialiser</a>
        </div>
    </form>
</div>

<!-- Vue Grille -->
<div id="gridView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @forelse($items as $title)
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
        <div class="relative h-64 bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden flex items-center justify-center">
            @if($title->poster_url)
                <img src="{{ $title->poster_url }}" alt="{{ $title->name }}" class="w-full h-full object-cover">
            @else
                <span class="material-symbols-outlined text-6xl text-gray-300">{{ $title->type === 'film' ? 'movie' : 'tv' }}</span>
            @endif
            <div class="absolute top-3 right-3"><span class="px-2.5 py-1 {{ $title->type === 'film' ? 'bg-purple-600' : 'bg-pink-600' }} text-white text-xs font-medium rounded-full">{{ $title->type === 'film' ? 'Film' : 'Série' }}</span></div>
            <div class="absolute top-3 left-3">@if($title->status === 'published')<span class="px-2.5 py-1 bg-green-600 text-white text-xs font-medium rounded-full flex items-center"><span class="material-symbols-outlined text-xs mr-1">check_circle</span>Publié</span>@else<span class="px-2.5 py-1 bg-gray-600 text-white text-xs font-medium rounded-full">Brouillon</span>@endif</div>
        </div>
        <div class="p-4">
            <h3 class="font-bold text-gray-900 text-lg mb-2 truncate" title="{{ $title->name }}">{{ $title->name }}</h3>
            <div class="flex items-center gap-2 text-sm text-gray-600 mb-3">@if($title->release_date)<span class="flex items-center"><span class="material-symbols-outlined text-sm mr-1">calendar_today</span>{{ $title->release_date->format('Y') }}</span>@endif @if($title->runtime_minutes)<span>•</span><span>{{ $title->runtime_minutes }} min</span>@endif</div>
            <div class="flex items-center gap-4 text-sm text-gray-600 mb-3"><span class="flex items-center"><span class="material-symbols-outlined text-sm mr-1">visibility</span>{{ number_format($title->views_count ?? 0) }}</span>@if($title->rating)<span class="flex items-center"><span class="material-symbols-outlined text-sm mr-1 text-yellow-500">star</span>{{ number_format($title->rating, 1) }}</span>@endif</div>
            <div class="flex gap-2 pt-3 border-t border-gray-200"><a href="{{ route('admin.titles.show', $title->id) }}" class="flex-1 text-center px-3 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition text-sm font-medium flex items-center justify-center"><span class="material-symbols-outlined text-sm mr-1">visibility</span>Voir</a><a href="{{ route('admin.titles.edit', $title->id) }}" class="flex-1 text-center px-3 py-2 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition text-sm font-medium flex items-center justify-center"><span class="material-symbols-outlined text-sm mr-1">edit</span>Modifier</a><form action="{{ route('admin.titles.destroy', $title->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ?')">@csrf @method('DELETE')<button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"><span class="material-symbols-outlined text-sm">delete</span></button></form></div>
        </div>
    </div>
    @empty
    <div class="col-span-full bg-white rounded-lg shadow-sm border border-gray-100 p-12 text-center"><div class="bg-gray-100 p-4 rounded-full mb-3 inline-block"><span class="material-symbols-outlined text-3xl text-gray-400">movie</span></div><h3 class="text-lg font-medium text-gray-900">Aucun titre</h3><p class="text-gray-500 mt-1">Ajoutez votre premier titre</p><a href="{{ route('admin.titles.create') }}" class="mt-4 inline-block bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition">Ajouter un titre</a></div>
    @endforelse
</div>

<!-- Vue Liste -->
<div id="listView" class="hidden bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full"><thead class="bg-gray-50 border-b border-gray-200"><tr><th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Titre</th><th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Type</th><th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th><th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Vues</th><th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Note</th><th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Statut</th><th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th></tr></thead>
        <tbody class="divide-y divide-gray-200">@forelse($items as $title)
            <tr class="hover:bg-gray-50 transition"><td class="px-6 py-4"><div class="font-medium text-gray-900">{{ $title->name }}</div><div class="text-sm text-gray-500">{{ Str::limit($title->synopsis ?? '', 60) }}</div></td><td class="px-6 py-4"><span class="px-2.5 py-1 {{ $title->type === 'film' ? 'bg-purple-100 text-purple-800' : 'bg-pink-100 text-pink-800' }} text-xs font-medium rounded-full border {{ $title->type === 'film' ? 'border-purple-200' : 'border-pink-200' }}">{{ $title->type === 'film' ? 'Film' : 'Série' }}</span></td><td class="px-6 py-4 text-gray-700 text-sm">{{ $title->release_date ? $title->release_date->format('d/m/Y') : '-' }}</td><td class="px-6 py-4 text-gray-700 text-sm">{{ number_format($title->views_count ?? 0) }}</td><td class="px-6 py-4">@if($title->rating)<div class="flex items-center gap-1"><span class="material-symbols-outlined text-yellow-500 text-sm">star</span><span class="font-medium">{{ number_format($title->rating, 1) }}</span></div>@else<span class="text-gray-400">-</span>@endif</td><td class="px-6 py-4">@if($title->status === 'published')<span class="px-2.5 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full border border-green-200">Publié</span>@else<span class="px-2.5 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full border border-gray-200">Brouillon</span>@endif</td><td class="px-6 py-4 text-right"><div class="flex items-center justify-end gap-2"><a href="{{ route('admin.titles.show', $title->id) }}" class="p-1 text-blue-600 hover:bg-blue-50 rounded" title="Voir"><span class="material-symbols-outlined">visibility</span></a><a href="{{ route('admin.titles.edit', $title->id) }}" class="p-1 text-green-600 hover:bg-green-50 rounded" title="Modifier"><span class="material-symbols-outlined">edit</span></a><form action="{{ route('admin.titles.destroy', $title->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ?')">@csrf @method('DELETE')<button type="submit" class="p-1 text-red-600 hover:bg-red-50 rounded" title="Supprimer"><span class="material-symbols-outlined">delete</span></button></form></div></td></tr>
            @empty
            <tr><td colspan="7" class="px-6 py-12 text-center"><div class="flex flex-col items-center"><div class="bg-gray-100 p-4 rounded-full mb-3"><span class="material-symbols-outlined text-3xl text-gray-400">movie</span></div><h3 class="text-lg font-medium text-gray-900">Aucun titre</h3></div></td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">{{ $items->appends(request()->query())->links() }}</div>

<script>
let currentView=localStorage.getItem('titlesViewMode')||'grid';function setView(mode){currentView=mode;localStorage.setItem('titlesViewMode',mode);const gridView=document.getElementById('gridView'),listView=document.getElementById('listView'),gridBtn=document.getElementById('viewGrid'),listBtn=document.getElementById('viewList');if(mode==='grid'){gridView.classList.remove('hidden');listView.classList.add('hidden');gridBtn.classList.add('bg-red-100','text-red-700');gridBtn.classList.remove('bg-gray-100','text-gray-700');listBtn.classList.add('bg-gray-100','text-gray-700');listBtn.classList.remove('bg-red-100','text-red-700')}else{gridView.classList.add('hidden');listView.classList.remove('hidden');listBtn.classList.add('bg-red-100','text-red-700');listBtn.classList.remove('bg-gray-100','text-gray-700');gridBtn.classList.add('bg-gray-100','text-gray-700');gridBtn.classList.remove('bg-red-100','text-red-700')}}window.addEventListener('DOMContentLoaded',()=>{setView(currentView)});
</script>
@endsection
