@extends('layouts.app')

@section('title', 'Modifier Cinéma - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div><h1 class="text-3xl font-bold text-gray-800">Modifier: {{ $cinema->name }}</h1><p class="text-gray-600 mt-2">Mettez à jour les informations du cinéma</p></div>
    <a href="{{ route('admin.boxoffice.cinemas.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors"><span class="material-symbols-outlined mr-1">arrow_back</span>Retour</a>
</div>

<form method="POST" action="{{ route('admin.boxoffice.cinemas.update', $cinema->id) }}">
    @csrf @method('PUT')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informations générales</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Nom <span class="text-red-500">*</span></label><input type="text" name="name" value="{{ old('name', $cinema->name) }}" required class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">@error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Email</label><input type="email" name="email" value="{{ old('email', $cinema->email) }}" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label><input type="tel" name="phone" value="{{ old('phone', $cinema->phone) }}" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Site web</label><input type="url" name="website" value="{{ old('website', $cinema->website) }}" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm"></div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Adresse</h3>
                <div class="space-y-4">
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Adresse <span class="text-red-500">*</span></label><input type="text" name="address" value="{{ old('address', $cinema->address) }}" required class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm"></div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Ville <span class="text-red-500">*</span></label><input type="text" name="city" value="{{ old('city', $cinema->city) }}" required class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm"></div>
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Code postal</label><input type="text" name="postal_code" value="{{ old('postal_code', $cinema->postal_code) }}" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm"></div>
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Pays <span class="text-red-500">*</span></label><input type="text" name="country" value="{{ old('country', $cinema->country) }}" required class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label><input type="number" step="0.000001" name="latitude" value="{{ old('latitude', $cinema->latitude) }}" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm"></div>
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label><input type="number" step="0.000001" name="longitude" value="{{ old('longitude', $cinema->longitude) }}" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm"></div>
                    </div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Description</label><textarea name="description" rows="4" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">{{ old('description', $cinema->description) }}</textarea></div>
                </div>
            </div>
        </div>
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Paramètres</h3>
                <div class="space-y-4">
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Commission (%)</label><input type="number" step="0.01" name="commission_rate" value="{{ old('commission_rate', $cinema->commission_rate) }}" min="0" max="100" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Statut <span class="text-red-500">*</span></label><select name="status" required class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm"><option value="active" {{ old('status', $cinema->status) == 'active' ? 'selected' : '' }}>Actif</option><option value="inactive" {{ old('status', $cinema->status) == 'inactive' ? 'selected' : '' }}>Inactif</option><option value="pending" {{ old('status', $cinema->status) == 'pending' ? 'selected' : '' }}>En attente</option></select></div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="space-y-3">
                    <button type="submit" class="w-full bg-red-600 text-white py-2.5 px-4 rounded-lg hover:bg-red-700 transition-all shadow-md font-medium flex justify-center items-center"><span class="material-symbols-outlined mr-2 text-sm">save</span>Mettre à jour</button>
                    <a href="{{ route('admin.boxoffice.cinemas.index') }}" class="block w-full bg-gray-100 text-gray-700 py-2.5 px-4 rounded-lg hover:bg-gray-200 transition-all font-medium text-center">Annuler</a>
                </div>
            </div>
            @if($cinema->exists)
            <div class="bg-white rounded-xl shadow-sm border border-red-100 p-6">
                <h3 class="text-lg font-semibold text-red-800 mb-4 border-b border-red-200 pb-2">Zone dangereuse</h3>
                <form action="{{ route('admin.boxoffice.cinemas.destroy', $cinema->id) }}" method="POST" onsubmit="return confirm('Supprimer ce cinéma ?');">@csrf @method('DELETE')<button type="submit" class="w-full bg-red-50 text-red-700 py-2.5 px-4 rounded-lg hover:bg-red-100 transition-all font-medium flex justify-center items-center border border-red-200"><span class="material-symbols-outlined mr-2 text-sm">delete</span>Supprimer</button></form>
            </div>
            @endif
        </div>
    </div>
</form>

@if($cinema->exists)
<div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-center justify-between mb-4"><h3 class="text-lg font-semibold text-gray-800 flex items-center"><span class="material-symbols-outlined mr-2 text-red-600">event_seat</span>Salles</h3><a href="{{ route('admin.boxoffice.cinemas.rooms', $cinema->id) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center shadow-sm transition-colors"><span class="material-symbols-outlined mr-2">add</span>Gérer les salles</a></div>
    @if($cinema->rooms && $cinema->rooms->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">@foreach($cinema->rooms as $room)<div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition"><h4 class="font-semibold text-gray-800">{{ $room->name }}</h4><p class="text-sm text-gray-600 mt-1 flex items-center"><span class="material-symbols-outlined text-gray-400 mr-1 text-sm">event_seat</span>{{ $room->capacity }} places</p></div>@endforeach</div>
    @else
    <p class="text-gray-500 text-center py-4">Aucune salle configurée</p>
    @endif
</div>
@endif
@endsection
