@extends('layouts.app')

@section('title', 'Cinémas - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Cinémas partenaires</h1>
        <p class="text-gray-600 mt-2">Gérez les cinémas et leurs salles</p>
    </div>
    <a href="{{ route('admin.boxoffice.cinemas.create') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center shadow-sm transition-colors">
        <span class="material-symbols-outlined mr-2">add_circle</span>
        Ajouter un cinéma
    </a>
</div>

<!-- Stats rapides -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total cinémas</p>
                <p class="text-2xl font-bold text-gray-900">{{ $cinemas->total() }}</p>
            </div>
            <div class="bg-red-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-red-600">theater_comedy</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Cinémas actifs</p>
                <p class="text-2xl font-bold text-gray-900">{{ $cinemas->where('is_active', true)->count() }}</p>
            </div>
            <div class="bg-green-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-green-600">check_circle</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total salles</p>
                <p class="text-2xl font-bold text-gray-900">{{ $cinemas->sum(fn($c) => $c->rooms->count()) }}</p>
            </div>
            <div class="bg-blue-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-blue-600">meeting_room</span>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100">
    <div class="table-container">
        <table class="w-full min-w-max">
            <thead class="bg-gray-50 border-b border-gray-200"> 
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Cinéma</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Localisation</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Salles</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Commission</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($cinemas as $cinema)
                <tr class="hover:bg-gray-50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="bg-gray-100 group-hover:bg-red-50 p-2 rounded-lg mr-3 transition-colors">
                                <span class="material-symbols-outlined text-gray-600 group-hover:text-red-600">theater_comedy</span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $cinema->name }}</div>
                                @if($cinema->company_name)
                                    <div class="text-xs text-gray-500 mt-0.5">{{ $cinema->company_name }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <span class="material-symbols-outlined text-gray-400 mr-2 text-base">location_on</span>
                            <div>
                                <div>{{ $cinema->city ?? 'N/A' }}</div>
                                @if($cinema->country)
                                    <div class="text-xs text-gray-400">{{ $cinema->country }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined text-blue-500 mr-1 text-base">meeting_room</span>
                            <span class="text-sm font-medium text-gray-900">{{ $cinema->rooms->count() }}</span>
                            <span class="text-xs text-gray-500 ml-1">salle(s)</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            {{ $cinema->commission_rate ?? 0 }}%
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($cinema->is_active)
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 border border-green-200">
                                Actif
                            </span>
                        @else
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                                Inactif
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('admin.boxoffice.cinemas.rooms', $cinema->id) }}" class="p-1 text-purple-600 hover:text-purple-900 hover:bg-purple-50 rounded transition-colors" title="Gérer les salles">
                                <span class="material-symbols-outlined">meeting_room</span>
                            </a>
                            <a href="{{ route('admin.boxoffice.cinemas.edit', $cinema->id) }}" class="p-1 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded transition-colors" title="Modifier">
                                <span class="material-symbols-outlined">edit</span>
                            </a>
                            <form action="{{ route('admin.boxoffice.cinemas.destroy', $cinema->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce cinéma ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1 text-red-600 hover:text-red-900 hover:bg-red-50 rounded transition-colors" title="Supprimer">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="bg-gray-100 p-4 rounded-full mb-3">
                                <span class="material-symbols-outlined text-3xl text-gray-400">theater_comedy</span>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Aucun cinéma</h3>
                            <p class="text-gray-500 mt-1">Ajoutez votre premier cinéma partenaire.</p>
                            <a href="{{ route('admin.boxoffice.cinemas.create') }}" class="mt-4 text-red-600 hover:text-red-700 font-medium">
                                Ajouter un cinéma
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $cinemas->links() }}
    </div>
</div>
@endsection
