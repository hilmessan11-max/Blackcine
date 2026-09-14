@extends('layouts.app')

@section('title', 'Nouveau Programme d\'Affiliation - BlackCine')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <a href="{{ route('admin.advertising.affiliate.index') }}" class="text-gray-500 hover:text-gray-700">
            ← Retour aux programmes
        </a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">Ajouter un programme d'affiliation</h1>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden max-w-2xl mx-auto">
        <form action="{{ route('admin.advertising.affiliate.store') }}" method="POST" class="p-6">
            @csrf

            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom du Programme *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Ex: Amazon Associates"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="affiliate_id" class="block text-sm font-medium text-gray-700 mb-1">Votre ID Affilié (Tag)</label>
                    <input type="text" name="affiliate_id" id="affiliate_id" value="{{ old('affiliate_id') }}" placeholder="Ex: blackcine-21"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="base_url" class="block text-sm font-medium text-gray-700 mb-1">URL de base du programme</label>
                    <input type="url" name="base_url" id="base_url" value="{{ old('base_url') }}" placeholder="https://www.amazon.fr"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="commission_rate" class="block text-sm font-medium text-gray-700 mb-1">Taux de commission estimé (%)</label>
                    <input type="number" name="commission_rate" id="commission_rate" value="{{ old('commission_rate') }}" min="0" max="100" step="0.1"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description / Notes</label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        Programme actif
                    </label>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="button" onclick="window.history.back()" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 mr-3">
                    Annuler
                </button>
                <button type="submit" class="bg-blue-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Enregistrer le programme
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
