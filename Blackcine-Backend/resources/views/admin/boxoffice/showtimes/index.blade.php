@extends('layouts.app')

@section('title', 'Séances - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800 flex items-center">
            <span class="material-symbols-outlined mr-3 text-3xl text-red-600">schedule</span>
            Séances
        </h1>
        <p class="text-gray-600 mt-2">Gestion des séances de cinéma</p>
    </div>
    <a href="{{ route('admin.boxoffice.showtimes.create') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition shadow-md hover:shadow-lg flex items-center">
        <span class="material-symbols-outlined mr-2">add</span>
        Créer une séance
    </a>
</div>

<!-- Filtres -->
<div class="card bg-white shadow-lg p-6 mb-6">
    <form method="GET" action="{{ route('admin.boxoffice.showtimes.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                <span class="material-symbols-outlined mr-1 text-base text-red-600">theaters</span>
                Cinéma
            </label>
            <select name="cinema_id" class="form-input w-full px-4 py-2">
                <option value="">Tous</option>
                @foreach($cinemas as $cinema)
                    <option value="{{ $cinema->id }}" {{ request('cinema_id') == $cinema->id ? 'selected' : '' }}>{{ $cinema->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                <span class="material-symbols-outlined mr-1 text-base text-red-600">movie</span>
                Film
            </label>
            <select name="title_id" class="form-input w-full px-4 py-2">
                <option value="">Tous</option>
                @foreach($titles as $title)
                    <option value="{{ $title->id }}" {{ request('title_id') == $title->id ? 'selected' : '' }}>{{ $title->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                <span class="material-symbols-outlined mr-1 text-base text-red-600">calendar_today</span>
                Date
            </label>
            <input type="date" name="date" value="{{ request('date') }}" class="form-input w-full px-4 py-2">
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition shadow-md hover:shadow-lg flex items-center justify-center">
                <span class="material-symbols-outlined mr-2 text-lg">filter_alt</span>
                Filtrer
            </button>
        </div>
    </form>
</div>

<!-- Liste des séances -->
<div class="card bg-white shadow-lg overflow-hidden">
    <div class="table-container">
        <table class="w-full min-w-max">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Film</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cinéma / Salle</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date & Heure</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prix</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($showtimes as $showtime)
                <tr class="table-row">
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900 flex items-center">
                            <span class="material-symbols-outlined mr-2 text-base text-gray-400">movie</span>
                            {{ $showtime->title->name ?? 'N/A' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined mr-1 text-base text-gray-400">theaters</span>
                            {{ $showtime->room->cinema->name ?? 'N/A' }}
                        </div>
                        <div class="text-xs text-gray-500 flex items-center mt-1">
                            <span class="material-symbols-outlined mr-1 text-xs text-gray-400">event_seat</span>
                            {{ $showtime->room->name ?? 'N/A' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 flex items-center">
                        <span class="material-symbols-outlined mr-1 text-base text-gray-400">schedule</span>
                        {{ $showtime->starts_at ? $showtime->starts_at->format('d/m/Y H:i') : 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 flex items-center">
                        <span class="material-symbols-outlined mr-1 text-base text-gray-400">payments</span>
                        {{ number_format($showtime->base_price_cents / 100, 2) }} €
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full flex items-center w-fit bg-gray-100 text-gray-800">
                            <span class="material-symbols-outlined text-xs mr-1">info</span>
                            {{ $showtime->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.boxoffice.showtimes.edit', $showtime->id) }}" class="text-blue-600 hover:text-blue-800 transition-colors" title="Modifier">
                                <span class="material-symbols-outlined text-xl">edit</span>
                            </a>
                            <form action="{{ route('admin.boxoffice.showtimes.destroy', $showtime->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cette séance ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 transition-colors" title="Supprimer">
                                    <span class="material-symbols-outlined text-xl">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <span class="material-symbols-outlined text-6xl text-gray-400 mb-4 block">schedule</span>
                        <p class="text-lg">Aucune séance trouvée</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $showtimes->links() }}
    </div>
</div>
@endsection

