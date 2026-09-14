@extends('layouts.app')

@section('title', 'Tickets Support - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Gestion des tickets</h1>
        <p class="text-gray-600 mt-2">Gérez les tickets de cinéma et réservations</p>
    </div>
    <a href="{{ route('admin.tickets.create') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center shadow-sm transition-colors">
        <span class="material-symbols-outlined mr-2">add_circle</span>
        Ajouter un ticket
    </a>
</div>

<!-- Statistiques -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
            </div>
            <div class="bg-blue-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-blue-600">confirmation_number</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">En attente</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['pending']) }}</p>
            </div>
            <div class="bg-yellow-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-yellow-600">schedule</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Confirmés</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['confirmed']) }}</p>
            </div>
            <div class="bg-green-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-green-600">check_circle</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Annulés</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['cancelled']) }}</p>
            </div>
            <div class="bg-red-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-red-600">cancel</span>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100">
    <div class="table-container">
        <table class="w-full min-w-max">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Siège</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Prix</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($tickets as $ticket)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">#{{ $ticket->id }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined text-base text-gray-400 mr-2">person</span>
                            {{ $ticket->holder_name }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined text-base text-gray-400 mr-2">event_seat</span>
                            {{ $ticket->seat_label }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                        {{ number_format($ticket->price_cents / 100, 2) }} €
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($ticket->status === 'confirmed')
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 border border-green-200">Confirmé</span>
                        @elseif($ticket->status === 'pending')
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">En attente</span>
                        @elseif($ticket->status === 'cancelled')
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 border border-red-200">Annulé</span>
                        @else
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 border border-gray-200">{{ ucfirst($ticket->status) }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined text-base text-gray-400 mr-1">calendar_today</span>
                            {{ $ticket->created_at->format('d/m/Y H:i') }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <button onclick="openResolveModal({{ $ticket->id }})" class="p-1 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded transition-colors" title="Gérer">
                            <span class="material-symbols-outlined">edit</span>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="bg-gray-100 p-4 rounded-full mb-3">
                                <span class="material-symbols-outlined text-3xl text-gray-400">confirmation_number</span>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Aucun ticket</h3>
                            <p class="text-gray-500 mt-1">Aucun ticket n'a été trouvé.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $tickets->links() }}
    </div>
</div>

<!-- Modal Résoudre -->
<div id="resolveModal" class="modal-overlay fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black bg-opacity-50">
    <div class="modal-content bg-white max-w-md w-full rounded-xl shadow-2xl">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-900">Gérer le ticket</h3>
            <button onclick="closeResolveModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="resolveForm" method="POST" class="p-6">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="status">Statut <span class="text-red-500">*</span></label>
                    <select name="status" id="status" required class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                        <option value="pending">En attente</option>
                        <option value="confirmed">Confirmé</option>
                        <option value="cancelled">Annulé</option>
                        <option value="refunded">Remboursé</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="notes">Notes</label>
                    <textarea name="notes" id="notes" rows="3" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm"></textarea>
                </div>
            </div>
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 mt-6">
                <button type="button" onclick="closeResolveModal()" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    Annuler
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors shadow-md font-medium flex items-center">
                    <span class="material-symbols-outlined mr-2 text-sm">save</span>
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openResolveModal(ticketId) {
    document.getElementById('resolveForm').action = '/admin/tickets/resolve/' + ticketId;
    document.getElementById('resolveModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeResolveModal() {
    document.getElementById('resolveModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeResolveModal();
});

document.getElementById('resolveModal')?.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-overlay')) closeResolveModal();
});
</script>
@endsection
