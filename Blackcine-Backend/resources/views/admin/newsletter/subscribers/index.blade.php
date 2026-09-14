@extends('layouts.app')

@section('title', 'Abonnés Newsletter - BlackCine')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Abonnés Newsletter</h1>
        <p class="text-gray-600 mt-2">Gérez les abonnés à votre newsletter</p>
    </div>
    <div class="flex gap-3">
        <a href="#" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 flex items-center shadow-sm transition-colors">
            <span class="material-symbols-outlined mr-2">file_download</span>
            Exporter
        </a>
        <a href="{{ route('admin.newsletter.subscribers.create') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center shadow-sm transition-colors">
            <span class="material-symbols-outlined mr-2">add_circle</span>
            Nouvel abonné
        </a>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
            </div>
            <div class="bg-blue-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-blue-600">contacts</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Actifs</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['active']) }}</p>
            </div>
            <div class="bg-green-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-green-600">check_circle</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Inactifs</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['inactive']) }}</p>
            </div>
            <div class="bg-red-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-red-600">cancel</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Ce mois-ci</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['this_month']) }}</p>
            </div>
            <div class="bg-purple-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-purple-600">calendar_month</span>
            </div>
        </div>
    </div>
</div>

<!-- Filtres et Recherche -->
<div class="bg-white rounded-lg shadow-sm p-4 mb-6 border border-gray-100">
    <form method="GET" action="{{ route('admin.newsletter.subscribers.index') }}" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="material-symbols-outlined text-gray-400">search</span>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher par email ou nom..." 
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
        </div>
        <div class="w-full md:w-48">
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
                <option value="">Tous les statuts</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactif</option>
            </select>
        </div>
        <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-900 transition-colors">
            Filtrer
        </button>
    </form>
</div>

<!-- Liste -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100">
    <div class="table-container">
        <table class="w-full min-w-max">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Source</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($subscribers as $subscriber)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined text-gray-400 mr-2 text-base">email</span>
                            {{ $subscriber->email }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined text-gray-400 mr-2 text-base">person</span>
                            {{ $subscriber->full_name }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        @if($subscriber->source)
                            <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded">
                                {{ ucfirst($subscriber->source) }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined text-gray-400 mr-1 text-base">calendar_today</span>
                            {{ $subscriber->subscribed_at ? $subscriber->subscribed_at->format('d/m/Y') : '-' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($subscriber->is_active)
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 border border-green-200 flex items-center w-fit">
                                <span class="material-symbols-outlined text-xs mr-1">check_circle</span>
                                Actif
                            </span>
                        @else
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 border border-gray-200 flex items-center w-fit">
                                <span class="material-symbols-outlined text-xs mr-1">cancel</span>
                                Inactif
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('admin.newsletter.subscribers.edit', $subscriber) }}" class="p-1 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded transition-colors" title="Modifier">
                                <span class="material-symbols-outlined">edit</span>
                            </a>
                            <form action="{{ route('admin.newsletter.subscribers.destroy', $subscriber) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cet abonné ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1 text-red-600 hover:text-red-900 hover:bg-red-50 rounded transition-colors" title="Supprimer">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="bg-gray-100 p-4 rounded-full mb-3">
                                <span class="material-symbols-outlined text-3xl text-gray-400">contacts</span>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Aucun abonné</h3>
                            <p class="text-gray-500 mt-1">Commencez par ajouter vos premiers abonnés.</p>
                            <a href="{{ route('admin.newsletter.subscribers.create') }}" class="mt-4 text-red-600 hover:text-red-700 font-medium">
                                Ajouter un abonné
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $subscribers->links() }}
    </div>
</div>
@endsection
