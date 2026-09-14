@extends('layouts.app')

@section('title', 'Modifier la Campagne Newsletter - BlackCine Admin')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Modifier la Campagne Newsletter</h1>
            <p class="text-gray-600 mt-2">{{ $campaign->name }}</p>
        </div>
        <a href="{{ route('admin.newsletter.campaigns.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
            ← Retour
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow">
    <form action="{{ route('admin.newsletter.campaigns.update', $campaign) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Informations principales -->
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations principales</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nom de la campagne *</label>
                    <input type="text" name="name" value="{{ old('name', $campaign->name) }}" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sujet de l'email *</label>
                    <input type="text" name="subject" value="{{ old('subject', $campaign->subject) }}" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nom de l'expéditeur</label>
                    <input type="text" name="from_name" value="{{ old('from_name', $campaign->from_name) }}" 
                        placeholder="BlackCine" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email de l'expéditeur</label>
                    <input type="email" name="from_email" value="{{ old('from_email', $campaign->from_email) }}" 
                        placeholder="newsletter@blackcine.com" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
            </div>
        </div>

        <!-- Contenu -->
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Contenu</h3>
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Texte de prévisualisation</label>
                    <input type="text" name="preview_text" value="{{ old('preview_text', $campaign->preview_text) }}" 
                        placeholder="Ce texte apparaît dans l'aperçu de l'email..." 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <p class="mt-1 text-xs text-gray-500">Apparaît dans la boîte de réception avant l'ouverture</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Corps de l'email (HTML) *</label>
                    <textarea name="body" required rows="12" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md font-mono text-sm">{{ old('body', $campaign->body) }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Utilisez HTML pour formater votre email</p>
                </div>
            </div>
        </div>

        <!-- Planification -->
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Planification & Statut</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Statut *</label>
                    <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="draft" {{ old('status', $campaign->status) == 'draft' ? 'selected' : '' }}>Brouillon</option>
                        <option value="scheduled" {{ old('status', $campaign->status) == 'scheduled' ? 'selected' : '' }}>Programmée</option>
                        <option value="sending" {{ old('status', $campaign->status) == 'sending' ? 'selected' : '' }}>En cours d'envoi</option>
                        <option value="sent" {{ old('status', $campaign->status) == 'sent' ? 'selected' : '' }}>Envoyée</option>
                        <option value="paused" {{ old('status', $campaign->status) == 'paused' ? 'selected' : '' }}>En pause</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date d'envoi programmée</label>
                    <input type="datetime-local" name="scheduled_at" 
                        value="{{ old('scheduled_at', $campaign->scheduled_at?->format('Y-m-d\TH:i')) }}" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <p class="mt-1 text-xs text-gray-500">Laissez vide pour envoi manuel</p>
                </div>
            </div>
        </div>

        <!-- Statistiques (si envoyée) -->
        @if($campaign->status == 'sent')
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistiques</h3>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ number_format($campaign->sent_count ?? 0) }}</div>
                    <div class="text-sm text-blue-700 mt-1">Envoyés</div>
                </div>
                <div class="bg-green-50 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-green-600">{{ number_format($campaign->opened_count ?? 0) }}</div>
                    <div class="text-sm text-green-700 mt-1">Ouvertures</div>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-purple-600">{{ number_format($campaign->clicked_count ?? 0) }}</div>
                    <div class="text-sm text-purple-700 mt-1">Clics</div>
                </div>
                <div class="bg-orange-50 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-orange-600">
                        {{ $campaign->sent_count > 0 ? number_format(($campaign->opened_count / $campaign->sent_count) * 100, 1) : 0 }}%
                    </div>
                    <div class="text-sm text-orange-700 mt-1">Taux d'ouverture</div>
                </div>
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="p-6 flex justify-between items-center bg-gray-50">
            <form action="{{ route('admin.newsletter.campaigns.destroy', $campaign) }}" method="POST" 
                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette campagne ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Supprimer
                </button>
            </form>

            <div class="flex space-x-3">
                @if($campaign->status == 'draft' || $campaign->status == 'scheduled')
                    <button type="button" onclick="if(confirm('Envoyer cette campagne maintenant ?')) { document.getElementById('send-form').submit(); }" 
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        📧 Envoyer maintenant
                    </button>
                @endif
                
                <a href="{{ route('admin.newsletter.campaigns.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Annuler
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Sauvegarder
                </button>
            </div>
        </div>
    </form>

    @if($campaign->status == 'draft' || $campaign->status == 'scheduled')
        <form id="send-form" action="{{ route('admin.newsletter.campaigns.send', $campaign) }}" method="POST" class="hidden">
            @csrf
        </form>
    @endif
</div>
@endsection
