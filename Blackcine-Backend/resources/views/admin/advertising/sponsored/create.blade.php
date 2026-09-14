@extends('layouts.app')

@section('title', 'Nouveau Contenu Sponsorisé - BlackCine')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <a href="{{ route('admin.advertising.sponsored.index') }}" class="text-gray-500 hover:text-gray-700">
            ← Retour aux contenus sponsorisés
        </a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">Ajouter un partenariat sponsorisé</h1>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden max-w-4xl mx-auto">
        <form action="{{ route('admin.advertising.sponsored.store') }}" method="POST" class="p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informations Sponsor -->
                <div class="col-span-2">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Informations Sponsor</h3>
                </div>

                <div>
                    <label for="sponsor_name" class="block text-sm font-medium text-gray-700 mb-1">Nom du Sponsor *</label>
                    <input type="text" name="sponsor_name" id="sponsor_name" value="{{ old('sponsor_name') }}" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="campaign_name" class="block text-sm font-medium text-gray-700 mb-1">Nom de la Campagne (Interne)</label>
                    <input type="text" name="campaign_name" id="campaign_name" value="{{ old('campaign_name') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Mentions Légales & Tracking -->
                <div class="col-span-2 mt-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Mentions Légales & Tracking</h3>
                </div>

                <div>
                    <label for="legal_mention" class="block text-sm font-medium text-gray-700 mb-1">Mention à afficher *</label>
                    <input type="text" name="legal_mention" id="legal_mention" value="{{ old('legal_mention', 'Sponsorisé par') }}" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Ex: "En partenariat avec", "Présenté par"</p>
                </div>

                <div>
                    <label for="tracking_pixel_url" class="block text-sm font-medium text-gray-700 mb-1">Pixel de tracking (URL)</label>
                    <input type="url" name="tracking_pixel_url" id="tracking_pixel_url" value="{{ old('tracking_pixel_url') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="col-span-2">
                    <label for="contract_details" class="block text-sm font-medium text-gray-700 mb-1">Détails du contrat (Privé)</label>
                    <textarea name="contract_details" id="contract_details" rows="3"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('contract_details') }}</textarea>
                </div>

                <!-- Planification & Budget -->
                <div class="col-span-2 mt-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Planification & Budget</h3>
                </div>

                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Date de fin</label>
                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="agreed_amount" class="block text-sm font-medium text-gray-700 mb-1">Montant convenu (€)</label>
                    <input type="number" name="agreed_amount" id="agreed_amount" value="{{ old('agreed_amount') }}" min="0" step="0.01"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut *</label>
                    <select name="status" id="status" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Actif</option>
                        <option value="ended" {{ old('status') == 'ended' ? 'selected' : '' }}>Terminé</option>
                    </select>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="button" onclick="window.history.back()" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 mr-3">
                    Annuler
                </button>
                <button type="submit" class="bg-blue-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Enregistrer le partenariat
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
