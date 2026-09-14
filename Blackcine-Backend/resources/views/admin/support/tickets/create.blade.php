@extends('layouts.app')

@section('title', 'Créer un ticket - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div><h1 class="text-3xl font-bold text-gray-800">Nouveau Ticket</h1><p class="text-gray-600 mt-2">Ouvrir une demande de support manuellement</p></div>
    <a href="{{ route('admin.support.tickets.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors"><span class="material-symbols-outlined mr-1">arrow_back</span>Retour</a>
</div>

<form action="{{ route('admin.support.tickets.store') }}" method="POST">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonne principale -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Détails de la demande</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sujet <span class="text-red-500">*</span></label>
                        <input type="text" name="subject" value="{{ old('subject') }}" required placeholder="Ex: Problème de paiement" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Message <span class="text-red-500">*</span></label>
                        <textarea name="message" rows="6" required placeholder="Décrivez le problème en détail..." class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">{{ old('message') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne latérale -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informations</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Priorité</label>
                        <select name="priority" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                            <option value="low">Basse</option>
                            <option value="medium" selected>Moyenne</option>
                            <option value="high">Haute</option>
                            <option value="urgent">Urgente</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Utilisateur concerné (Email)</label>
                        <input type="email" name="user_email" value="{{ old('user_email') }}" placeholder="client@example.com" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                        <p class="text-xs text-gray-500 mt-1">Laisser vide si interne</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <button type="submit" class="w-full bg-red-600 text-white py-2.5 px-4 rounded-lg hover:bg-red-700 transition-all shadow-md font-medium flex justify-center items-center">
                    <span class="material-symbols-outlined mr-2 text-sm">send</span>Créer le ticket
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
