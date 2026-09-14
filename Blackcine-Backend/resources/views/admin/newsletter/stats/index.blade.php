@extends('layouts.app')

@section('title', 'Statistiques Newsletter - BlackCine Admin')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Statistiques Newsletter</h1>
    <p class="text-gray-600 mt-2">Analyse des performances de vos campagnes</p>
</div>

<!-- KPIs principaux -->
<div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm">Total Campagnes</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($stats['total_campaigns']) }}</p>
            </div>
            <div class="text-4xl opacity-50">📧</div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm">Campagnes Envoyées</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($stats['total_sent']) }}</p>
            </div>
            <div class="text-4xl opacity-50">✅</div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm">Abonnés Actifs</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($stats['total_subscribers']) }}</p>
            </div>
            <div class="text-4xl opacity-50">👥</div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm">Taux Ouverture Moyen</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($stats['avg_open_rate'] ?? 0, 1) }}%</p>
            </div>
            <div class="text-4xl opacity-50">👁️</div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-pink-500 to-pink-600 rounded-lg p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-pink-100 text-sm">Taux Clic Moyen</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($stats['avg_click_rate'] ?? 0, 1) }}%</p>
            </div>
            <div class="text-4xl opacity-50">🖱️</div>
        </div>
    </div>
</div>

<!-- Campagnes récentes -->
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">📊 Campagnes Récentes</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campagne</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date d'envoi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Envoyés</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ouvertures</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clics</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Taux Ouv.</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Taux Clic</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($recentCampaigns as $campaign)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $campaign->name }}</div>
                        <div class="text-sm text-gray-500">{{ Str::limit($campaign->subject, 50) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $campaign->sent_at?->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ number_format($campaign->sent_count ?? 0) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ number_format($campaign->opened_count ?? 0) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ number_format($campaign->clicked_count ?? 0) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $openRate = $campaign->sent_count > 0 ? ($campaign->opened_count / $campaign->sent_count) * 100 : 0;
                        @endphp
                        <div class="flex items-center">
                            <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ $openRate }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-700">{{ number_format($openRate, 1) }}%</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $clickRate = $campaign->sent_count > 0 ? ($campaign->clicked_count / $campaign->sent_count) * 100 : 0;
                        @endphp
                        <div class="flex items-center">
                            <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $clickRate }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-700">{{ number_format($clickRate, 1) }}%</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="{{ route('admin.newsletter.campaigns.show', $campaign) }}" class="text-blue-600 hover:text-blue-900">
                            Voir détails
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                        Aucune campagne envoyée pour le moment
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t border-gray-200">
        <a href="{{ route('admin.newsletter.campaigns.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
            → Voir toutes les campagnes
        </a>
    </div>
</div>
@endsection
