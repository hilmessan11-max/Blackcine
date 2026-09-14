@extends('layouts.app')

@section('title', 'Modifier une personne - BlackCine Admin')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-800">Modifier : {{ $person->name }}</h1>
        <a href="{{ route('admin.persons.index') }}" class="text-gray-600 hover:text-gray-900">
            &larr; Retour
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <form action="{{ route('admin.persons.update', $person->id) }}" method="POST" class="p-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nom complet *</label>
                <input type="text" name="name" value="{{ old('name', $person->name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date de naissance</label>
                <input type="date" name="birth_date" value="{{ old('birth_date', $person->birth_date) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                @error('birth_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Lieu de naissance</label>
                <input type="text" name="birth_place" value="{{ old('birth_place', $person->birth_place) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                @error('birth_place') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Biographie</label>
                <textarea name="biography" rows="5" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">{{ old('biography', $person->biography) }}</textarea>
                @error('biography') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Photo</label>
                <select name="photo_asset_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Sélectionner une photo</option>
                    @foreach(\App\Models\Asset::where('mime_type', 'like', 'image/%')->latest()->take(50)->get() as $asset)
                        <option value="{{ $asset->id }}" {{ old('photo_asset_id', $person->photo_asset_id) == $asset->id ? 'selected' : '' }}>
                            {{ basename($asset->path) }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Les images doivent d'abord être uploadées dans la médiathèque.</p>
                @error('photo_asset_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection
