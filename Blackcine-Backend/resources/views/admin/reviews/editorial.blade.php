@extends('layouts.app')

@section('title', 'Critiques Éditoriales')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">✍️ Critiques Éditoriales</h2>
        <div class="text-sm text-gray-600">
            Total : <span class="font-semibold text-red-600">{{ $editorialReviews->total() }}</span> critiques
        </div>
    </div>

    @if($editorialReviews->isEmpty())
        <div class="text-center py-12">
            <p class="text-gray-500 text-lg">Aucune critique éditoriale pour le moment.</p>
        </div>
    @else
        <div class="grid gap-6">
            @foreach($editorialReviews as $review)
                <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $review->headline }}</h3>
                            <div class="flex items-center space-x-4 text-sm text-gray-600">
                                <span class="flex items-center">
                                    🎬 <a href="{{ route('admin.titles.show', $review->title_id) }}" class="ml-1 text-red-600 hover:text-red-800 font-medium">
                                        {{ $review->title->name ?? 'N/A' }}
                                    </a>
                                </span>
                                @if($review->rating)
                                    <span class="flex items-center">
                                        <span class="text-yellow-400">★</span>
                                        <span class="ml-1 font-semibold">{{ $review->rating }}/10</span>
                                    </span>
                                @endif
                                @if($review->critic_name)
                                    <span>✒️ {{ $review->critic_name }}</span>
                                @endif
                                @if($review->publication)
                                    <span>📰 {{ $review->publication }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($review->is_featured)
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">
                                    ⭐ En vedette
                                </span>
                            @endif
                            @if($review->status === 'published')
                                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                    Publié
                                </span>
                            @elseif($review->status === 'draft')
                                <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">
                                    Brouillon
                                </span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">
                                    Archivé
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="text-gray-700 mb-4 line-clamp-3">
                        {{ Str::limit($review->content, 300) }}
                    </div>

                    <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                        <div class="text-sm text-gray-500">
                            <span>Par {{ $review->author->name ?? 'N/A' }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $review->created_at->format('d/m/Y') }}</span>
                            @if($review->published_at)
                                <span class="mx-2">•</span>
                                <span>Publié le {{ $review->published_at->format('d/m/Y') }}</span>
                            @endif
                            @if($review->external_url)
                                <span class="mx-2">•</span>
                                <a href="{{ $review->external_url }}" target="_blank" class="text-red-600 hover:text-red-800">🔗 Lien externe</a>
                            @endif
                        </div>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('admin.titles.reviews.editorial.edit', [$review->title_id, $review->id]) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                ✏️ Modifier
                            </a>
                            <form method="POST" action="{{ route('admin.titles.reviews.editorial.toggle-featured', $review->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-yellow-600 hover:text-yellow-800 text-sm font-medium">
                                    {{ $review->is_featured ? '⭐ Retirer vedette' : '⭐ Mettre en vedette' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.titles.reviews.editorial.destroy', [$review->title_id, $review->id]) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette critique ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    ✕ Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $editorialReviews->links() }}
        </div>
    @endif
</div>
@endsection
