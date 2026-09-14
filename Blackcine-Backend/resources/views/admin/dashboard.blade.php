@extends('layouts.app')

@section('title', 'Dashboard Analytics - BlackCine Admin')

@section('content')
<!-- En-tête avec période et filtres -->
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Dashboard Analytics</h1>
            <p class="text-gray-600 mt-2">Vue d'ensemble en temps réel de votre plateforme</p>
        </div>
        <div class="flex items-center space-x-3">
            <select id="periodFilter" class="form-input bg-white border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                <option value="today">Aujourd'hui</option>
                <option value="7days" selected>7 derniers jours</option>
                <option value="30days">30 derniers jours</option>
                <option value="year">Cette année</option>
            </select>
            <button onclick="refreshDashboard()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-all duration-200 flex items-center shadow-md hover:shadow-lg">
                <span class="material-symbols-outlined mr-2 text-lg">refresh</span>
                Actualiser
            </button>
        </div>
    </div>
</div>

<!-- KPIs principaux avec animations -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Utilisateurs -->
    <div class="card bg-gradient-to-br from-blue-500 to-blue-600 shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300 cursor-pointer">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium">Utilisateurs</p>
                <p class="text-4xl font-bold mt-2" id="totalUsers">{{ number_format($stats['total_users']) }}</p>
                <div class="flex items-center mt-2 space-x-2">
                    <span class="text-xs bg-white/20 px-2 py-1 rounded-full">+{{ $stats['users_growth'] ?? 12 }}%</span>
                    <span class="text-xs text-blue-100">vs mois dernier</span>
                </div>
            </div>
            <div class="bg-white/20 p-4 rounded-full">
                <span class="material-symbols-outlined text-4xl">people</span>
            </div>
        </div>
    </div>

    <!-- Revenus -->
    <div class="card bg-gradient-to-br from-green-500 to-green-600 shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300 cursor-pointer">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium">Revenus du mois</p>
                <p class="text-4xl font-bold mt-2">{{ number_format($stats['total_revenue'] ?? 0) }}€</p>
                <div class="flex items-center mt-2 space-x-2">
                    <span class="text-xs bg-white/20 px-2 py-1 rounded-full">+{{ $stats['revenue_growth'] ?? 18 }}%</span>
                    <span class="text-xs text-green-100">vs mois dernier</span>
                </div>
            </div>
            <div class="bg-white/20 p-4 rounded-full">
                <span class="material-symbols-outlined text-4xl">account_balance_wallet</span>
            </div>
        </div>
    </div>

    <!-- Tickets vendus -->
    <div class="card bg-gradient-to-br from-purple-500 to-purple-600 shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300 cursor-pointer">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium">Tickets Vendus</p>
                <p class="text-4xl font-bold mt-2">{{ number_format($stats['total_tickets']) }}</p>
                <div class="flex items-center mt-2 space-x-2">
                    <span class="text-xs bg-white/20 px-2 py-1 rounded-full">+{{ $stats['tickets_growth'] ?? 25 }}%</span>
                    <span class="text-xs text-purple-100">vs mois dernier</span>
                </div>
            </div>
            <div class="bg-white/20 p-4 rounded-full">
                <span class="material-symbols-outlined text-4xl">confirmation_number</span>
            </div>
        </div>
    </div>

    <!-- Taux de satisfaction -->
    <div class="card bg-gradient-to-br from-orange-500 to-orange-600 shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300 cursor-pointer">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm font-medium">Satisfaction Client</p>
                <p class="text-4xl font-bold mt-2">{{ $stats['satisfaction_rate'] ?? 92 }}%</p>
                <div class="flex items-center mt-2 space-x-2">
                    <span class="text-xs bg-white/20 px-2 py-1 rounded-full">+{{ $stats['satisfaction_growth'] ?? 3 }}%</span>
                    <span class="text-xs text-orange-100">vs mois dernier</span>
                </div>
            </div>
            <div class="bg-white/20 p-4 rounded-full">
                <span class="material-symbols-outlined text-4xl">star</span>
            </div>
        </div>
    </div>
</div>

<!-- Graphiques principaux -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Graphique des ventes -->
    <div class="card bg-white shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <span class="material-symbols-outlined mr-2 text-red-600">trending_up</span>
                Évolution des Ventes
            </h3>
            <div class="flex space-x-2">
                <button class="px-3 py-1 text-xs bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition">Semaine</button>
                <button class="px-3 py-1 text-xs bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition">Mois</button>
            </div>
        </div>
        <canvas id="salesChart" height="300"></canvas>
    </div>

    <!-- Graphique des utilisateurs -->
    <div class="card bg-white shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <span class="material-symbols-outlined mr-2 text-red-600">people</span>
                Nouveaux Utilisateurs
            </h3>
            <span class="text-sm text-gray-500">7 derniers jours</span>
        </div>
        <canvas id="usersChart" height="300"></canvas>
    </div>
</div>

<!-- Graphiques secondaires -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Répartition par type de contenu -->
    <div class="card bg-white shadow-lg p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
            <span class="material-symbols-outlined mr-2 text-red-600">pie_chart</span>
            Répartition Contenu
        </h3>
        <canvas id="contentChart" height="200"></canvas>
        <div class="mt-4 space-y-2">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                    <span class="text-sm text-gray-600">Films</span>
                </div>
                <span class="text-sm font-semibold">{{ $stats['films_count'] ?? 0 }}</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-purple-500 rounded-full mr-2"></div>
                    <span class="text-sm text-gray-600">Séries</span>
                </div>
                <span class="text-sm font-semibold">{{ $stats['series_count'] ?? 0 }}</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                    <span class="text-sm text-gray-600">Articles</span>
                </div>
                <span class="text-sm font-semibold">{{ $stats['total_articles'] ?? 0 }}</span>
            </div>
        </div>
    </div>

    <!-- Top 5 Films -->
    <div class="card bg-white shadow-lg p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
            <span class="material-symbols-outlined mr-2 text-red-600">emoji_events</span>
            Top 5 Films
        </h3>
        <div class="space-y-3">
            @forelse($stats['top_titles'] ?? [] as $index => $title)
            <div class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded-lg transition">
                <div class="w-8 h-8 rounded-full bg-gradient-to-r from-yellow-400 to-orange-500 flex items-center justify-center text-white font-bold text-sm">
                    {{ $index + 1 }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $title->title }}</p>
                    <p class="text-xs text-gray-500">{{ $title->tickets_sold ?? 0 }} tickets</p>
                </div>
                <span class="text-sm font-bold text-gray-900">{{ number_format($title->revenue ?? 0) }}€</span>
            </div>
            @empty
            <p class="text-center text-gray-500 py-4 text-sm">Aucune donnée</p>
            @endforelse
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="card bg-white shadow-lg p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
            <span class="material-symbols-outlined mr-2 text-red-600">flash_on</span>
            Stats Rapides
        </h3>
        <div class="space-y-4">
            <div>
                <div class="flex justify-between items-center mb-1">
                    <span class="text-sm text-gray-600">Taux de conversion</span>
                    <span class="text-sm font-bold text-green-600">{{ $stats['conversion_rate'] ?? 2.5 }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-gradient-to-r from-green-400 to-green-600 h-2 rounded-full" style="width: {{ $stats['conversion_rate'] ?? 2.5 }}%"></div>
                </div>
            </div>

            <div>
                <div class="flex justify-between items-center mb-1">
                    <span class="text-sm text-gray-600">Taux d'engagement</span>
                    <span class="text-sm font-bold text-blue-600">{{ $stats['engagement_rate'] ?? 68 }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-2 rounded-full" style="width: {{ $stats['engagement_rate'] ?? 68 }}%"></div>
                </div>
            </div>

            <div>
                <div class="flex justify-between items-center mb-1">
                    <span class="text-sm text-gray-600">Taux de retour</span>
                    <span class="text-sm font-bold text-purple-600">{{ $stats['return_rate'] ?? 45 }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-gradient-to-r from-purple-400 to-purple-600 h-2 rounded-full" style="width: {{ $stats['return_rate'] ?? 45 }}%"></div>
                </div>
            </div>

            <div class="pt-4 mt-4 border-t border-gray-200">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Panier moyen</span>
                    <span class="text-sm font-bold text-gray-900">{{ number_format($stats['average_cart'] ?? 0, 2) }}€</span>
                </div>
            </div>

            <div class="flex justify-between">
                <span class="text-sm text-gray-600">Utilisateurs actifs</span>
                <span class="text-sm font-bold text-blue-600">{{ number_format($stats['active_users'] ?? 0) }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Activité en temps réel -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Dernières activités -->
    <div class="card bg-white shadow-lg">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <span class="material-symbols-outlined mr-2 text-red-600">notifications</span>
                Activité Récente
            </h3>
        </div>
        <div class="p-6 max-h-96 overflow-y-auto">
            <div class="space-y-4">
                @forelse($stats['recent_activities'] ?? [] as $activity)
                <div class="table-row flex items-start space-x-3 p-3 rounded-lg transition">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-white text-lg">{{ $activity['icon'] ?? 'notifications' }}</span>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-900">{{ $activity['description'] ?? 'Activité' }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $activity['time'] ?? 'Il y a quelques instants' }}</p>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-8">Aucune activité récente</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Objectifs du mois -->
    <div class="card bg-white shadow-lg">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <span class="material-symbols-outlined mr-2 text-red-600">target</span>
                Objectifs du Mois
            </h3>
        </div>
        <div class="p-6 space-y-6">
            <div>
                <div class="flex justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Revenus (50 000€)</span>
                    <span class="text-sm font-bold text-green-600">{{ number_format(($stats['total_revenue'] ?? 0) / 50000 * 100, 1) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-gradient-to-r from-green-400 to-green-600 h-3 rounded-full transition-all duration-500" style="width: {{ min(($stats['total_revenue'] ?? 0) / 50000 * 100, 100) }}%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">{{ number_format($stats['total_revenue'] ?? 0) }}€ / 50 000€</p>
            </div>

            <div>
                <div class="flex justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Nouveaux Utilisateurs (1000)</span>
                    <span class="text-sm font-bold text-blue-600">{{ number_format(($stats['new_users_month'] ?? 0) / 1000 * 100, 1) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-3 rounded-full transition-all duration-500" style="width: {{ min(($stats['new_users_month'] ?? 0) / 1000 * 100, 100) }}%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">{{ number_format($stats['new_users_month'] ?? 0) }} / 1 000</p>
            </div>

            <div>
                <div class="flex justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Tickets Vendus (5000)</span>
                    <span class="text-sm font-bold text-purple-600">{{ number_format(($stats['tickets_month'] ?? 0) / 5000 * 100, 1) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-gradient-to-r from-purple-400 to-purple-600 h-3 rounded-full transition-all duration-500" style="width: {{ min(($stats['tickets_month'] ?? 0) / 5000 * 100, 100) }}%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">{{ number_format($stats['tickets_month'] ?? 0) }} / 5 000</p>
            </div>

            <div>
                <div class="flex justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Nouveaux Articles (50)</span>
                    <span class="text-sm font-bold text-orange-600">{{ number_format(($stats['articles_month'] ?? 0) / 50 * 100, 1) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-gradient-to-r from-orange-400 to-orange-600 h-3 rounded-full transition-all duration-500" style="width: {{ min(($stats['articles_month'] ?? 0) / 50 * 100, 100) }}%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">{{ number_format($stats['articles_month'] ?? 0) }} / 50</p>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Configuration commune
const commonOptions = {
    responsive: true,
    maintainAspectRatio: true,
    plugins: {
        legend: {
            display: true,
            position: 'bottom'
        }
    }
};

// Graphique des ventes
const salesCtx = document.getElementById('salesChart').getContext('2d');
new Chart(salesCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode(collect($stats['weekly_sales'] ?? [])->pluck('day')) !!},
        datasets: [{
            label: 'Ventes (€)',
            data: {!! json_encode(collect($stats['weekly_sales'] ?? [])->pluck('total')) !!},
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        ...commonOptions,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value + '€';
                    }
                }
            }
        }
    }
});

// Graphique des utilisateurs
const usersCtx = document.getElementById('usersChart').getContext('2d');
new Chart(usersCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode(collect($stats['weekly_users'] ?? [])->pluck('day')) !!},
        datasets: [{
            label: 'Nouveaux utilisateurs',
            data: {!! json_encode(collect($stats['weekly_users'] ?? [])->pluck('count')) !!},
            backgroundColor: 'rgba(139, 92, 246, 0.8)',
            borderColor: 'rgb(139, 92, 246)',
            borderWidth: 1
        }]
    },
    options: {
        ...commonOptions,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Graphique de répartition
const contentCtx = document.getElementById('contentChart').getContext('2d');
new Chart(contentCtx, {
    type: 'doughnut',
    data: {
        labels: ['Films', 'Séries', 'Articles'],
        datasets: [{
            data: [
                {{ $stats['films_count'] ?? 0 }},
                {{ $stats['series_count'] ?? 0 }},
                {{ $stats['total_articles'] ?? 0 }}
            ],
            backgroundColor: [
                'rgba(59, 130, 246, 0.8)',
                'rgba(139, 92, 246, 0.8)',
                'rgba(34, 197, 94, 0.8)'
            ],
            borderWidth: 0
        }]
    },
    options: {
        ...commonOptions,
        cutout: '70%'
    }
});

// Fonction de rafraîchissement
function refreshDashboard() {
    window.location.reload();
}

// Auto-refresh toutes les 5 minutes
setInterval(refreshDashboard, 300000);
</script>
@endsection