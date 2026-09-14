@extends('layouts.app')

@section('title', 'Comptes Pro - BlackCine Admin')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Comptes Professionnels</h1>
    <p class="text-gray-600 mt-2">Gestion et validation des comptes professionnels</p>
</div>

<!-- Filtres -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form method="GET" action="{{ route('admin.users.pro-accounts.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Entreprise, email..."
                class="w-full px-3 py-2 border border-gray-300 rounded-md">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Statut vérification</label>
            <select name="verification_status" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                <option value="">Tous</option>
                <option value="pending" {{ request('verification_status') === 'pending' ? 'selected' : '' }}>En attente</option>
                <option value="verified" {{ request('verification_status') === 'verified' ? 'selected' : '' }}>Vérifié</option>
                <option value="rejected" {{ request('verification_status') === 'rejected' ? 'selected' : '' }}>Rejeté</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">KYC</label>
            <select name="kyc_status" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                <option value="">Tous</option>
                <option value="pending" {{ request('kyc_status') === 'pending' ? 'selected' : '' }}>En attente</option>
                <option value="approved" {{ request('kyc_status') === 'approved' ? 'selected' : '' }}>Approuvé</option>
                <option value="rejected" {{ request('kyc_status') === 'rejected' ? 'selected' : '' }}>Rejeté</option>
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Filtrer
            </button>
        </div>
    </form>
</div>

<!-- Liste des comptes -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="table-container">
        <table class="w-full min-w-max">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Entreprise</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vérification</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">KYC</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($accounts as $account)
                <tr>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $account->company_name }}</div>
                        @if($account->user)
                            <div class="text-sm text-gray-500">{{ $account->user->name }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $account->company_type ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $account->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($account->verification_status === 'verified')
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">✓ Vérifié</span>
                        @elseif($account->verification_status === 'pending')
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">En attente</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Rejeté</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">
                            {{ $account->kyc_status ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.users.pro-accounts.show', $account->id) }}" class="text-blue-600 hover:text-blue-900">Voir</a>
                            @if($account->verification_status !== 'verified')
                                <form action="{{ route('admin.users.pro-accounts.verify', $account->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900">Vérifier</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Aucun compte Pro trouvé</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $accounts->links() }}
    </div>
</div>
@endsection

