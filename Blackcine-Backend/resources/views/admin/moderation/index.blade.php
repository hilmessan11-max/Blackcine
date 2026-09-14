@extends('layouts.app')

@section('title', 'Modération - BlackCine Admin')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Modération</h1>
    <p class="text-gray-600 mt-2">Vue d'ensemble de la modération</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium uppercase">Commentaires en attente</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $pendingComments }}</p>
            </div>
            <div class="bg-yellow-100 p-3 rounded-full">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('admin.moderation.comments', ['status' => 'pending']) }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Gérer les commentaires &rarr;</a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium uppercase">Signalements en attente</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $pendingReports }}</p>
            </div>
            <div class="bg-red-100 p-3 rounded-full">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('admin.moderation.reports', ['status' => 'pending']) }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Gérer les signalements &rarr;</a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium uppercase">Logs d'activité</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">Logs</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('admin.moderation.logs') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Voir l'historique &rarr;</a>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Dernières activités</h3>
    </div>
    <div class="divide-y divide-gray-200">
        @forelse($recentLogs as $log)
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-500">
                                <span class="text-xs font-medium leading-none text-white">{{ substr($log->user->name ?? 'S', 0, 2) }}</span>
                            </span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">{{ $log->user->name ?? 'Système' }}</p>
                            <p class="text-sm text-gray-500">{{ $log->description }}</p>
                        </div>
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ $log->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>
        @empty
            <div class="px-6 py-4 text-center text-gray-500">
                Aucune activité récente.
            </div>
        @endforelse
    </div>
</div>
@endsection
