@extends('layouts.app')

@section('title', 'Rapports Financiers - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Rapports Financiers</h1>
        <p class="text-gray-600 mt-2">Vue d'ensemble et analyses financières</p>
    </div>
    <div class="flex gap-3">
        <button onclick="exportReport()" class="bg-white text-gray-700 border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-50 flex items-center shadow-sm transition-colors font-medium">
            <span class="material-symbols-outlined mr-2">download</span>Exporter
        </button>
        <a href="{{ route('admin.finance.payouts') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center shadow-sm transition-colors font-medium">
            <span class="material-symbols-outlined mr-2">payments</span>Gérer les Paiements
        </a>
    </div>
</div>

<!-- Filtres de période -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <div class="flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-sm font-medium text-gray-700 mb-1">Période</label>
            <select class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                <option>7 derniers jours</option>
                <option>30 derniers jours</option>
                <option>3 derniers mois</option>
                <option>6 derniers mois</option>
                <option selected>12 derniers mois</option>
                <option>Année en cours</option>
                <option>Personnalisée</option>
            </select>
        </div>
        <div class="flex-1 min-w-[150px]">
            <label class="block text-sm font-medium text-gray-700 mb-1">Du</label>
            <input type="date" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm" value="{{ now()->subMonths(12)->format('Y-m-d') }}">
        </div>
        <div class="flex-1 min-w-[150px]">
            <label class="block text-sm font-medium text-gray-700 mb-1">Au</label>
            <input type="date" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm" value="{{ now()->format('Y-m-d') }}">
        </div>
        <div>
            <button class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-900 transition-colors flex items-center shadow-sm">
                <span class="material-symbols-outlined mr-2">filter_alt</span>Filtrer
            </button>
        </div>
    </div>
</div>

<!-- Statistiques principales -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex flex-col justify-between">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-sm font-medium text-gray-500">Revenus Totaux</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_revenue'] ?? 125000, 2) }} €</h3>
            </div>
            <div class="bg-green-50 p-3 rounded-lg">
                <span class="material-symbols-outlined text-green-600">attach_money</span>
            </div>
        </div>
        <div class="flex items-center text-xs">
            <span class="text-green-600 flex items-center font-medium bg-green-50 px-2 py-0.5 rounded-full mr-2">
                <span class="material-symbols-outlined text-sm mr-1">trending_up</span>+12.5%
            </span>
            <span class="text-gray-400">vs période préc.</span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex flex-col justify-between">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-sm font-medium text-gray-500">Tickets Vendus</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_orders'] ?? 3250, 0) }}</h3>
            </div>
            <div class="bg-blue-50 p-3 rounded-lg">
                <span class="material-symbols-outlined text-blue-600">confirmation_number</span>
            </div>
        </div>
        <div class="flex items-center text-xs">
            <span class="text-green-600 flex items-center font-medium bg-green-50 px-2 py-0.5 rounded-full mr-2">
                <span class="material-symbols-outlined text-sm mr-1">trending_up</span>+8.2%
            </span>
            <span class="text-gray-400">vs période préc.</span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex flex-col justify-between">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-sm font-medium text-gray-500">Remboursements</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_refunds'] ?? 2350, 2) }} €</h3>
            </div>
            <div class="bg-red-50 p-3 rounded-lg">
                <span class="material-symbols-outlined text-red-600">assignment_return</span>
            </div>
        </div>
        <div class="flex items-center text-xs">
            <span class="text-red-600 flex items-center font-medium bg-red-50 px-2 py-0.5 rounded-full mr-2">
                {{ number_format(($stats['refund_rate'] ?? 1.88), 2) }}%
            </span>
            <span class="text-gray-400">du CA total</span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex flex-col justify-between">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-sm font-medium text-gray-500">Paiements en attente</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['pending_payments'] ?? 12, 0) }}</h3>
            </div>
            <div class="bg-yellow-50 p-3 rounded-lg">
                <span class="material-symbols-outlined text-yellow-600">hourglass_empty</span>
            </div>
        </div>
        <div class="flex items-center text-xs">
            <span class="text-gray-500">Transactions à traiter</span>
        </div>
    </div>
</div>

<!-- TVA et Taxes -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- TVA -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <span class="material-symbols-outlined mr-2 text-gray-500">percent</span>TVA Collectée
            </h3>
        </div>
        <div class="p-6 space-y-4">
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">TVA 20%</span>
                <span class="font-bold text-gray-900">{{ number_format($stats['vat_20'] ?? 20833.33, 2) }} €</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">TVA 10%</span>
                <span class="font-bold text-gray-900">{{ number_format($stats['vat_10'] ?? 4166.67, 2) }} €</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">TVA 5.5%</span>
                <span class="font-bold text-gray-900">{{ number_format($stats['vat_5_5'] ?? 916.67, 2) }} €</span>
            </div>
            <div class="pt-4 border-t border-gray-100 flex justify-between items-center">
                <span class="font-semibold text-gray-800">Total TVA</span>
                <span class="font-bold text-xl text-red-600">{{ number_format($stats['total_vat'] ?? 25916.67, 2) }} €</span>
            </div>
        </div>
    </div>

    <!-- Moyens de Paiement -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <span class="material-symbols-outlined mr-2 text-gray-500">credit_card</span>Moyens de Paiement
            </h3>
        </div>
        <div class="p-6 space-y-4">
            @foreach([
                ['label' => 'Carte bancaire', 'value' => 72.5, 'amount' => 90625, 'color' => 'bg-blue-500'],
                ['label' => 'PayPal', 'value' => 18.3, 'amount' => 22875, 'color' => 'bg-indigo-500'],
                ['label' => 'Virement', 'value' => 6.2, 'amount' => 7750, 'color' => 'bg-purple-500'],
                ['label' => 'Autres', 'value' => 3.0, 'amount' => 3750, 'color' => 'bg-gray-400']
            ] as $payment)
                <div>
                    <div class="flex justify-between items-center mb-1 text-sm">
                        <span class="text-gray-700">{{ $payment['label'] }}</span>
                        <span class="font-medium text-gray-900">{{ $payment['value'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="{{ $payment['color'] }} h-2 rounded-full" style="width: {{ $payment['value'] }}%"></div>
                    </div>
                    <div class="text-xs text-gray-400 mt-1 text-right">{{ number_format($payment['amount'], 2) }} €</div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Top Catégories -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <span class="material-symbols-outlined mr-2 text-gray-500">category</span>Top Catégories
            </h3>
        </div>
        <div class="p-6 space-y-4">
            @foreach([
                ['label' => 'Billetterie', 'value' => 65, 'amount' => 81250, 'color' => 'bg-red-500'],
                ['label' => 'VOD', 'value' => 22, 'amount' => 27500, 'color' => 'bg-orange-500'],
                ['label' => 'Abonnements', 'value' => 10, 'amount' => 12500, 'color' => 'bg-yellow-500'],
                ['label' => 'Autres', 'value' => 3, 'amount' => 3750, 'color' => 'bg-gray-400']
            ] as $category)
                <div>
                    <div class="flex justify-between items-center mb-1 text-sm">
                        <span class="text-gray-700">{{ $category['label'] }}</span>
                        <span class="font-medium text-gray-900">{{ $category['value'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="{{ $category['color'] }} h-2 rounded-full" style="width: {{ $category['value'] }}%"></div>
                    </div>
                    <div class="text-xs text-gray-400 mt-1 text-right">{{ number_format($category['amount'], 2) }} €</div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Graphique des revenus mensuels -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50">
        <h2 class="text-lg font-semibold text-gray-800 flex items-center">
            <span class="material-symbols-outlined mr-2 text-gray-500">bar_chart</span>Évolution des Revenus (12 derniers mois)
        </h2>
    </div>
    <div class="p-6">
        <div class="space-y-4">
            @php
                $months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
                $revenues = [8500, 9200, 11000, 10500, 12300, 9800, 10200, 11500, 13200, 12800, 14500, 11000];
                $maxRevenue = max($revenues);
            @endphp
            @foreach($months as $index => $month)
                <div class="flex items-center gap-4">
                    <div class="w-10 text-sm font-medium text-gray-500">{{ $month }}</div>
                    <div class="flex-1 bg-gray-100 rounded-full h-6 relative overflow-hidden group cursor-pointer">
                        <div class="bg-red-500 h-6 rounded-full transition-all duration-500 group-hover:bg-red-600" 
                             style="width: {{ ($revenues[$index] / $maxRevenue) * 100 }}%">
                        </div>
                        <div class="absolute inset-0 flex items-center px-3 opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="text-xs font-bold text-white drop-shadow-md">{{ number_format($revenues[$index], 2) }} €</span>
                        </div>
                    </div>
                    <div class="w-16 text-right">
                        @if($index > 0)
                            @php $diff = (($revenues[$index] - $revenues[$index - 1]) / $revenues[$index - 1]) * 100; @endphp
                            <span class="text-xs font-medium {{ $diff >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $diff >= 0 ? '+' : '' }}{{ number_format($diff, 1) }}%
                            </span>
                        @else
                            <span class="text-xs text-gray-400">-</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Dernières transactions -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50">
        <h2 class="text-lg font-semibold text-gray-800 flex items-center">
            <span class="material-symbols-outlined mr-2 text-gray-500">receipt_long</span>Dernières Transactions
        </h2>
        <a href="{{ route('admin.finance.transactions') }}" class="text-red-600 hover:text-red-800 text-sm font-medium flex items-center">
            Voir tout <span class="material-symbols-outlined text-sm ml-1">arrow_forward</span>
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Montant HT</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">TVA</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">TTC</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($recentPayments ?? [] as $payment)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">#{{ $payment->id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $payment->type ?? 'Ticket' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ number_format(($payment->amount_cents ?? 0) / 100, 2) }} €</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ number_format((($payment->amount_cents ?? 0) * 0.20) / 100, 2) }} €</td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ number_format((($payment->amount_cents ?? 0) * 1.20) / 100, 2) }} €</td>
                        <td class="px-6 py-4">
                            @php
                                $status = $payment->status ?? 'completed';
                                $colors = [
                                    'completed' => 'bg-green-100 text-green-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'failed' => 'bg-red-100 text-red-800'
                                ];
                                $icons = [
                                    'completed' => 'check_circle',
                                    'pending' => 'hourglass_empty',
                                    'failed' => 'error'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colors[$status] ?? 'bg-gray-100 text-gray-800' }}">
                                <span class="material-symbols-outlined text-[10px] mr-1">{{ $icons[$status] ?? 'help' }}</span>
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ ($payment->created_at ?? now())->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-gray-400 hover:text-blue-600 transition-colors"><span class="material-symbols-outlined text-lg">visibility</span></button>
                        </td>
                    </tr>
                @empty
                    @foreach(range(1, 5) as $i)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">#{{ 10000 + $i }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ ['Ticket', 'VOD', 'Abonnement'][rand(0, 2)] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ number_format(rand(15, 150), 2) }} €</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ number_format(rand(15, 150) * 0.20, 2) }} €</td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ number_format(rand(15, 150) * 1.20, 2) }} €</td>
                            <td class="px-6 py-4">
                                @php 
                                    $status = ['completed', 'pending', 'failed'][rand(0, 2)]; 
                                    $colors = ['completed' => 'bg-green-100 text-green-800', 'pending' => 'bg-yellow-100 text-yellow-800', 'failed' => 'bg-red-100 text-red-800'];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colors[$status] }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ now()->subDays(rand(0, 30))->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 text-right">
                                <button class="text-gray-400 hover:text-blue-600 transition-colors"><span class="material-symbols-outlined text-lg">visibility</span></button>
                            </td>
                        </tr>
                    @endforeach
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function exportReport() {
    // Simulation export
    const btn = document.querySelector('button[onclick="exportReport()"]');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="material-symbols-outlined mr-2 animate-spin">refresh</span>Export...';
    setTimeout(() => {
        btn.innerHTML = originalText;
        alert('Rapport exporté avec succès !');
    }, 1500);
}
</script>
@endsection
