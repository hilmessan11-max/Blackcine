@extends('layouts.app')

@section('title', 'Gestion des Campagnes Publicitaires - BlackCine')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800 flex items-center">
            <span class="material-symbols-outlined mr-3 text-3xl text-red-600">campaign</span>
            Campagnes Publicitaires
        </h1>
        <p class="text-gray-600 mt-2">Gérez vos campagnes publicitaires</p>
    </div>
    <a href="{{ route('admin.advertising.campaigns.create') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition shadow-md hover:shadow-lg flex items-center">
        <span class="material-symbols-outlined mr-2">add</span>
        Nouvelle Campagne
    </a>
</div>

<!-- Statistiques rapides -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="card bg-white shadow-lg p-6 border-l-4 border-green-500">
        <div class="text-gray-600 text-sm uppercase font-semibold flex items-center mb-2">
            <span class="material-symbols-outlined mr-1 text-base text-green-500">check_circle</span>
            Actives
        </div>
        <div class="text-3xl font-bold text-gray-900">{{ $stats['active'] }}</div>
    </div>
    <div class="card bg-white shadow-lg p-6 border-l-4 border-blue-500">
        <div class="text-gray-600 text-sm uppercase font-semibold flex items-center mb-2">
            <span class="material-symbols-outlined mr-1 text-base text-blue-500">visibility</span>
            Impressions
        </div>
        <div class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_impressions']) }}</div>
    </div>
    <div class="card bg-white shadow-lg p-6 border-l-4 border-yellow-500">
        <div class="text-gray-600 text-sm uppercase font-semibold flex items-center mb-2">
            <span class="material-symbols-outlined mr-1 text-base text-yellow-500">ads_click</span>
            Clics
        </div>
        <div class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_clicks']) }}</div>
    </div>
    <div class="card bg-white shadow-lg p-6 border-l-4 border-purple-500">
        <div class="text-gray-600 text-sm uppercase font-semibold flex items-center mb-2">
            <span class="material-symbols-outlined mr-1 text-base text-purple-500">payments</span>
            Revenus (Est.)
        </div>
        <div class="text-3xl font-bold text-gray-900">{{ number_format($stats['revenue'] / 100, 2) }} €</div>
    </div>
</div>

<!-- Liste des campagnes -->
<div class="card bg-white shadow-lg overflow-hidden">
    <div class="table-container">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campagne</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type / Placement</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stats</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($campaigns as $campaign)
                <tr class="table-row">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                @if($campaign->type == 'video')
                                    <span class="material-symbols-outlined text-gray-500">play_circle</span>
                                @elseif($campaign->type == 'sponsored_post')
                                    <span class="material-symbols-outlined text-gray-500">article</span>
                                @else
                                    <span class="material-symbols-outlined text-gray-500">image</span>
                                @endif
                            </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $campaign->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $campaign->client_name ?? 'Client inconnu' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ ucfirst($campaign->type) }}</div>
                                <div class="text-xs text-gray-500">{{ $campaign->placement }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $campaign->start_date ? $campaign->start_date->format('d/m/Y') : 'N/A' }}
                                    <span class="text-gray-400">→</span>
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $campaign->end_date ? $campaign->end_date->format('d/m/Y') : 'Indéfini' }}
                                </div>
                            </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div class="flex items-center mb-1">
                            <span class="material-symbols-outlined mr-1 text-base text-gray-400">visibility</span>
                            {{ number_format($campaign->impressions_count) }}
                        </div>
                        <div class="flex items-center">
                            <span class="material-symbols-outlined mr-1 text-base text-gray-400">ads_click</span>
                            {{ number_format($campaign->clicks_count) }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full flex items-center bg-{{ $campaign->status_color }}-100 text-{{ $campaign->status_color }}-800">
                            <span class="material-symbols-outlined text-xs mr-1">
                                {{ $campaign->status === 'active' ? 'play_circle' : ($campaign->status === 'paused' ? 'pause_circle' : 'stop_circle') }}
                            </span>
                            {{ ucfirst($campaign->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.advertising.campaigns.edit', $campaign) }}" class="text-blue-600 hover:text-blue-800 transition-colors" title="Modifier">
                                <span class="material-symbols-outlined text-xl">edit</span>
                            </a>
                            <form action="{{ route('admin.advertising.campaigns.destroy', $campaign) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 transition-colors" title="Supprimer">
                                    <span class="material-symbols-outlined text-xl">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <span class="material-symbols-outlined text-6xl text-gray-400 mb-4 block">campaign</span>
                        <p class="text-lg">Aucune campagne publicitaire trouvée</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $campaigns->links() }}
    </div>
</div>
    </div>
</div>
@endsection
