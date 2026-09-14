@extends('layouts.app')

@section('title', 'Modifier sélection - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div><h1 class="text-3xl font-bold text-gray-800">Modifier: {{ $selection->title }}</h1></div>
    <a href="{{ route('admin.editorial.selections.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors"><span class="material-symbols-outlined mr-1">arrow_back</span>Retour</a>
</div>

<form method="POST" action="{{ route('admin.editorial.selections.update', $selection) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informations</h3>
                <div class="space-y-6">
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Titre <span class="text-red-500">*</span></label><input type="text" name="title" value="{{ old('title', $selection->title) }}" required class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">@error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Slug</label><input type="text" name="slug" value="{{ old('slug', $selection->slug) }}" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Description</label><textarea name="description" rows="4" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">{{ old('description', $selection->description) }}</textarea></div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Titres associés</h3>
                <p class="text-sm text-gray-500 mb-4">Gérez les films/séries de cette sélection</p>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                    <span class="material-symbols-outlined text-5xl text-gray-300 mb-3 block">movie</span>
                    <p class="text-gray-500">Interface de gestion des titres</p>
                </div>
            </div>
        </div>
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Type de sélection</h3>
                <div class="space-y-3">
                    <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors"><input type="radio" name="type" value="coup_de_coeur" class="h-4 w-4 text-red-600 focus:ring-red-500" {{ old('type', $selection->type) == 'coup_de_coeur' ? 'checked' : '' }}><div class="ml-3 flex items-center"><span class="material-symbols-outlined text-red-600 mr-2">favorite</span><span class="text-sm font-medium text-gray-700">Coup de cœur</span></div></label>
                    <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors"><input type="radio" name="type" value="carousel" class="h-4 w-4 text-red-600 focus:ring-red-500" {{ old('type', $selection->type) == 'carousel' ? 'checked' : '' }}><div class="ml-3 flex items-center"><span class="material-symbols-outlined text-purple-600 mr-2">view_carousel</span><span class="text-sm font-medium text-gray-700">Carousel</span></div></label>
                    <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors"><input type="radio" name="type" value="selection" class="h-4 w-4 text-red-600 focus:ring-red-500" {{ old('type', $selection->type) == 'selection' ? 'checked' : '' }}><div class="ml-3 flex items-center"><span class="material-symbols-outlined text-green-600 mr-2">stars</span><span class="text-sm font-medium text-gray-700">À ne pas manquer</span></div></label>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Paramètres</h3>
                <div class="space-y-4">
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Ordre</label><input type="number" name="display_order" value="{{ old('display_order', $selection->display_order ?? 0) }}" min="0" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm"></div>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg"><input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $selection->is_active) ? 'checked' : '' }} class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500"><label for="is_active" class="ml-2 text-sm text-gray-700 font-medium">Sélection active</label></div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="space-y-3">
                    <button type="submit" class="w-full bg-red-600 text-white py-2.5 px-4 rounded-lg hover:bg-red-700 transition-all shadow-md font-medium flex justify-center items-center"><span class="material-symbols-outlined mr-2 text-sm">save</span>Mettre à jour</button>
                    <a href="{{ route('admin.editorial.selections.index') }}" class="block w-full bg-gray-100 text-gray-700 py-2.5 px-4 rounded-lg hover:bg-gray-200 transition-all font-medium text-center">Annuler</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
