@extends('layouts.app')

@section('title', 'Import/Export Catalogue - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div><h1 class="text-3xl font-bold text-gray-800">Import / Export Catalogue</h1><p class="text-gray-600 mt-2">Gestion en masse des données du catalogue</p></div>
    <a href="{{ asset('templates/import_template.csv') }}" class="text-blue-600 hover:text-blue-800 flex items-center text-sm font-medium transition-colors"><span class="material-symbols-outlined mr-1 text-base">download</span>Télécharger modèle CSV</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Export -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center">
            <div class="bg-green-100 p-2 rounded-full mr-3"><span class="material-symbols-outlined text-green-600">upload_file</span></div>
            <h2 class="text-lg font-bold text-gray-800">Exporter les données</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.catalog.import-export.export') }}" method="GET" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Format d'export</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="format" value="csv" checked class="peer sr-only">
                            <div class="p-4 border border-gray-200 rounded-lg peer-checked:border-green-500 peer-checked:bg-green-50 hover:bg-gray-50 transition-all flex flex-col items-center">
                                <span class="material-symbols-outlined text-3xl text-gray-400 peer-checked:text-green-600 mb-2">csv</span>
                                <span class="text-sm font-medium text-gray-700 peer-checked:text-green-800">Fichier CSV</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="format" value="excel" class="peer sr-only">
                            <div class="p-4 border border-gray-200 rounded-lg peer-checked:border-green-500 peer-checked:bg-green-50 hover:bg-gray-50 transition-all flex flex-col items-center">
                                <span class="material-symbols-outlined text-3xl text-gray-400 peer-checked:text-green-600 mb-2">table_view</span>
                                <span class="text-sm font-medium text-gray-700 peer-checked:text-green-800">Fichier Excel</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type de contenu</label>
                    <select name="type" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 transition-colors shadow-sm">
                        <option value="all">Tout le catalogue</option>
                        <option value="movie">Films uniquement</option>
                        <option value="series">Séries uniquement</option>
                        <option value="classic">Classiques uniquement</option>
                    </select>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-green-600 text-white py-2.5 px-4 rounded-lg hover:bg-green-700 transition-all shadow-md font-medium flex justify-center items-center">
                        <span class="material-symbols-outlined mr-2 text-sm">download</span>Exporter maintenant
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Import -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center">
            <div class="bg-blue-100 p-2 rounded-full mr-3"><span class="material-symbols-outlined text-blue-600">cloud_upload</span></div>
            <h2 class="text-lg font-bold text-gray-800">Importer des données</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.catalog.import-export.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fichier source (CSV)</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors bg-gray-50">
                        <div class="space-y-1 text-center">
                            <span class="material-symbols-outlined text-4xl text-gray-400">upload_file</span>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Téléverser un fichier</span>
                                    <input id="file-upload" name="file" type="file" class="sr-only" accept=".csv,.txt" required>
                                </label>
                                <p class="pl-1">ou glisser-déposer</p>
                            </div>
                            <p class="text-xs text-gray-500">CSV jusqu'à 10MB</p>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-100 rounded-lg p-3">
                    <h4 class="text-sm font-medium text-blue-800 mb-1 flex items-center"><span class="material-symbols-outlined text-sm mr-1">info</span>Structure attendue</h4>
                    <p class="text-xs text-blue-600">Les colonnes doivent respecter l'ordre : ID, Nom, Type, Synopsis, Date, Pays, Langue, Durée, Genres, Statut.</p>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-blue-600 text-white py-2.5 px-4 rounded-lg hover:bg-blue-700 transition-all shadow-md font-medium flex justify-center items-center">
                        <span class="material-symbols-outlined mr-2 text-sm">publish</span>Lancer l'importation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(session('errors'))
    <div class="mt-8 bg-red-50 border border-red-200 rounded-xl p-6">
        <h3 class="text-lg font-bold text-red-800 mb-2 flex items-center"><span class="material-symbols-outlined mr-2">error</span>Erreurs lors de l'importation</h3>
        <ul class="list-disc list-inside text-sm text-red-700 space-y-1 ml-2">
            @foreach(session('errors') as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@endsection
