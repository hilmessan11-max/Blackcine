@extends('layouts.app')

@section('title', 'Avis Utilisateurs - ' . $title->name)

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Avis Utilisateurs</h1>
            <p class="text-gray-600 mt-2">{{ $title->name }}</p>
        </div>
        <a href="{{ route('admin.titles.show', $title->id) }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400">
            ← Retour au titre
        </a>
    </div>
</div>

<!-- Statistiques -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Moyenne</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($title->average_rating, 1) }}</p>
                <div class="flex items-center mt-1">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= round($title->average_rating) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    @endfor
                </div>
            </div>
            <span class="text-4xl">⭐</span>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Avis</p>
                <p class="text-3xl font-bold text-gray-900">{{ $reviews->total() }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $title->total_reviews }} approuvés</p>
            </div>
            <span class="text-4xl">💬</span>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">En attente</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $title->reviews()->where('status', 'pending')->count() }}</p>
            </div>
            <span class="text-4xl">⏱️</span>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Rejetés</p>
                <p class="text-3xl font-bold text-red-600">{{ $title->reviews()->where('status', 'rejected')->count() }}</p>
            </div>
            <span class="text-4xl">❌</span>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" class="flex gap-4">
        <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg" onchange="this.form.submit()">
            <option value="">Tous les statuts</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvés</option>
            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejetés</option>
        </select>
        <select name="rating" class="px-4 py-2 border border-gray-300 rounded-lg" onchange="this.form.submit()">
            <option value="">Toutes les notes</option>
            <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 étoiles</option>
            <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 étoiles</option>
            <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 étoiles</option>
            <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 étoiles</option>
            <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 étoile</option>
        </select>
    </form>
</div>

<!-- Liste des avis -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="divide-y divide-gray-200">
        @forelse($reviews as $review)
            <div class="p-6 hover:bg-gray-50">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($review->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $review->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $review->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-2 mb-2">
                            <div class="flex">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endfor
                            </div>
                            
                            @if($review->is_verified_purchase)
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">✓ Achat vérifié</span>
                            @endif
                            
                            @if($review->contains_spoiler)
                                <span class="px-2 py-1 bg-orange-100 text-orange-800 text-xs rounded-full">⚠️ Spoiler</span>
                            @endif
                            
                            <span class="px-2 py-1 text-xs rounded-full {{ $review->status === 'approved' ? 'bg-green-100 text-green-800' : ($review->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($review->status) }}
                            </span>
                        </div>

                        @if($review->title)
                            <h3 class="font-semibold text-gray-900 mb-2">{{ $review->title }}</h3>
                        @endif
                        
                        @if($review->content)
                            <p class="text-gray-700 mb-3">{{ $review->content }}</p>
                        @endif

                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                            <span>👍 {{ $review->helpful_count }} utile(s)</span>
                            <span>👎 {{ $review->not_helpful_count }} non utile(s)</span>
                            @if($review->helpful_count + $review->not_helpful_count > 0)
                                <span class="text-green-600 font-medium">{{ $review->helpful_percentage }}% trouvent ça utile</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col space-y-2 ml-4">
                        @if($review->status === 'pending')
                            <form action="{{ route('admin.titles.reviews.approve', ['titleId' => $title->id, 'reviewId' => $review->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm whitespace-nowrap">
                                    ✓ Approuver
                                </button>
                            </form>
                            <form action="{{ route('admin.titles.reviews.reject', ['titleId' => $title->id, 'reviewId' => $review->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 text-sm whitespace-nowrap">
                                    ✗ Rejeter
                                </button>
                            </form>
                        @endif
                        
                        <form action="{{ route('admin.titles.reviews.destroy', ['titleId' => $title->id, 'reviewId' => $review->id]) }}" method="POST" onsubmit="return confirm('Supprimer cet avis ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 text-sm whitespace-nowrap">
                                🗑️ Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <p class="mt-4 text-lg text-gray-600">Aucun avis trouvé</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($reviews->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $reviews->links() }}
        </div>
    @endif
</div>
@endsection
