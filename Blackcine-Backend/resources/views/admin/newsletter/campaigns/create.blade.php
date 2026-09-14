@extends('layouts.app')

@section('title', 'Nouvelle Campagne - BlackCine')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <a href="{{ route('admin.newsletter.campaigns.index') }}" class="text-gray-500 hover:text-gray-700">
            ← Retour aux campagnes
        </a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">Créer une campagne newsletter</h1>
        <p class="text-gray-600 mt-1">{{ number_format($subscribersCount) }} abonnés actifs recevront cette campagne</p>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden max-w-4xl mx-auto">
        <form action="{{ route('admin.newsletter.campaigns.store') }}" method="POST" class="p-6">
            @csrf

            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom de la campagne (interne) *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Sujet de l'email *</label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="from_name" class="block text-sm font-medium text-gray-700 mb-1">Nom de l'expéditeur</label>
                        <input type="text" name="from_name" id="from_name" value="{{ old('from_name', 'BlackCine') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="from_email" class="block text-sm font-medium text-gray-700 mb-1">Email expéditeur</label>
                        <input type="email" name="from_email" id="from_email" value="{{ old('from_email', 'newsletter@blackcine.com') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Contenu HTML *</label>
                    <textarea name="content" id="content" rows="15" required
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 font-mono text-sm">{{ old('content') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Vous pouvez utiliser du HTML pour formater votre email</p>
                </div>

                <div>
                    <label for="scheduled_at" class="block text-sm font-medium text-gray-700 mb-1">Planifier l'envoi (optionnel)</label>
                    <input type="datetime-local" name="scheduled_at" id="scheduled_at" value="{{ old('scheduled_at') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Laissez vide pour enregistrer comme brouillon</p>
                </div>

                <div class="flex items-center p-4 bg-blue-50 rounded-md">
                    <input type="checkbox" name="send_now" id="send_now" value="1"
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="send_now" class="ml-2 block text-sm text-gray-900 font-medium">
                        📤 Envoyer immédiatement (ignore la planification)
                    </label>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="button" onclick="window.history.back()" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 mr-3">
                    Annuler
                </button>
                <button type="submit" class="bg-blue-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700">
                    Créer la campagne
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
