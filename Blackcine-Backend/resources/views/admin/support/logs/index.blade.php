@extends('layouts.app')

@section('title', 'Logs Système - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div><h1 class="text-3xl font-bold text-gray-800">Logs Système</h1><p class="text-gray-600 mt-2">Surveillance et historique des erreurs</p></div>
    <div class="flex space-x-3">
        @if($logExists)
            <a href="{{ route('admin.support.logs.download') }}" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 flex items-center shadow-sm transition-colors font-medium">
                <span class="material-symbols-outlined mr-2 text-base">download</span>Télécharger
            </a>
            <form action="{{ route('admin.support.logs.clear') }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir effacer tous les logs ?');">
                @csrf
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center shadow-sm transition-colors font-medium">
                    <span class="material-symbols-outlined mr-2 text-base">delete</span>Effacer
                </button>
            </form>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center justify-between">
        <div><p class="text-sm text-gray-500">Statut fichier</p><p class="text-lg font-bold {{ $logExists ? 'text-green-600' : 'text-gray-400' }}">{{ $logExists ? 'Actif' : 'Inexistant' }}</p></div>
        <div class="bg-blue-50 p-3 rounded-full"><span class="material-symbols-outlined text-blue-600">description</span></div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center justify-between">
        <div><p class="text-sm text-gray-500">Taille actuelle</p><p class="text-lg font-bold text-gray-900">{{ $logExists ? number_format($logSize / 1024, 2) . ' KB' : '0 KB' }}</p></div>
        <div class="bg-purple-50 p-3 rounded-full"><span class="material-symbols-outlined text-purple-600">data_usage</span></div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center justify-between">
        <div><p class="text-sm text-gray-500">Dernière modif.</p><p class="text-lg font-bold text-gray-900">{{ $logExists ? date('H:i:s d/m/Y', filemtime(storage_path('logs/laravel.log'))) : '-' }}</p></div>
        <div class="bg-yellow-50 p-3 rounded-full"><span class="material-symbols-outlined text-yellow-600">schedule</span></div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
        <h2 class="text-lg font-bold text-gray-800 flex items-center"><span class="material-symbols-outlined mr-2 text-gray-600">terminal</span>Contenu du fichier log</h2>
        <span class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded">100 dernières lignes</span>
    </div>
    
    @if($logExists)
        <div class="bg-[#1e1e1e] p-4 overflow-x-auto custom-scrollbar" style="max-height: 600px;">
            <pre class="text-xs font-mono leading-relaxed"><code class="language-log">@foreach($logs as $log)<div class="hover:bg-[#2d2d2d] px-2 py-0.5 rounded transition-colors border-l-2 {{ str_contains($log, '.ERROR') ? 'border-red-500 text-red-300' : (str_contains($log, '.WARNING') ? 'border-yellow-500 text-yellow-300' : 'border-transparent text-gray-300') }}">{{ $log }}</div>@endforeach</code></pre>
        </div>
    @else
        <div class="p-12 text-center">
            <div class="bg-gray-100 p-4 rounded-full mb-3 inline-block"><span class="material-symbols-outlined text-3xl text-gray-400">check_circle</span></div>
            <h3 class="text-lg font-medium text-gray-900">Aucun log enregistré</h3>
            <p class="text-gray-500 mt-1">Tout semble fonctionner correctement.</p>
        </div>
    @endif
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 8px; height: 8px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #1e1e1e; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #4a4a4a; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #606060; }
</style>
@endsection
