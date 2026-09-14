@extends('layouts.app')

@section('title', 'Gestion des salles - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Gestion des salles</h1>
        <p class="text-gray-600 mt-2">Cinéma : {{ $cinema->name }}</p>
    </div>
    <a href="{{ route('admin.boxoffice.cinemas.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center">
        <span class="material-symbols-outlined mr-1">arrow_back</span>
        Retour aux cinémas
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Liste des salles -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Salles existantes</h3>
                <span class="text-xs text-gray-500">{{ $rooms->count() }} salle(s)</span>
            </div>
            <div class="table-container">
                <table class="w-full min-w-max">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Capacité</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type d'écran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($rooms as $room)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $room->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $room->capacity }} places</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $room->screen_type ?? 'Standard' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $room->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $room->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <button class="text-blue-600 hover:text-blue-900 mr-3" title="Modifier">
                                    <span class="material-symbols-outlined">edit</span>
                                </button>
                                <button class="text-red-600 hover:text-red-900" title="Supprimer">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                Aucune salle configurée pour ce cinéma.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Ajouter une salle -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900">Ajouter une salle</h3>
            </div>
            <form action="{{ route('admin.boxoffice.cinemas.rooms.store', $cinema->id) }}" method="POST" class="p-6">
                @csrf
                
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nom de la salle <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" required placeholder="Ex: Salle 1, IMAX..."
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm form-input p-2">
                    </div>

                    <div>
                        <label for="capacity" class="block text-sm font-medium text-gray-700">Capacité (places) <span class="text-red-500">*</span></label>
                        <input type="number" name="capacity" id="capacity" required min="1"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm form-input p-2">
                    </div>

                    <div>
                        <label for="screen_type" class="block text-sm font-medium text-gray-700">Type d'écran</label>
                        <select name="screen_type" id="screen_type"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm form-input p-2">
                            <option value="Standard">Standard</option>
                            <option value="IMAX">IMAX</option>
                            <option value="3D">3D</option>
                            <option value="4DX">4DX</option>
                            <option value="Dolby Cinema">Dolby Cinema</option>
                        </select>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" checked
                            class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            Salle active
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition-colors shadow-sm">
                        Ajouter la salle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
