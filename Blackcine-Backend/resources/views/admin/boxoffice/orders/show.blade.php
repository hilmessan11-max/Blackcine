@extends('layouts.app')

@section('title', 'Commande #' . $order->id . ' - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div><h1 class="text-3xl font-bold text-gray-800">Commande #{{ $order->id }}</h1><p class="text-gray-600 mt-2">Détails de la commande</p></div>
    <a href="{{ route('admin.boxoffice.orders.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors"><span class="material-symbols-outlined mr-1">arrow_back</span>Retour</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Informations principales -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2 flex items-center"><span class="material-symbols-outlined mr-2 text-red-600">receipt_long</span>Informations commande</h3>
            <div class="grid grid-cols-2 gap-4">
                <div><p class="text-sm text-gray-500">ID Commande</p><p class="text-base font-semibold text-gray-900">#{{ $order->id }}</p></div>
                <div><p class="text-sm text-gray-500">Date</p><p class="text-base font-semibold text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</p></div>
                <div><p class="text-sm text-gray-500">Email</p><p class="text-base font-semibold text-gray-900">{{ $order->email }}</p></div>
                <div><p class="text-sm text-gray-500">Téléphone</p><p class="text-base font-semibold text-gray-900">{{ $order->phone ?? 'N/A' }}</p></div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2 flex items-center"><span class="material-symbols-outlined mr-2 text-red-600">confirmation_number</span>Billets</h3>
            <div class="space-y-3">
                @foreach($order->tickets ?? [] as $ticket)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center gap-4">
                        <div class="bg-blue-100 p-3 rounded-full"><span class="material-symbols-outlined text-blue-600">movie</span></div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $ticket->showtime->title->name ?? 'Film' }}</p>
                            <p class="text-sm text-gray-500">{{ $ticket->showtime->cinema->name ?? 'Cinéma' }} • Salle {{ $ticket->showtime->room->name ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-500">{{ $ticket->showtime->start_time->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">{{ number_format($ticket->price_cents / 100, 2) }} €</p>
                        <p class="text-xs text-gray-500">Siège {{ $ticket->seat_number ?? 'N/A' }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Statut</h3>
            <div class="space-y-3">
                @if($order->status === 'confirmed')
                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg flex items-center"><span class="material-symbols-outlined text-green-600 mr-2">check_circle</span><span class="font-medium text-green-800">Confirmée</span></div>
                @elseif($order->status === 'pending')
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg flex items-center"><span class="material-symbols-outlined text-yellow-600 mr-2">schedule</span><span class="font-medium text-yellow-800">En attente</span></div>
                @elseif($order->status === 'cancelled')
                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg flex items-center"><span class="material-symbols-outlined text-red-600 mr-2">cancel</span><span class="font-medium text-red-800">Annulée</span></div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Récapitulatif</h3>
            <div class="space-y-3">
                <div class="flex justify-between"><span class="text-gray-600">Sous-total</span><span class="font-medium text-gray-900">{{ number_format(($order->total_cents - ($order->fees_cents ?? 0)) / 100, 2) }} €</span></div>
                @if($order->fees_cents)
                <div class="flex justify-between"><span class="text-gray-600">Frais</span><span class="font-medium text-gray-900">{{ number_format($order->fees_cents / 100, 2) }} €</span></div>
                @endif
                <div class="flex justify-between pt-3 border-t border-gray-200"><span class="text-lg font-semibold text-gray-900">Total</span><span class="text-lg font-bold text-red-600">{{ number_format($order->total_cents / 100, 2) }} €</span></div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Paiement</h3>
            <div class="space-y-2">
                <div class="flex justify-between text-sm"><span class="text-gray-600">Méthode</span><span class="font-medium text-gray-900">{{ $order->payment_method ?? 'Carte bancaire' }}</span></div>
                <div class="flex justify-between text-sm"><span class="text-gray-600">Transaction ID</span><span class="font-medium text-gray-900 text-xs">{{ $order->transaction_id ?? 'N/A' }}</span></div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <button class="w-full bg-red-600 text-white py-2.5 px-4 rounded-lg hover:bg-red-700 transition-all shadow-md font-medium flex justify-center items-center"><span class="material-symbols-outlined mr-2 text-sm">print</span>Imprimer facture</button>
        </div>
    </div>
</div>
@endsection
