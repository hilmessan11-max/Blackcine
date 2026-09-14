@extends('layouts.app')

@section('title', 'Campagnes Newsletter')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-green-50 to-teal-50">
    <!-- Header avec dégradé -->
    <div class="bg-gradient-to-r from-green-600 via-teal-600 to-emerald-600 shadow-lg">
        <div class="container mx-auto px-6 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2 flex items-center">
                        <span class="material-symbols-outlined mr-3 text-3xl">mail</span>
                        Campagnes Newsletter
                    </h1>
                    <p class="text-green-100">Gérez vos campagnes d'emailing</p>
                </div>
                <a href="{{ route('admin.newsletter.campaigns.create') }}" class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg font-semibold transition backdrop-blur-sm shadow-md hover:shadow-lg flex items-center">
                    <span class="material-symbols-outlined mr-2">add</span>
                    Nouvelle Campagne
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="container mx-auto px-6 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="card bg-white shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-3 flex items-center">
                    <span class="material-symbols-outlined mr-2 text-white">campaign</span>
                    <div class="text-white text-sm font-semibold">Total Campagnes</div>
                </div>
                <div class="p-6">
                    <div class="text-4xl font-bold text-blue-600">{{ $stats['total'] }}</div>
                </div>
            </div>

            <div class="card bg-white shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-green-600 px-4 py-3 flex items-center">
                    <span class="material-symbols-outlined mr-2 text-white">send</span>
                    <div class="text-white text-sm font-semibold">Envoyées</div>
                </div>
                <div class="p-6">
                    <div class="text-4xl font-bold text-green-600">{{ $stats['sent'] }}</div>
                </div>
            </div>

            <div class="card bg-white shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-4 py-3 flex items-center">
                    <span class="material-symbols-outlined mr-2 text-white">schedule</span>
                    <div class="text-white text-sm font-semibold">Planifiées</div>
                </div>
                <div class="p-6">
                    <div class="text-4xl font-bold text-purple-600">{{ $stats['scheduled'] }}</div>
                </div>
            </div>

            <div class="card bg-white shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 px-4 py-3 flex items-center">
                    <span class="material-symbols-outlined mr-2 text-white">draft</span>
                    <div class="text-white text-sm font-semibold">Brouillons</div>
                </div>
                <div class="p-6">
                    <div class="text-4xl font-bold text-yellow-600">{{ $stats['draft'] }}</div>
                </div>
            </div>
        </div>

        <!-- Liste des campagnes -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Campagne</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Sujet</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Statistiques</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($campaigns as $campaign)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-green-100 to-teal-100 rounded-lg flex items-center justify-center">
                                            <span class="text-2xl">📧</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900">{{ $campaign->name }}</div>
                                            <div class="text-xs text-gray-500">
                                                Créée le {{ $campaign->created_at->format('d/m/Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $campaign->subject }}</div>
                                    @if($campaign->scheduled_at)
                                        <div class="text-xs text-gray-500 mt-1">
                                            📅 Planifié pour {{ $campaign->scheduled_at->format('d/m/Y H:i') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($campaign->status === 'sent')
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2">
                                                <span class="text-blue-600">👤 {{ number_format($campaign->total_recipients) }} destinataires</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-green-600">📖 {{ number_format($campaign->opened_count) }} ouvertures</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-purple-600">👆 {{ number_format($campaign->clicked_count) }} clics</span>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-400">Pas encore envoyée</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'draft' => 'bg-gray-100 text-gray-800',
                                            'scheduled' => 'bg-purple-100 text-purple-800',
                                            'sending' => 'bg-blue-100 text-blue-800',
                                            'sent' => 'bg-green-100 text-green-800',
                                        ];
                                        $statusLabels = [
                                            'draft' => 'Brouillon',
                                            'scheduled' => 'Planifiée',
                                            'sending' => 'En Envoi',
                                            'sent' => 'Envoyée',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$campaign->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusLabels[$campaign->status] ?? $campaign->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.newsletter.campaigns.show', $campaign) }}" class="text-blue-600 hover:text-blue-800" title="Voir">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        @if(in_array($campaign->status, ['draft', 'scheduled']))
                                            <a href="{{ route('admin.newsletter.campaigns.edit', $campaign) }}" class="text-indigo-600 hover:text-indigo-800" title="Modifier">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                        @endif
                                        <form action="{{ route('admin.newsletter.campaigns.destroy', $campaign) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800" title="Supprimer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <p class="text-lg text-gray-500 mb-2">Aucune campagne newsletter</p>
                                        <a href="{{ route('admin.newsletter.campaigns.create') }}" class="text-green-600 hover:text-green-800 font-semibold">
                                            Créer votre première campagne →
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($campaigns->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $campaigns->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
