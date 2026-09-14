@extends('layouts.app')

@section('title', 'Critiques Éditoriales - ' . $title->name)

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div><h1 class="text-3xl font-bold text-gray-800">Critiques Éditoriales</h1><p class="text-gray-600 mt-2">{{ $title->name }}</p></div>
    <div class="flex gap-3"><a href="{{ route('admin.titles.reviews.editorial.create', $title->id) }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center shadow-sm transition-colors"><span class="material-symbols-outlined mr-2">add_circle</span>Nouvelle Critique</a><a href="{{ route('admin.titles.show', $title->id) }}" class="text-gray-600 hover:text-gray-900 flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors"><span class="material-symbols-outlined mr-1">arrow_back</span>Retour</a></div>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4"><div class="flex items-center justify-between"><div><p class="text-sm text-gray-500">Total</p><p class="text-2xl font-bold text-gray-900">{{ $editorialReviews->count() }}</p></div><div class="bg-blue-50 p-3 rounded-full"><span class="material-symbols-outlined text-blue-600">rate_review</span></div></div></div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4"><div class="flex items-center justify-between"><div><p class="text-sm text-gray-500">Publiées</p><p class="text-2xl font-bold text-gray-900">{{ $editorialReviews->where('status', 'published')->count() }}</p></div><div class="bg-green-50 p-3 rounded-full"><span class="material-symbols-outlined text-green-600">check_circle</span></div></div></div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4"><div class="flex items-center justify-between"><div><p class="text-sm text-gray-500">Mises en avant</p><p class="text-2xl font-bold text-gray-900">{{ $editorialReviews->where('is_featured', true)->count() }}</p></div><div class="bg-yellow-50 p-3 rounded-full"><span class="material-symbols-outlined text-yellow-600">star</span></div></div></div>
</div>

<!-- Liste -->
<div class="space-y-4">
    @forelse($editorialReviews as $review)
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
        <div class="p-6">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <div class="flex items-center flex-wrap gap-2 mb-3">
                        @if($review->is_featured)<span class="px-2.5 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full border border-yellow-200 flex items-center"><span class="material-symbols-outlined text-xs mr-1">star</span>Mise en avant</span>@endif
                        <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $review->status === 'published' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-gray-100 text-gray-800 border border-gray-200' }}">{{ ucfirst($review->status) }}</span>
                        @if($review->rating)<div class="flex items-center gap-1 px-2.5 py-1 bg-yellow-50 border border-yellow-200 rounded-full"><span class="material-symbols-outlined text-yellow-500 text-sm">star</span><span class="text-sm font-bold text-yellow-700">{{ $review->rating }}/10</span></div>@endif
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $review->headline }}</h3>
                    <div class="text-gray-700 mb-4 text-sm">{{ Str::limit($review->content, 300) }}</div>
                    <div class="flex items-center flex-wrap gap-3 text-sm text-gray-600">
                        <div class="flex items-center gap-2"><div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center text-white text-xs font-bold">{{ strtoupper(substr($review->author->name, 0, 1)) }}</div><span>{{ $review->author->name }}</span></div>
                        @if($review->critic_name)<span>• Critique: <strong>{{ $review->critic_name }}</strong></span>@endif
                        @if($review->publication)<span>• {{ $review->publication }}</span>@endif
                        @if($review->publication_date)<span>• {{ $review->publication_date->format('d/m/Y') }}</span>@endif
                    </div>
                    @if($review->external_url)<div class="mt-3"><a href="{{ $review->external_url }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm flex items-center gap-1"><span>Lire la critique complète</span><span class="material-symbols-outlined text-sm">open_in_new</span></a></div>@endif
                </div>
                <div class="flex flex-col gap-2 ml-4">
                    <a href="{{ route('admin.titles.reviews.editorial.edit', ['titleId' => $title->id, 'reviewId' => $review->id]) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded transition" title="Modifier"><span class="material-symbols-outlined">edit</span></a>
                    <form action="{{ route('admin.titles.reviews.editorial.toggle-featured', ['titleId' => $title->id, 'reviewId' => $review->id]) }}" method="POST"><button type="submit" class="p-2 {{ $review->is_featured ? 'text-gray-600 hover:bg-gray-50' : 'text-yellow-600 hover:bg-yellow-50' }} rounded transition" title="{{ $review->is_featured ? 'Retirer' : 'Mettre en avant' }}">@csrf<span class="material-symbols-outlined">{{ $review->is_featured ? 'star_half' : 'star' }}</span></button></form>
                    <form action="{{ route('admin.titles.reviews.editorial.destroy', ['titleId' => $title->id, 'reviewId' => $review->id]) }}" method="POST" onsubmit="return confirm('Supprimer ?');">@csrf @method('DELETE')<button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded transition" title="Supprimer"><span class="material-symbols-outlined">delete</span></button></form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-12 text-center"><div class="bg-gray-100 p-4 rounded-full mb-3 inline-block"><span class="material-symbols-outlined text-3xl text-gray-400">rate_review</span></div><h3 class="text-lg font-medium text-gray-900">Aucune critique</h3><p class="text-gray-500 mt-1">Créez votre première critique éditoriale</p><a href="{{ route('admin.titles.reviews.editorial.create', $title->id) }}" class="mt-4 inline-block text-red-600 hover:text-red-700 font-medium">Créer une critique →</a></div>
    @endforelse
</div>
@endsection
