@extends('layouts.app')

@section('title', 'Créer titre - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div><h1 class="text-3xl font-bold text-gray-800">Créer un titre</h1><p class="text-gray-600 mt-2">Ajoutez un nouveau film ou série</p></div>
    <a href="{{ route('admin.titles.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors"><span class="material-symbols-outlined mr-1">arrow_back</span>Retour</a>
</div>

@if ($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
        <h3 class="text-red-800 font-semibold mb-2 flex items-center"><span class="material-symbols-outlined mr-2">error</span>Erreurs de validation</h3>
        <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.titles.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonne Principale -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informations principales</h3>
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm @error('name') border-red-500 @enderror">
                        @error('name')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Synopsis</label>
                        <textarea name="synopsis" rows="4" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">{{ old('synopsis') }}</textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date de sortie</label>
                            <input type="date" name="release_date" value="{{ old('release_date') }}" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Durée (min)</label>
                            <input type="number" name="runtime_minutes" value="{{ old('runtime_minutes') }}" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne Latérale -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Type & Statut</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                        <select name="type" required class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                            <option value="">Sélectionner</option>
                            @foreach ($types as $value => $label)
                                <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Statut <span class="text-red-500">*</span></label>
                        <select name="status" required class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                            <option value="">Sélectionner</option>
                            @foreach ($statuses as $value => $label)
                                <option value="{{ $value }}" {{ old('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Affiche</h3>
                <input type="file" name="poster_image" id="posterImage" accept="image/*" class="hidden">
                <label for="posterImage" class="cursor-pointer block">
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-red-400 transition-colors">
                        <span class="material-symbols-outlined text-4xl text-gray-300 mb-2 block">upload</span>
                        <p class="text-sm text-gray-600">Cliquez pour uploader</p>
                        <p class="text-xs text-gray-400 mt-1">PNG, JPG (max 2MB)</p>
                    </div>
                </label>
            </div>

            <!-- Section Bande-annonce -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Bande-annonce</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lien YouTube</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="material-symbols-outlined text-gray-400 text-sm">link</span>
                            </span>
                            <input type="url" name="trailer_url" value="{{ old('trailer_url') }}" placeholder="https://youtube.com/..." class="w-full pl-9 rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm text-sm">
                        </div>
                    </div>

                    <div class="relative flex py-1 items-center">
                        <div class="flex-grow border-t border-gray-200"></div>
                        <span class="flex-shrink-0 mx-2 text-xs text-gray-400 font-medium">OU</span>
                        <div class="flex-grow border-t border-gray-200"></div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fichier Vidéo (MP4)</label>
                        <input type="file" name="trailer_file" id="trailerFile" accept="video/mp4,video/webm" class="hidden">
                        <label for="trailerFile" class="cursor-pointer block">
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-red-400 transition-colors bg-gray-50">
                                <span class="material-symbols-outlined text-3xl text-gray-400 mb-1 block">movie</span>
                                <p class="text-xs text-gray-600" id="trailerFileName">Cliquez pour uploader</p>
                                <p class="text-[10px] text-gray-400 mt-1">MP4, WebM (max 50MB)</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="space-y-3">
                    <button type="submit" class="w-full bg-red-600 text-white py-2.5 px-4 rounded-lg hover:bg-red-700 transition-all shadow-md font-medium flex justify-center items-center">
                        <span class="material-symbols-outlined mr-2 text-sm">save</span>Créer
                    </button>
                    <a href="{{ route('admin.titles.index') }}" class="block w-full bg-gray-100 text-gray-700 py-2.5 px-4 rounded-lg hover:bg-gray-200 transition-all font-medium text-center">Annuler</a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    // Preview Affiche
    document.getElementById('posterImage').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const label = document.querySelector('label[for="posterImage"]');
                label.innerHTML = `<img src="${event.target.result}" class="w-full rounded-lg">`;
            };
            reader.readAsDataURL(file);
        }
    });

    // Nom fichier Trailer
    document.getElementById('trailerFile').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            document.getElementById('trailerFileName').textContent = file.name;
        }
    });
</script>
@endsection