@extends('layouts.app')

@section('title', 'Médiathèque - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div><h1 class="text-3xl font-bold text-gray-800">Médiathèque</h1><p class="text-gray-600 mt-2">Gestion des assets (images, vidéos, documents)</p></div>
    <div><form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" class="inline">@csrf<input type="file" name="file" id="file" class="hidden" onchange="this.form.submit()"><label for="file" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 cursor-pointer flex items-center shadow-sm transition-colors"><span class="material-symbols-outlined mr-2">upload</span>Uploader</label></form></div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4"><div class="flex items-center justify-between"><div><p class="text-sm text-gray-500">Total fichiers</p><p class="text-2xl font-bold text-gray-900">{{ $assets->total() }}</p></div><div class="bg-blue-50 p-3 rounded-full"><span class="material-symbols-outlined text-blue-600">folder</span></div></div></div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4"><div class="flex items-center justify-between"><div><p class="text-sm text-gray-500">Images</p><p class="text-2xl font-bold text-gray-900">{{ $assets->filter(fn($a) => str_starts_with($a->mime_type, 'image/'))->count() }}</p></div><div class="bg-purple-50 p-3 rounded-full"><span class="material-symbols-outlined text-purple-600">image</span></div></div></div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4"><div class="flex items-center justify-between"><div><p class="text-sm text-gray-500">Vidéos</p><p class="text-2xl font-bold text-gray-900">{{ $assets->filter(fn($a) => str_starts_with($a->mime_type, 'video/'))->count() }}</p></div><div class="bg-green-50 p-3 rounded-full"><span class="material-symbols-outlined text-green-600">videocam</span></div></div></div>
</div>

<div class="bg-white rounded-lg shadow-sm p-4 mb-6 border border-gray-100">
    <form method="GET" action="{{ route('admin.media.index') }}" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative"><span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><span class="material-symbols-outlined text-gray-400">search</span></span><input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors"></div>
        <select name="type" class="w-full md:w-48 px-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors"><option value="">Tous types</option><option value="image" {{ request('type') === 'image' ? 'selected' : '' }}>Images</option><option value="video" {{ request('type') === 'video' ? 'selected' : '' }}>Vidéos</option><option value="document" {{ request('type') === 'document' ? 'selected' : '' }}>Documents</option></select>
        <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-900 transition-colors">Filtrer</button>
    </form>
</div>

<div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @forelse($assets as $asset)
        <div class="border border-gray-200 rounded-lg p-2 hover:shadow-md transition group">
            @if(str_starts_with($asset->mime_type, 'image/'))
                <img src="{{ asset('storage/' . $asset->path) }}" alt="{{ $asset->path }}" class="w-full h-32 object-cover rounded group-hover:opacity-90 transition">
            @else
                <div class="w-full h-32 bg-gray-100 rounded flex items-center justify-center"><span class="material-symbols-outlined text-4xl text-gray-400">description</span></div>
            @endif
            <div class="mt-2"><p class="text-xs text-gray-600 truncate">{{ basename($asset->path) }}</p><p class="text-xs text-gray-400">{{ number_format($asset->size_bytes / 1024, 2) }} KB</p></div>
            <form action="{{ route('admin.media.destroy', $asset->id) }}" method="POST" class="mt-2" onsubmit="return confirm('Supprimer ?');">@csrf @method('DELETE')<button type="submit" class="text-xs text-red-600 hover:text-red-900 flex items-center"><span class="material-symbols-outlined text-sm mr-1">delete</span>Supprimer</button></form>
        </div>
        @empty
        <div class="col-span-full text-center py-12"><div class="flex flex-col items-center"><div class="bg-gray-100 p-4 rounded-full mb-3"><span class="material-symbols-outlined text-3xl text-gray-400">folder_open</span></div><h3 class="text-lg font-medium text-gray-900">Aucun fichier</h3></div></div>
        @endforelse
    </div>
    <div class="mt-6">{{ $assets->links() }}</div>
</div>
@endsection
