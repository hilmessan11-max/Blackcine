@extends('layouts.app')

@section('title', 'Box-Office - BlackCine Admin')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Box-Office</h1>
        <p class="text-gray-600 mt-2">Vue d'ensemble des séances, cinémas, commandes et billets.</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.boxoffice.showtimes.index') }}" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 flex items-center shadow-sm transition-colors">
            <span class="material-symbols-outlined mr-2">schedule</span>
            Séances
        </a>
        <a href="{{ route('admin.boxoffice.orders.index') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center shadow-sm transition-colors">
            <span class="material-symbols-outlined mr-2">receipt_long</span>
            Commandes
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-7 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 xl:col-span-1">
        <p class="text-sm text-gray-500">Séances</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['showtimes'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 xl:col-span-1">
        <p class="text-sm text-gray-500">À venir</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['upcoming_showtimes'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 xl:col-span-1">
        <p class="text-sm text-gray-500">Cinémas</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['cinemas'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 xl:col-span-1">
        <p class="text-sm text-gray-500">Actifs</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['active_cinemas'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 xl:col-span-1">
        <p class="text-sm text-gray-500">Commandes</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['orders'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 xl:col-span-1">
        <p class="text-sm text-gray-500">Billets</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['tickets'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 xl:col-span-1">
        <p class="text-sm text-gray-500">Revenu encaissé</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['revenue_cents'] / 100, 2, ',', ' ') }} €</p>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-900">Séances récentes</h2>
            <a href="{{ route('admin.boxoffice.showtimes.index') }}" class="text-sm text-red-600 hover:text-red-700">Voir tout</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentShowtimes as $showtime)
                <div class="px-5 py-4">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="font-medium text-gray-900">{{ $showtime->title?->title ?? $showtime->title?->name ?? 'Séance' }}</p>
                            <p class="text-sm text-gray-500">{{ $showtime->room?->cinema?->name ?? 'Cinéma inconnu' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-900">{{ optional($showtime->starts_at)->format('d/m/Y H:i') }}</p>
                            <p class="text-xs text-gray-500">{{ ucfirst($showtime->status) }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-5 py-8 text-center text-sm text-gray-500">Aucune séance enregistrée.</div>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-900">Commandes récentes</h2>
            <a href="{{ route('admin.boxoffice.orders.index') }}" class="text-sm text-red-600 hover:text-red-700">Voir tout</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentOrders as $order)
                <div class="px-5 py-4">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="font-medium text-gray-900">Commande #{{ $order->id }}</p>
                            <p class="text-sm text-gray-500">{{ $order->email }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-900">{{ number_format(($order->total_cents ?? 0) / 100, 2, ',', ' ') }} €</p>
                            <p class="text-xs text-gray-500">{{ ucfirst($order->status) }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-5 py-8 text-center text-sm text-gray-500">Aucune commande enregistrée.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
