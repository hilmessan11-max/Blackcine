@extends('layouts.app')

@section('title', 'Modèles d\'e-mails - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div><h1 class="text-3xl font-bold text-gray-800">Modèles d'e-mails</h1><p class="text-gray-600 mt-2">Gérez les templates de notifications par e-mail</p></div>
    <a href="{{ route('admin.notifications.templates.create') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center shadow-sm transition-colors"><span class="material-symbols-outlined mr-2">add_circle</span>Nouveau Modèle</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($templates as $template)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow group">
        <div class="p-6 border-b border-gray-100">
            <div class="flex justify-between items-start mb-4">
                <div class="bg-blue-50 p-3 rounded-lg group-hover:bg-blue-100 transition-colors">
                    <span class="material-symbols-outlined text-blue-600 text-2xl">mail</span>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.notifications.templates.edit', $template->id) }}" class="text-gray-400 hover:text-blue-600 transition-colors"><span class="material-symbols-outlined">edit</span></a>
                    <form action="{{ route('admin.notifications.templates.destroy', $template->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce modèle ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors"><span class="material-symbols-outlined">delete</span></button>
                    </form>
                </div>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-1">{{ $template->name }}</h3>
            <p class="text-sm text-gray-500 mb-3">{{ $template->subject }}</p>
            <div class="flex flex-wrap gap-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    <span class="material-symbols-outlined text-[10px] mr-1">vpn_key</span>{{ $template->key }}
                </span>
            </div>
        </div>
        <div class="bg-gray-50 px-6 py-3 flex justify-between items-center text-xs text-gray-500">
            <span>Mis à jour le {{ $template->updated_at->format('d/m/Y') }}</span>
            <button onclick="previewTemplate('{{ $template->id }}')" class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                <span class="material-symbols-outlined text-sm mr-1">visibility</span>Aperçu
            </button>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-12 bg-white rounded-xl border border-gray-100 border-dashed">
        <div class="bg-gray-50 p-4 rounded-full mb-3 inline-block"><span class="material-symbols-outlined text-3xl text-gray-400">drafts</span></div>
        <h3 class="text-lg font-medium text-gray-900">Aucun modèle</h3>
        <p class="text-gray-500 mt-1">Créez votre premier modèle d'e-mail pour commencer.</p>
        <a href="{{ route('admin.notifications.templates.create') }}" class="mt-4 inline-flex items-center text-red-600 hover:text-red-700 font-medium">Créer un modèle <span class="material-symbols-outlined ml-1 text-sm">arrow_forward</span></a>
    </div>
    @endforelse
</div>

<!-- Modal Aperçu (Placeholder) -->
<div id="previewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">Aperçu du modèle</h3>
            <button onclick="closePreview()" class="text-gray-400 hover:text-gray-600"><span class="material-symbols-outlined">close</span></button>
        </div>
        <div class="p-6 bg-gray-100 min-h-[300px] flex items-center justify-center">
            <p class="text-gray-500">L'aperçu sera chargé ici via AJAX...</p>
        </div>
    </div>
</div>

<script>
    function previewTemplate(id) {
        // Logique pour charger l'aperçu (à implémenter)
        // document.getElementById('previewModal').classList.remove('hidden');
        // document.getElementById('previewModal').classList.add('flex');
        alert('Fonctionnalité d\'aperçu à venir pour l\'ID: ' + id);
    }
    function closePreview() {
        document.getElementById('previewModal').classList.add('hidden');
        document.getElementById('previewModal').classList.remove('flex');
    }
</script>
@endsection
