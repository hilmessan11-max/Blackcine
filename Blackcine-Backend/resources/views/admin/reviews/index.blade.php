@extends('layouts.app')

@section('title', 'Avis Utilisateurs - BlackCiné Admin')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Avis Utilisateurs</h1>
    <p class="text-gray-600 mt-2">Modérez et gérez tous les avis</p>
</div>

<!-- Stats rapides -->
<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total</p>
                <p class="text-2xl font-bold text-gray-900">{{ $reviews->total() }}</p>
            </div>
            <div class="bg-yellow-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-yellow-600">star</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">En attente</p>
                <p class="text-2xl font-bold text-gray-900">{{ $reviews->where('status', 'pending')->count() }}</p>
            </div>
            <div class="bg-orange-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-orange-600">schedule</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Approuvés</p>
                <p class="text-2xl font-bold text-gray-900">{{ $reviews->where('status', 'approved')->count() }}</p>
            </div>
            <div class="bg-green-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-green-600">check_circle</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Note moyenne</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($reviews->avg('rating') ?? 0, 1) }}/5</p>
            </div>
            <div class="bg-yellow-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-yellow-600">grade</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Aujourd'hui</p>
                <p class="text-2xl font-bold text-gray-900">{{ $reviews->where('created_at', '>=', now()->startOfDay())->count() }}</p>
            </div>
            <div class="bg-blue-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-blue-600">today</span>
            </div>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="bg-white rounded-lg shadow-sm p-4 mb-6 border border-gray-100">
    <form method="GET" action="{{ route('admin.reviews.index') }}" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="material-symbols-outlined text-gray-400">search</span>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..." 
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
        </div>
        <div class="w-full md:w-48">
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
                <option value="">Tous les statuts</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approuvés</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejetés</option>
            </select>
        </div>
        <div class="w-full md:w-48">
            <select name="rating" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
                <option value="">Toutes les notes</option>
                <option value="5" {{ request('rating') === '5' ? 'selected' : '' }}>5 étoiles</option>
                <option value="4" {{ request('rating') === '4' ? 'selected' : '' }}>4 étoiles</option>
                <option value="3" {{ request('rating') === '3' ? 'selected' : '' }}>3 étoiles</option>
                <option value="2" {{ request('rating') === '2' ? 'selected' : '' }}>2 étoiles</option>
                <option value="1" {{ request('rating') === '1' ? 'selected' : '' }}>1 étoile</option>
            </select>
        </div>
        <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-900 transition-colors">
            Filtrer
        </button>
    </form>
</div>

<!-- Liste des avis -->
<div class="space-y-4">
    @forelse($reviews as $review)
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all">
        <div class="p-6">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-4 flex-1">
                    <!-- Avatar -->
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center text-white font-bold text-lg shadow-md">
                            {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                        </div>
                    </div>

                    <!-- Contenu -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 mb-2 flex-wrap">
                            <h3 class="font-bold text-gray-900">{{ $review->user->name ?? 'Utilisateur' }}</h3>
                            
                            <!-- Étoiles -->
                            <div class="flex items-center gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="material-symbols-outlined text-base {{ $i <= ($review->rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}">star</span>
                                @endfor
                                <span class="ml-1 text-sm font-semibold text-gray-700">{{ $review->rating ?? 0 }}/5</span>
                            </div>
                            
                            <!-- Badge statut -->
                            @if($review->status == 'approved')
                            <span class="px-2.5 py-0.5 bg-green-100 text-green-700 text-xs rounded-full font-medium border border-green-200 flex items-center">
                                <span class="material-symbols-outlined text-xs mr-1">check_circle</span>
                                Approuvé
                            </span>
                            @elseif($review->status == 'pending')
                            <span class="px-2.5 py-0.5 bg-orange-100 text-orange-700 text-xs rounded-full font-medium border border-orange-200 flex items-center">
                                <span class="material-symbols-outlined text-xs mr-1">schedule</span>
                                En attente
                            </span>
                            @else
                            <span class="px-2.5 py-0.5 bg-red-100 text-red-700 text-xs rounded-full font-medium border border-red-200 flex items-center">
                                <span class="material-symbols-outlined text-xs mr-1">cancel</span>
                                Rejeté
                            </span>
                            @endif
                        </div>

                        <p class="text-gray-700 mb-3 line-clamp-3">{{ $review->content ?? $review->title ?? 'Pas de commentaire' }}</p>

                        <div class="flex items-center gap-4 text-sm text-gray-500 flex-wrap">
                            @if($review->reviewable)
                            <a href="{{ route('admin.titles.show', $review->reviewable_id) }}" class="text-red-600 hover:text-red-800 font-medium flex items-center">
                                <span class="material-symbols-outlined text-sm mr-1">movie</span>
                                {{ $review->reviewable->name ?? 'Titre'  }}
                            </a>
                            @else
                            <span class="flex items-center">
                                <span class="material-symbols-outlined text-sm mr-1">movie</span>
                                Titre supprimé
                            </span>
                            @endif
                            <span>•</span>
                            <span class="flex items-center">
                                <span class="material-symbols-outlined text-sm mr-1">calendar_today</span>
                                {{ $review->created_at->format('d/m/Y à H:i') }}
                            </span>
                            @if($review->verified_purchase ?? false)
                            <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs rounded-full font-medium flex items-center">
                                <span class="material-symbols-outlined text-xs mr-1">verified</span>
                                Achat vérifié
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col gap-2 flex-shrink-0">
                    @if($review->status == 'pending' || $review->status != 'approved')
                    <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition text-sm font-medium whitespace-nowrap flex items-center">
                            <span class="material-symbols-outlined text-sm mr-1">check_circle</span>
                            Approuver
                        </button>
                    </form>
                    @endif
                    @if($review->status == 'pending' || $review->status != 'rejected')
                    <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition text-sm font-medium whitespace-nowrap flex items-center">
                            <span class="material-symbols-outlined text-sm mr-1">cancel</span>
                            Rejeter
                        </button>
                    </form>
                    @endif
                    <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Supprimer cet avis ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 transition text-sm font-medium whitespace-nowrap flex items-center">
                            <span class="material-symbols-outlined text-sm mr-1">delete</span>
                            Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-12 text-center">
        <div class="bg-gray-100 p-4 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
            <span class="material-symbols-outlined text-3xl text-gray-400">star</span>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Aucun avis trouvé</h3>
        <p class="text-gray-500">Aucun avis utilisateur pour le moment</p>
    </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $reviews->links() }}
</div>
@endsection
