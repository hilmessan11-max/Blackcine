@extends('layouts.app')

@section('title', 'Modifier festival - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div><h1 class="text-3xl font-bold text-gray-800">Modifier: {{ $festival->name }}</h1></div>
    <a href="{{ route('admin.partners.festivals.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors"><span class="material-symbols-outlined mr-1">arrow_back</span>Retour</a>
</div>

<form action="{{ route('admin.partners.festivals.update', $festival->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonne principale -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informations générales</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom du festival <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $festival->name) }}" required class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="4" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">{{ old('description', $festival->description) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Dates et Localisation</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
                        <input type="date" name="starts_at" value="{{ old('starts_at', $festival->starts_at ? $festival->starts_at->format('Y-m-d') : '') }}" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date de fin</label>
                        <input type="date" name="ends_at" value="{{ old('ends_at', $festival->ends_at ? $festival->ends_at->format('Y-m-d') : '') }}" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                        <input type="text" name="city" value="{{ old('city', $festival->city) }}" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pays</label>
                        <input type="text" name="country" value="{{ old('country', $festival->country) }}" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne latérale -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Logo</h3>
                <div class="space-y-4">
                    @if($festival->logo_url)
                        <div class="mb-3">
                            <img src="{{ $festival->logo_url }}" alt="Logo actuel" class="w-full rounded-lg border border-gray-200">
                        </div>
                    @endif
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-red-400 transition-colors cursor-pointer" onclick="document.getElementById('logo').click()">
                        <span class="material-symbols-outlined text-4xl text-gray-300 mb-2 block">upload</span>
                        <p class="text-sm text-gray-600">Changer le logo</p>
                    </div>
                    <input type="file" name="logo" id="logo" accept="image/*" class="hidden">
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Liens</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Site web officiel</label>
                        <input type="url" name="website_url" value="{{ old('website_url', $festival->website_url) }}" placeholder="https://" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
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
