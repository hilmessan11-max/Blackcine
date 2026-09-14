@extends('layouts.app')

@section('title', 'Modifier casting - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div><h1 class="text-3xl font-bold text-gray-800">Modifier: {{ $casting->title }}</h1></div>
    <a href="{{ route('admin.community.castings.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors"><span class="material-symbols-outlined mr-1">arrow_back</span>Retour</a>
</div>

<form method="POST" action="{{ route('admin.community.castings.update', $casting) }}">
    @csrf @method('PUT')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informations</h3>
                <div class="space-y-6">
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Titre <span class="text-red-500">*</span></label><input type="text" name="title" value="{{ old('title', $casting->title) }}" required class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Rôle <span class="text-red-500">*</span></label><input type="text" name="role" value="{{ old('role', $casting->role) }}" required class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Description</label><textarea name="description" rows="4" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">{{ old('description', $casting->description) }}</textarea></div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Ville</label><input type="text" name="city" value="{{ old('city', $casting->city) }}" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm"></div>
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Pays</label><input type="text" name="country" value="{{ old('country', $casting->country) }}" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Date début</label><input type="date" name="start_date" value="{{ old('start_date', $casting->start_date?->format('Y-m-d')) }}" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm"></div>
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Date fin</label><input type="date" name="end_date" value="{{ old('end_date', $casting->end_date?->format('Y-m-d')) }}" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Statut</h3>
                <select name="status" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm"><option value="draft" {{ $casting->status == 'draft' ? 'selected' : '' }}>Brouillon</option><option value="published" {{ $casting->status == 'published' ? 'selected' : '' }}>Publié</option><option value="closed" {{ $casting->status == 'closed' ? 'selected' : '' }}>Fermé</option></select>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="space-y-3">
                    <button type="submit" class="w-full bg-red-600 text-white py-2.5 px-4 rounded-lg hover:bg-red-700 transition-all shadow-md font-medium flex justify-center items-center"><span class="material-symbols-outlined mr-2 text-sm">save</span>Mettre à jour</button>
                    <a href="{{ route('admin.community.castings.index') }}" class="block w-full bg-gray-100 text-gray-700 py-2.5 px-4 rounded-lg hover:bg-gray-200 transition-all font-medium text-center">Annuler</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
