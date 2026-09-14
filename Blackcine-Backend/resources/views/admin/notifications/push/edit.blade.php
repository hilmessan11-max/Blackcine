@extends('layouts.app')

@section('title', 'Modifier Notification Push - BlackCine')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <a href="{{ route('admin.notifications.push.index') }}" class="text-gray-500 hover:text-gray-700">
            ← Retour aux notifications
        </a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">Modifier la notification : {{ $push->title }}</h1>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden max-w-4xl mx-auto">
        <form action="{{ route('admin.notifications.push.update', $push) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Contenu de la Notification -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Contenu de la Notification</h3>
                </div>

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Titre *</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $push->title) }}" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message *</label>
                    <textarea name="message" id="message" rows="4" required
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('message', $push->message) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="image_url" class="block text-sm font-medium text-gray-700 mb-1">URL de l'image (Optionnelle)</label>
                        <input type="url" name="image_url" id="image_url" value="{{ old('image_url', $push->image_url) }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="target_url" class="block text-sm font-medium text-gray-700 mb-1">URL de destination (Optionnelle)</label>
                        <input type="url" name="target_url" id="target_url" value="{{ old('target_url', $push->target_url) }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Ciblage -->
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Ciblage de l'Audience</h3>
                </div>

                <div>
                    <label for="target_audience" class="block text-sm font-medium text-gray-700 mb-1">Public cible *</label>
                    <select name="target_audience" id="target_audience" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="all" {{ old('target_audience', $push->target_audience) == 'all' ? 'selected' : '' }}>Tous les utilisateurs</option>
                        <option value="subscribers" {{ old('target_audience', $push->target_audience) == 'subscribers' ? 'selected' : '' }}>Abonnés Premium uniquement</option>
                        <option value="free_users" {{ old('target_audience', $push->target_audience) == 'free_users' ? 'selected' : '' }}>Utilisateurs gratuits uniquement</option>
                        <option value="specific_users" {{ old('target_audience', $push->target_audience) == 'specific_users' ? 'selected' : '' }}>Utilisateurs spécifiques</option>
                    </select>
                </div>

                <!-- Planification -->
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Planification</h3>
                </div>

                <div>
                    <label for="scheduled_at" class="block text-sm font-medium text-gray-700 mb-1">Date et heure d'envoi (Optionnel)</label>
                    <input type="datetime-local" name="scheduled_at" id="scheduled_at" 
                           value="{{ old('scheduled_at', $push->scheduled_at ? $push->scheduled_at->format('Y-m-d\TH:i') : '') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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
                <button type="submit" class="bg-blue-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
