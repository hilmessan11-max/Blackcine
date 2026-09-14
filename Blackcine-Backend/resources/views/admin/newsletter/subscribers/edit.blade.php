@extends('layouts.app')

@section('title', 'Modifier Abonné - BlackCine')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <a href="{{ route('admin.newsletter.subscribers.index') }}" class="text-gray-500 hover:text-gray-700">
            ← Retour aux abonnés
        </a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">Modifier l'abonné : {{ $subscriber->email }}</h1>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden max-w-2xl mx-auto">
        <form action="{{ route('admin.newsletter.subscribers.update', $subscriber) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $subscriber->email) }}" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $subscriber->first_name) }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $subscriber->last_name) }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label for="source" class="block text-sm font-medium text-gray-700 mb-1">Source</label>
                    <select name="source" id="source" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">-- Sélectionner --</option>
                        <option value="website" {{ old('source', $subscriber->source) == 'website' ? 'selected' : '' }}>Site Web</option>
                        <option value="import" {{ old('source', $subscriber->source) == 'import' ? 'selected' : '' }}>Import CSV</option>
                        <option value="manual" {{ old('source', $subscriber->source) == 'manual' ? 'selected' : '' }}>Manuel</option>
                        <option value="api" {{ old('source', $subscriber->source) == 'api' ? 'selected' : '' }}>API</option>
                    </select>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $subscriber->is_active) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        Abonné actif
                    </label>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="button" onclick="window.history.back()" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 mr-3">
                    Annuler
                </button>
                <button type="submit" class="bg-blue-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
