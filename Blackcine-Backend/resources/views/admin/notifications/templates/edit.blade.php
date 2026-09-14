@extends('layouts.app')

@section('title', 'Modifier modèle - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div><h1 class="text-3xl font-bold text-gray-800">Modifier: {{ $template->name }}</h1><p class="text-gray-600 mt-2">Mise à jour du contenu de l'e-mail</p></div>
    <a href="{{ route('admin.notifications.templates.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors"><span class="material-symbols-outlined mr-1">arrow_back</span>Retour</a>
</div>

<form action="{{ route('admin.notifications.templates.update', $template->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonne principale : Éditeur -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Contenu de l'e-mail</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sujet de l'e-mail <span class="text-red-500">*</span></label>
                        <input type="text" name="subject" value="{{ old('subject', $template->subject) }}" required class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contenu HTML <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <textarea name="content" rows="20" required class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm font-mono text-sm">{{ old('content', $template->content) }}</textarea>
                        </div>
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
                        <input type="text" name="name" value="{{ old('name', $template->name) }}" required class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Clé unique (Slug) <span class="text-red-500">*</span></label>
                        <input type="text" name="key" value="{{ old('key', $template->key) }}" required class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm bg-gray-50 font-mono text-sm">
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
                    <span class="material-symbols-outlined mr-2 text-sm">save</span>Mettre à jour
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
