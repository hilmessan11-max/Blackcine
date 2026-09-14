@extends('layouts.app')

@section('title', 'Notifications Push - BlackCine')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800 flex items-center">
            <span class="material-symbols-outlined mr-3 text-3xl text-red-600">notifications</span>
            Notifications Push
        </h1>
        <p class="text-gray-600 mt-2">Gérez vos notifications push</p>
    </div>
    <a href="{{ route('admin.notifications.push.create') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition shadow-md hover:shadow-lg flex items-center">
        <span class="material-symbols-outlined mr-2">add</span>
        Nouvelle Notification
    </a>
</div>

<div class="card bg-white shadow-lg overflow-hidden">
    <div class="table-container">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre / Message</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cible</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Planification</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stats</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($notifications as $notif)
                <tr class="table-row">
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900 flex items-center">
                            <span class="material-symbols-outlined mr-2 text-base text-gray-400">notifications</span>
                            {{ $notif->title }}
                        </div>
                        <div class="text-xs text-gray-500 truncate max-w-xs mt-1">{{ $notif->message }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($notif->target_audience == 'all')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                <span class="material-symbols-outlined text-xs mr-1">people</span>
                                Tous
                            </span>
                        @elseif($notif->target_audience == 'subscribers')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                <span class="material-symbols-outlined text-xs mr-1">contacts</span>
                                Abonnés
                            </span>
                        @else
                            {{ ucfirst($notif->target_audience) }}
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 flex items-center">
                        <span class="material-symbols-outlined mr-1 text-base text-gray-400">schedule</span>
                        @if($notif->sent_at)
                            Envoyé le {{ $notif->sent_at->format('d/m/Y H:i') }}
                        @elseif($notif->scheduled_at)
                            Prévu le {{ $notif->scheduled_at->format('d/m/Y H:i') }}
                        @else
                            Non planifié
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($notif->status == 'sent')
                            <div class="text-green-600 flex items-center">
                                <span class="material-symbols-outlined text-xs mr-1">check_circle</span>
                                {{ $notif->success_count }}
                            </div>
                            <div class="text-red-600 flex items-center">
                                <span class="material-symbols-outlined text-xs mr-1">cancel</span>
                                {{ $notif->failure_count }}
                            </div>
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full flex items-center
                            {{ $notif->status === 'sent' ? 'bg-green-100 text-green-800' : 
                               ($notif->status === 'scheduled' ? 'bg-blue-100 text-blue-800' : 
                               ($notif->status === 'draft' ? 'bg-gray-100 text-gray-800' : 'bg-yellow-100 text-yellow-800')) }}">
                            <span class="material-symbols-outlined text-xs mr-1">
                                {{ $notif->status === 'sent' ? 'check_circle' : ($notif->status === 'scheduled' ? 'schedule' : 'draft') }}
                            </span>
                            {{ ucfirst($notif->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end gap-3">
                            @if($notif->status == 'draft' || $notif->status == 'scheduled')
                                <a href="{{ route('admin.notifications.push.edit', $notif) }}" class="text-blue-600 hover:text-blue-800 transition-colors" title="Modifier">
                                    <span class="material-symbols-outlined text-xl">edit</span>
                                </a>
                            @endif
                            <form action="{{ route('admin.notifications.push.destroy', $notif) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 transition-colors" title="Supprimer">
                                    <span class="material-symbols-outlined text-xl">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <span class="material-symbols-outlined text-6xl text-gray-400 mb-4 block">notifications</span>
                        <p class="text-lg">Aucune notification push trouvée</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
