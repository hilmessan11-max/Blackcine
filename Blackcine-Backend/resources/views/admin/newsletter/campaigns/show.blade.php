@extends('layouts.app')

@section('title', 'Détails de la Campagne - ' . $campaign->name)

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $campaign->name }}</h1>
            <p class="text-gray-600 mt-2">Campagne Newsletter</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.newsletter.campaigns.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                ← Retour
            </a>
            <a href="{{ route('admin.newsletter.campaigns.edit', $campaign) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                ✏️ Modifier
            </a>
        </div>
    </div>
</div>

<!-- Statistiques principales -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm">Envoyés</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($campaign->sent_count ?? 0) }}</p>
            </div>
            <div class="text-4xl opacity-50">📧</div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm">Ouvertures</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($campaign->opened_count ?? 0) }}</p>
            </div>
            <div class="text-4xl opacity-50">👁️</div>
        </div>
        <p class="text-green-100 text-xs mt-2">
            {{ $campaign->sent_count > 0 ? number_format(($campaign->opened_count / $campaign->sent_count) * 100, 1) : 0 }}% taux d'ouverture
        </p>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm">Clics</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($campaign->clicked_count ?? 0) }}</p>
            </div>
            <div class="text-4xl opacity-50">🖱️</div>
        </div>
        <p class="text-purple-100 text-xs mt-2">
            {{ $campaign->sent_count > 0 ? number_format(($campaign->clicked_count / $campaign->sent_count) * 100, 1) : 0 }}% taux de clic
        </p>
    </div>

    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm">Statut</p>
                <p class="text-2xl font-bold mt-1 capitalize">
                    @switch($campaign->status)
                        @case('draft') 📝 Brouillon @break
                        @case('scheduled') ⏰ Programmée @break
                        @case('sending') 🚀 En cours @break
                        @case('sent') ✅ Envoyée @break
                        @case('paused') ⏸️ Pause @break
                        @default {{ $campaign->status }}
                    @endswitch
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Informations détaillées -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Informations principales -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">📋 Informations principales</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-500">Sujet</label>
                    <p class="text-gray-900 mt-1">{{ $campaign->subject }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Nom expéditeur</label>
                        <p class="text-gray-900 mt-1">{{ $campaign->from_name ?? 'Non défini' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Email expéditeur</label>
                        <p class="text-gray-900 mt-1">{{ $campaign->from_email ?? 'Non défini' }}</p>
                    </div>
                </div>

                @if($campaign->preview_text)
                <div>
                    <label class="text-sm font-medium text-gray-500">Texte de prévisualisation</label>
                    <p class="text-gray-700 mt-1 italic">{{ $campaign->preview_text }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Aperçu du contenu -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">📄 Aperçu du contenu</h3>
            
            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 max-h-96 overflow-auto">
                {!! $campaign->body !!}
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Planning -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">📅 Planning</h3>
            
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-500">Créée le</label>
                    <p class="text-gray-900 mt-1">{{ $campaign->created_at->format('d/m/Y à H:i') }}</p>
                </div>

                @if($campaign->scheduled_at)
                <div>
                    <label class="text-sm font-medium text-gray-500">Programmée pour</label>
                    <p class="text-gray-900 mt-1">{{ $campaign->scheduled_at->format('d/m/Y à H:i') }}</p>
                </div>
                @endif

                @if($campaign->sent_at)
                <div>
                    <label class="text-sm font-medium text-gray-500">Envoyée le</label>
                    <p class="text-gray-900 mt-1">{{ $campaign->sent_at->format('d/m/Y à H:i') }}</p>
                </div>
                @endif

                <div>
                    <label class="text-sm font-medium text-gray-500">Dernière mise à jour</label>
                    <p class="text-gray-900 mt-1">{{ $campaign->updated_at->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">⚡ Actions rapides</h3>
            
            <div class="space-y-3">
                @if($campaign->status == 'draft' || $campaign->status == 'scheduled')
                <form action="{{ route('admin.newsletter.campaigns.send', $campaign) }}" method="POST" 
                    onsubmit="return confirm('Envoyer cette campagne maintenant à tous les abonnés ?');">
                    @csrf
                    <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        📧 Envoyer maintenant
                    </button>
                </form>
                @endif

                <form action="{{ route('admin.newsletter.campaigns.duplicate', $campaign) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        📋 Dupliquer
                    </button>
                </form>

                <form action="{{ route('admin.newsletter.campaigns.destroy', $campaign) }}" method="POST" 
                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette campagne ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        🗑️ Supprimer
                    </button>
                </form>
            </div>
        </div>

        <!-- Performances détaillées -->
        @if($campaign->status == 'sent')
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">📊 Performances</h3>
            
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Taux d'ouverture</span>
                    <span class="font-semibold text-green-600">
                        {{ $campaign->sent_count > 0 ? number_format(($campaign->opened_count / $campaign->sent_count) * 100, 1) : 0 }}%
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full" 
                        style="width: {{ $campaign->sent_count > 0 ? ($campaign->opened_count / $campaign->sent_count) * 100 : 0 }}%">
                    </div>
                </div>

                <div class="flex justify-between items-center mt-4">
                    <span class="text-sm text-gray-600">Taux de clic</span>
                    <span class="font-semibold text-purple-600">
                        {{ $campaign->sent_count > 0 ? number_format(($campaign->clicked_count / $campaign->sent_count) * 100, 1) : 0 }}%
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-purple-600 h-2 rounded-full" 
                        style="width: {{ $campaign->sent_count > 0 ? ($campaign->clicked_count / $campaign->sent_count) * 100 : 0 }}%">
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
