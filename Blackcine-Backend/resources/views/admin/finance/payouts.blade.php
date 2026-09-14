@extends('layouts.app')

@section('title', 'Gestion des Paiements')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-purple-50 to-pink-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-600 via-pink-600 to-fuchsia-600 shadow-lg">
        <div class="container mx-auto px-6 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">💸 Gestion des Paiements</h1>
                    <p class="text-purple-100">Gérez et suivez tous les paiements sortants</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.finance.reports') }}" class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-lg font-semibold transition backdrop-blur-sm">
                        ← Rapports
                    </a>
                    <button onclick="createPayout()" class="bg-white text-purple-600 px-6 py-3 rounded-lg font-semibold hover:bg-purple-50 transition">
                        + Nouveau Paiement
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 py-8">
        <!-- Stats rapides -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <span class="text-2xl">✅</span>
                        <span class="text-green-100 text-xs uppercase">Payés</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="text-2xl font-bold text-green-600">{{ number_format($stats['paid'] ?? 45230, 2) }} €</div>
                    <div class="text-xs text-gray-500 mt-1">125 paiements</div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-br from-yellow-500 to-orange-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <span class="text-2xl">⏳</span>
                        <span class="text-yellow-100 text-xs uppercase">En Attente</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="text-2xl font-bold text-yellow-600">{{ number_format($stats['pending'] ?? 8450, 2) }} €</div>
                    <div class="text-xs text-gray-500 mt-1">23 paiements</div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-br from-blue-500 to-indigo-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <span class="text-2xl">🔄</span>
                        <span class="text-blue-100 text-xs uppercase">En Traitement</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="text-2xl font-bold text-blue-600">{{ number_format($stats['processing'] ?? 3280, 2) }} €</div>
                    <div class="text-xs text-gray-500 mt-1">12 paiements</div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-br from-red-500 to-pink-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <span class="text-2xl">❌</span>
                        <span class="text-red-100 text-xs uppercase">Échoués</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="text-2xl font-bold text-red-600">{{ number_format($stats['failed'] ?? 420, 2) }} €</div>
                    <div class="text-xs text-gray-500 mt-1">3 paiements</div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Statut</label>
                    <select class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-purple-500 transition">
                        <option value="">Tous les statuts</option>
                        <option value="paid">Payé</option>
                        <option value="pending">En attente</option>
                        <option value="processing">En traitement</option>
                        <option value="failed">Échoué</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Bénéficiaire</label>
                    <input type="text" placeholder="Rechercher..." class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-purple-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Type</label>
                    <select class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-purple-500 transition">
                        <option value="">Tous les types</option>
                        <option value="partner">Partenaire</option>
                        <option value="affiliate">Affilié</option>
                        <option value="refund">Remboursement</option>
                        <option value="commission">Commission</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date début</label>
                    <input type="date" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-purple-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date fin</label>
                    <input type="date" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-purple-500 transition">
                </div>
            </div>
            <div class="mt-4 flex gap-3">
                <button class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg font-semibold hover:from-purple-700 hover:to-pink-700 transition shadow-lg">
                    Filtrer
                </button>
                <button class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition">
                    Réinitialiser
                </button>
            </div>
        </div>

        <!-- Liste des paiements -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-fuchsia-500 to-purple-500 px-6 py-4">
                <h2 class="text-xl font-bold text-white">📋 Liste des Paiements</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                <input type="checkbox" class="rounded">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Bénéficiaire</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Montant</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Méthode</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($payouts ?? [] as $payout)
                            <tr class="hover:bg-purple-50 transition">
                                <td class="px-6 py-4">
                                    <input type="checkbox" class="rounded">
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">#{{ $payout->id }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $payout->recipient_name }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded">{{ $payout->type }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-purple-600">{{ number_format($payout->amount, 2) }} €</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $payout->method }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'paid' => 'bg-green-100 text-green-800',
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'processing' => 'bg-blue-100 text-blue-800',
                                            'failed' => 'bg-red-100 text-red-800'
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 text-xs rounded-full font-semibold {{ $statusColors[$payout->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($payout->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $payout->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <button class="text-blue-600 hover:text-blue-800 text-sm">Voir</button>
                                        @if($payout->status === 'pending')
                                            <button class="text-green-600 hover:text-green-800 text-sm">Valider</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            @foreach(range(1, 15) as $i)
                                @php
                                    $statuses = ['paid', 'pending', 'processing', 'failed'];
                                    $types = ['partner', 'affiliate', 'refund', 'commission'];
                                    $methods = ['Virement', 'PayPal', 'Carte', 'Chèque'];
                                    $status = $statuses[array_rand($statuses)];
                                    $statusColors = [
                                        'paid' => 'bg-green-100 text-green-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'processing' => 'bg-blue-100 text-blue-800',
                                        'failed' => 'bg-red-100 text-red-800'
                                    ];
                                @endphp
                                <tr class="hover:bg-purple-50 transition">
                                    <td class="px-6 py-4">
                                        <input type="checkbox" class="rounded">
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">#PY{{ 10000 + $i }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ ['Cinéma Gaumont', 'UGC Paris', 'MK2 Nation', 'Pathé Belle-Époque', 'CGR Tours'][rand(0, 4)] }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded">{{ ucfirst($types[array_rand($types)]) }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-purple-600">{{ number_format(rand(100, 5000), 2) }} €</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $methods[array_rand($methods)] }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 text-xs rounded-full font-semibold {{ $statusColors[$status] }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ now()->subDays(rand(0, 60))->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            <button onclick="viewPayout({{ $i }})" class="text-blue-600 hover:text-blue-800 text-sm">Voir</button>
                                            @if($status === 'pending')
                                                <button onclick="validatePayout({{ $i }})" class="text-green-600 hover:text-green-800 text-sm">Valider</button>
                                            @endif
                                        </div>
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
                    Affichage de 1 à 15 sur 163 paiements
                </div>
                <div class="flex gap-2">
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Précédent</button>
                    <button class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">1</button>
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">2</button>
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">3</button>
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Suivant</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function createPayout() {
    alert('Nouveau paiement - À implémenter');
}

function viewPayout(id) {
    alert('Voir détails du paiement #' + id);
}

function validatePayout(id) {
    if(confirm('Valider ce paiement ?')) {
        alert('Paiement #' + id + ' validé');
    }
}
</script>
@endsection
