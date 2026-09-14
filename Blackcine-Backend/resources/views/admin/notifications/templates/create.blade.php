@extends('layouts.app')

@section('title', 'Créer un modèle - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div><h1 class="text-3xl font-bold text-gray-800">Nouveau Modèle</h1><p class="text-gray-600 mt-2">Designez un nouvel e-mail transactionnel</p></div>
    <a href="{{ route('admin.notifications.templates.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors"><span class="material-symbols-outlined mr-1">arrow_back</span>Retour</a>
</div>

<form action="{{ route('admin.notifications.templates.store') }}" method="POST">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonne principale : Éditeur -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Contenu de l'e-mail</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sujet de l'e-mail <span class="text-red-500">*</span></label>
                        <input type="text" name="subject" value="{{ old('subject') }}" required placeholder="Ex: Bienvenue sur BlackCiné !" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contenu HTML <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <textarea name="content" rows="15" required class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm font-mono text-sm" placeholder="<h1>Bonjour {{ $user->name }},</h1>...">{{ old('content') }}</textarea>
                            <div class="absolute top-2 right-2 flex space-x-1">
                                <button type="button" class="p-1 bg-gray-100 hover:bg-gray-200 rounded text-gray-600" title="Insérer variable"><span class="material-symbols-outlined text-sm">data_object</span></button>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Utilisez la syntaxe Blade ou {{ $variable }} pour les données dynamiques.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne latérale : Paramètres -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Configuration</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom du modèle <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Ex: Bienvenue Utilisateur" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Clé unique (Slug) <span class="text-red-500">*</span></label>
                        <input type="text" name="key" value="{{ old('key') }}" required placeholder="Ex: welcome_email" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm bg-gray-50 font-mono text-sm">
                        <p class="text-xs text-gray-500 mt-1">Utilisé pour appeler ce modèle dans le code.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Variables disponibles</h3>
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm bg-gray-50 p-2 rounded border border-gray-200">
                        <code class="text-red-600 font-mono">{{ $user->name }}</code>
                        <span class="text-gray-500 text-xs">Nom utilisateur</span>
                    </div>
                    <div class="flex items-center justify-between text-sm bg-gray-50 p-2 rounded border border-gray-200">
                        <code class="text-red-600 font-mono">{{ $user->email }}</code>
                        <span class="text-gray-500 text-xs">Email utilisateur</span>
                    </div>
                    <div class="flex items-center justify-between text-sm bg-gray-50 p-2 rounded border border-gray-200">
                        <code class="text-red-600 font-mono">{{ $action_url }}</code>
                        <span class="text-gray-500 text-xs">Lien d'action</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <button type="submit" class="w-full bg-red-600 text-white py-2.5 px-4 rounded-lg hover:bg-red-700 transition-all shadow-md font-medium flex justify-center items-center">
                    <span class="material-symbols-outlined mr-2 text-sm">save</span>Enregistrer le modèle
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
