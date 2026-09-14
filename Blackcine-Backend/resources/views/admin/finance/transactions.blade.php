@extends('layouts.app')

@section('title', 'Toutes les Transactions')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 shadow-lg">
        <div class="container mx-auto px-6 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">💳 Toutes les Transactions</h1>
                    <p class="text-blue-100">Historique complet et détaillé des transactions</p>
                </div>
                <div class="flex gap-3">
                    <button onclick="exportTransactions()" class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg font-semibold transition backdrop-blur-sm flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Exporter
                    </button>
                    <a href="{{ route('admin.finance.reports') }}" class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition">
                        ← Rapports
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 py-8">
        <!-- Filtres avancés -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h3 class="text-lg font-bold text-gray-900 mb-4">🔍 Filtres Avancés</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Recherche</label>
                    <input type="text" placeholder="ID, email, nom..." class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Type</label>
                    <select class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 transition">
                        <option value="">Tous</option>
                        <option value="ticket">Billetterie</option>
                        <option value="vod">VOD</option>
                        <option value="subscription">Abonnement</option>
                        <option value="other">Autre</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Statut</label>
                    <select class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 transition">
                        <option value="">Tous</option>
                        <option value="completed">Complété</option>
                        <option value="pending">En attente</option>
                        <option value="failed">Échoué</option>
                        <option value="refunded">Remboursé</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Méthode</label>
                    <select class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 transition">
                        <option value="">Toutes</option>
                        <option value="card">Carte bancaire</option>
                        <option value="paypal">PayPal</option>
                        <option value="transfer">Virement</option>
                        <option value="other">Autre</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date début</label>
                    <input type="date" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 transition" value="{{ now()->subDays(30)->format('Y-m-d') }}">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date fin</label>
                    <input type="date" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 transition" value="{{ now()->format('Y-m-d') }}">
                </div>
            </div>
            <div class="mt-4 flex gap-3">
                <button class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg font-semibold hover:from-blue-700 hover:to-indigo-700 transition shadow-lg">
                    Appliquer les filtres
                </button>
                <button class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition">
                    Réinitialiser
                </button>
            </div>
        </div>

        <!-- Liste des transactions -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-500 to-purple-500 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-white">📊 Transactions (derniers 30 jours)</h2>
                    <div class="text-white text-sm">
                        Total: <span class="font-bold">{{ number_format($total_count ?? 1523, 0) }}</span> transactions - 
                        <span class="font-bold">{{ number_format($total_amount ?? 127450, 2) }} €</span>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Montant HT</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">TVA (20%)</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Montant TTC</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Méthode</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date/Heure</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($transactions ?? [] as $transaction)
                            <tr class="hover:bg-indigo-50 transition">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">#TRX-{{ $transaction->id }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $transaction->user_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $transaction->user_email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">{{ $transaction->type }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $transaction->description }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ number_format($transaction->amount_ht, 2) }} €</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ number_format($transaction->vat, 2) }} €</td>
                                <td class="px-6 py-4 text-sm font-semibold text-indigo-600">{{ number_format($transaction->amount_ttc, 2) }} €</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $transaction->payment_method }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'completed' => 'bg-green-100 text-green-800',
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'failed' => 'bg-red-100 text-red-800',
                                            'refunded' => 'bg-purple-100 text-purple-800'
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 text-xs rounded-full font-semibold {{ $statusColors[$transaction->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $transaction->created_at->format('d/m/Y H:i:s') }}</td>
                                <td class="px-6 py-4">
                                    <button onclick="viewTransaction({{ $transaction->id }})" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">Détails</button>
                                </td>
                            </tr>
                        @empty
                            @foreach(range(1, 20) as $i)
                                @php
                                    $types = ['Ticket', 'VOD', 'Abonnement', 'Autre'];
                                    $methods = ['Carte bancaire', 'PayPal', 'Virement', 'Autre'];
                                    $statuses = ['completed', 'pending', 'failed', 'refunded'];
                                    $names = ['Jean Dupont', 'Marie Martin', 'Pierre Durand', 'Sophie Bernard', 'Luc Petit'];
                                    $status = $statuses[array_rand($statuses)];
                                    $statusColors = [
                                        'completed' => 'bg-green-100 text-green-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'failed' => 'bg-red-100 text-red-800',
                                        'refunded' => 'bg-purple-100 text-purple-800'
                                    ];
                                    $amountHT = rand(10, 200);
                                    $vat = $amountHT * 0.20;
                                    $amountTTC = $amountHT + $vat;
                                @endphp
                                <tr class="hover:bg-indigo-50 transition">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">#TRX-{{ 100000 + $i }}</td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $names[array_rand($names)] }}</div>
                                        <div class="text-xs text-gray-500">{{ strtolower(str_replace(' ', '.', $names[array_rand($names)])) }}@example.com</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">{{ $types[array_rand($types)] }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ ['Séance Inception', 'Film Avatar', 'Pass Mensuel', 'Place VIP'][rand(0, 3)] }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ number_format($amountHT, 2) }} €</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ number_format($vat, 2) }} €</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-indigo-600">{{ number_format($amountTTC, 2) }} €</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $methods[array_rand($methods)] }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 text-xs rounded-full font-semibold {{ $statusColors[$status] }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ now()->subDays(rand(0, 30))->format('d/m/Y H:i:s') }}</td>
                                    <td class="px-6 py-4">
                                        <button onclick="viewTransaction({{ $i }})" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">Détails</button>
                                    </td>
                                </tr>
                            @endforeach
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t border-gray-200">
                <div class="text-sm text-gray-600">
                    Affichage de 1 à 20 sur 1523 transactions
                </div>
                <div class="flex gap-2">
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Précédent</button>
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">1</button>
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">2</button>
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">3</button>
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">...</button>
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">77</button>
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Suivant</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function exportTransactions() {
    alert('Export des transactions en cours...');
}

function viewTransaction(id) {
    alert('Détails de la transaction #' + id);
}
</script>
@endsection
