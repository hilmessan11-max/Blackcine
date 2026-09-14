@extends('layouts.app')

@section('title', 'Communauté - BlackCine Admin')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Gestion de la communauté</h1>
    <p class="text-gray-600 mt-2">Gérez les utilisateurs et la modération</p>
</div>

<!-- Statistiques -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-600 text-sm">Total utilisateurs</p>
        <p class="text-3xl font-bold text-gray-800">{{ number_format($stats['total_users']) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-600 text-sm">Utilisateurs actifs (30j)</p>
        <p class="text-3xl font-bold text-green-600">{{ number_format($stats['active_users']) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-600 text-sm">Utilisateurs bannis</p>
        <p class="text-3xl font-bold text-red-600">{{ number_format($stats['banned_users']) }}</p>
    </div>
</div>

<!-- Dernières inscriptions -->
<div class="bg-white rounded-lg shadow mb-8">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-800">Dernières inscriptions</h2>
    </div>
    <div class="table-container">
        <table class="w-full min-w-max">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rôles</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($recentRegistrations as $user)
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $user->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        @foreach($user->roles as $role)
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">{{ $role->name }}</span>
                        @endforeach
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">Aucune inscription récente</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Liste des utilisateurs -->
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-800">Tous les utilisateurs</h2>
    </div>
    <div class="table-container">
        <table class="w-full min-w-max">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rôles</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($users as $user)
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $user->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        @foreach($user->roles as $role)
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">{{ $role->name }}</span>
                        @endforeach
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Actif</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $user->created_at->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openBanModal({{ $user->id }})" class="text-red-600 hover:text-red-900">Bannir</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Aucun utilisateur trouvé</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $users->links() }}
    </div>
</div>

<!-- Modal Bannir -->
<div id="banModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Bannir un utilisateur</h3>
            <form id="banForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="reason">Raison</label>
                    <textarea name="reason" id="reason" rows="3"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="duration_days">Durée (jours)</label>
                    <input type="number" name="duration_days" id="duration_days" min="1"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <p class="text-gray-500 text-xs mt-1">Laissez vide pour un bannissement permanent</p>
                </div>
                <div class="flex items-center justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('banModal').classList.add('hidden')" class="text-gray-600 hover:text-gray-800">
                        Annuler
                    </button>
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                        Bannir
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openBanModal(userId) {
    document.getElementById('banForm').action = '/admin/community/ban/' + userId;
    document.getElementById('banModal').classList.remove('hidden');
}
</script>
@endsection

