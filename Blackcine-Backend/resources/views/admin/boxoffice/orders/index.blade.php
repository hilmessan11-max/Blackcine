@extends('layouts.app')

@section('title', 'Commandes - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div><h1 class="text-3xl font-bold text-gray-800">Commandes & Paiements</h1><p class="text-gray-600 mt-2">Gestion des commandes de billets</p></div>
    <a href="{{ route('admin.boxoffice.orders.export') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center shadow-sm transition-colors"><span class="material-symbols-outlined mr-2">download</span>Exporter CSV</a>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4"><div class="flex items-center justify-between"><div><p class="text-sm text-gray-500">Total commandes</p><p class="text-2xl font-bold text-gray-900">{{ $orders->total() }}</p></div><div class="bg-blue-50 p-3 rounded-full"><span class="material-symbols-outlined text-blue-600">receipt_long</span></div></div></div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4"><div class="flex items-center justify-between"><div><p class="text-sm text-gray-500">En attente</p><p class="text-2xl font-bold text-gray-900">{{ $orders->where('status', 'pending')->count() }}</p></div><div class="bg-yellow-50 p-3 rounded-full"><span class="material-symbols-outlined text-yellow-600">schedule</span></div></div></div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4"><div class="flex items-center justify-between"><div><p class="text-sm text-gray-500">Confirmées</p><p class="text-2xl font-bold text-gray-900">{{ $orders->where('status', 'confirmed')->count() }}</p></div><div class="bg-green-50 p-3 rounded-full"><span class="material-symbols-outlined text-green-600">check_circle</span></div></div></div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4"><div class="flex items-center justify-between"><div><p class="text-sm text-gray-500">Annulées</p><p class="text-2xl font-bold text-gray-900">{{ $orders->where('status', 'cancelled')->count() }}</p></div><div class="bg-red-50 p-3 rounded-full"><span class="material-symbols-outlined text-red-600">cancel</span></div></div></div>
</div>

<!-- Filtres -->
<div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 mb-6">
    <form method="GET" action="{{ route('admin.boxoffice.orders.index') }}" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative"><span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><span class="material-symbols-outlined text-gray-400">search</span></span><input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher (Email, ID)..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors"></div>
        <select name="status" class="w-full md:w-48 px-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors"><option value="">Tous statuts</option>@foreach($statuses as $status)<option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>@endforeach</select>
        <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-900 transition-colors">Filtrer</button>
        <a href="{{ route('admin.boxoffice.orders.index') }}" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-center">Réinitialiser</a>
    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Client</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $order->email }}</td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ number_format($order->total_cents / 100, 2) }} €</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($order->status === 'confirmed')
                            <span class="px-2.5 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full border border-green-200">Confirmée</span>
                        @elseif($order->status === 'pending')
                            <span class="px-2.5 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full border border-yellow-200">En attente</span>
                        @elseif($order->status === 'cancelled')
                            <span class="px-2.5 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full border border-red-200">Annulée</span>
                        @else
                            <span class="px-2.5 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full border border-gray-200">{{ ucfirst($order->status) }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.boxoffice.orders.show', $order->id) }}" class="p-1 text-blue-600 hover:bg-blue-50 rounded transition" title="Voir détails"><span class="material-symbols-outlined">visibility</span></a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center"><div class="bg-gray-100 p-4 rounded-full mb-3"><span class="material-symbols-outlined text-3xl text-gray-400">receipt_long</span></div><h3 class="text-lg font-medium text-gray-900">Aucune commande</h3></div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200">{{ $orders->links() }}</div>
</div>
@endsection
