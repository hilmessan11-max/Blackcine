@extends('layouts.app')

@section('title', 'Créer un projet - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div><h1 class="text-3xl font-bold text-gray-800">Créer un projet</h1><p class="text-gray-600 mt-2">Ajoutez un nouveau projet en développement</p></div>
    <a href="{{ route('admin.community.projects.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors"><span class="material-symbols-outlined mr-1">arrow_back</span>Retour</a>
</div>

<form method="POST" action="{{ route('admin.community.projects.store') }}">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informations du projet</h3>
                <div class="space-y-6">
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Titre <span class="text-red-500">*</span></label><input type="text" name="title" value="{{ old('title') }}" required class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Description</label><textarea name="description" rows="4" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">{{ old('description') }}</textarea></div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Besoins</h3>
                <div class="space-y-3">
                    <label class="flex items-center p-3 bg-gray-50 rounded-lg"><input type="checkbox" name="needs_crew" value="1" {{ old('needs_crew') ? 'checked' : '' }} class="h-4 w-4 text-red-600 rounded"><span class="ml-2 text-sm font-medium">Besoin d'équipe</span></label>
                    <label class="flex items-center p-3 bg-gray-50 rounded-lg"><input type="checkbox" name="needs_equipment" value="1" {{ old('needs_equipment') ? 'checked' : '' }} class="h-4 w-4 text-red-600 rounded"><span class="ml-2 text-sm font-medium">Besoin de matériel</span></label>
                    <label class="flex items-center p-3 bg-gray-50 rounded-lg"><input type="checkbox" name="needs_location" value="1" {{ old('needs_location') ? 'checked' : '' }} class="h-4 w-4 text-red-600 rounded"><span class="ml-2 text-sm font-medium">Besoin de lieux de tournage</span></label>
                </div>
            </div>
        </div>
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Type</h3>
                <select name="project_type" required class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm"><option value="film">Film</option><option value="series">Série</option><option value="documentary">Documentaire</option><option value="short_film">Court métrage</option></select>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Statut</h3>
                <select name="status" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm"><option value="casting">Casting</option><option value="pre_production">Pré-production</option><option value="production">Production</option></select>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="space-y-3">
                    <button type="submit" class="w-full bg-red-600 text-white py-2.5 px-4 rounded-lg hover:bg-red-700 transition-all shadow-md font-medium flex justify-center items-center"><span class="material-symbols-outlined mr-2 text-sm">save</span>Créer</button>
                    <a href="{{ route('admin.community.projects.index') }}" class="block w-full bg-gray-100 text-gray-700 py-2.5 px-4 rounded-lg hover:bg-gray-200 transition-all font-medium text-center">Annuler</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
