@extends('layouts.app')

@section('title', 'Tickets Support - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div><h1 class="text-3xl font-bold text-gray-800">Support & Tickets</h1><p class="text-gray-600 mt-2">Gestion des demandes d'assistance</p></div>
    <a href="{{ route('admin.support.tickets.create') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center shadow-sm transition-colors"><span class="material-symbols-outlined mr-2">add_circle</span>Nouveau Ticket</a>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center justify-between">
        <div><p class="text-sm text-gray-500">Total Tickets</p><p class="text-2xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p></div>
        <div class="bg-gray-100 p-3 rounded-full"><span class="material-symbols-outlined text-gray-600">confirmation_number</span></div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center justify-between">
        <div><p class="text-sm text-gray-500">Ouverts</p><p class="text-2xl font-bold text-yellow-600">{{ $stats['open'] ?? 0 }}</p></div>
        <div class="bg-yellow-50 p-3 rounded-full"><span class="material-symbols-outlined text-yellow-600">lock_open</span></div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center justify-between">
        <div><p class="text-sm text-gray-500">En cours</p><p class="text-2xl font-bold text-blue-600">{{ $stats['in_progress'] ?? 0 }}</p></div>
        <div class="bg-blue-50 p-3 rounded-full"><span class="material-symbols-outlined text-blue-600">hourglass_empty</span></div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center justify-between">
        <div><p class="text-sm text-gray-500">Résolus</p><p class="text-2xl font-bold text-green-600">{{ $stats['closed'] ?? 0 }}</p></div>
        <div class="bg-green-50 p-3 rounded-full"><span class="material-symbols-outlined text-green-600">check_circle</span></div>
    </div>
</div>

<!-- Filtres -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><span class="material-symbols-outlined text-gray-400">search</span></span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..." class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors">
        </div>
        <select name="status" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors">
            <option value="">Tous statuts</option>
            <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Ouvert</option>
            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>En cours</option>
            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Fermé</option>
        </select>
        <select name="priority" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors">
            <option value="">Toutes priorités</option>
            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Basse</option>
            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Moyenne</option>
            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>Haute</option>
            <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgente</option>
        </select>
        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-900 transition-colors flex justify-center items-center"><span class="material-symbols-outlined mr-2">filter_alt</span>Filtrer</button>
    </form>
</div>

<!-- Liste -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Sujet</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Utilisateur</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Priorité</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Statut</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($tickets as $ticket)
            <tr class="hover:bg-gray-50 transition-colors group">
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="bg-red-50 p-2 rounded-lg mr-3 group-hover:bg-red-100 transition-colors"><span class="material-symbols-outlined text-red-600">confirmation_number</span></div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">#{{ $ticket->id }} - {{ $ticket->subject }}</div>
                            <div class="text-xs text-gray-500">{{ Str::limit($ticket->message, 40) }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm text-gray-900">{{ $ticket->user_name ?? 'Anonyme' }}</div>
                    <div class="text-xs text-gray-500">{{ $ticket->user_email }}</div>
                </td>
                <td class="px-6 py-4">
                    @php
                        $pColors = ['low' => 'bg-gray-100 text-gray-800', 'medium' => 'bg-blue-100 text-blue-800', 'high' => 'bg-orange-100 text-orange-800', 'urgent' => 'bg-red-100 text-red-800'];
                        $pIcons = ['low' => 'arrow_downward', 'medium' => 'arrow_forward', 'high' => 'arrow_upward', 'urgent' => 'local_fire_department'];
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $pColors[$ticket->priority] ?? 'bg-gray-100 text-gray-800' }}">
                        <span class="material-symbols-outlined text-[10px] mr-1">{{ $pIcons[$ticket->priority] ?? 'help' }}</span>{{ ucfirst($ticket->priority) }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    @php
                        $sColors = ['open' => 'bg-yellow-100 text-yellow-800', 'in_progress' => 'bg-blue-100 text-blue-800', 'closed' => 'bg-green-100 text-green-800'];
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $sColors[$ticket->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                <td class="px-6 py-4 text-right space-x-2">
                    <a href="{{ route('admin.support.tickets.show', $ticket) }}" class="text-blue-600 hover:text-blue-900 p-1 hover:bg-blue-50 rounded transition-colors"><span class="material-symbols-outlined">visibility</span></a>
                    <form action="{{ route('admin.support.tickets.destroy', $ticket) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900 p-1 hover:bg-red-50 rounded transition-colors"><span class="material-symbols-outlined">delete</span></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center">
                    <div class="bg-gray-100 p-4 rounded-full mb-3 inline-block"><span class="material-symbols-outlined text-3xl text-gray-400">support_agent</span></div>
                    <h3 class="text-lg font-medium text-gray-900">Aucun ticket</h3>
                    <p class="text-gray-500 mt-1">Tout est calme pour le moment.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($tickets->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">{{ $tickets->links() }}</div>
    @endif
</div>
@endsection
