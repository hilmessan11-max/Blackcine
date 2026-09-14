@extends('layouts.app')

@section('title', 'Gestion des signalements - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Signalements</h1>
        <p class="text-gray-600 mt-2">Gérer les signalements de contenu</p>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <div class="flex space-x-2">
            <a href="{{ route('admin.moderation.reports') }}" class="px-3 py-1 rounded-md text-sm {{ !request('status') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">Tous</a>
            <a href="{{ route('admin.moderation.reports', ['status' => 'pending']) }}" class="px-3 py-1 rounded-md text-sm {{ request('status') == 'pending' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">En attente</a>
            <a href="{{ route('admin.moderation.reports', ['status' => 'resolved']) }}" class="px-3 py-1 rounded-md text-sm {{ request('status') == 'resolved' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">Résolus</a>
            <a href="{{ route('admin.moderation.reports', ['status' => 'dismissed']) }}" class="px-3 py-1 rounded-md text-sm {{ request('status') == 'dismissed' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">Rejetés</a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Signalé par</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Raison</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cible</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($reports as $report)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8">
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-500">
                                        <span class="text-xs font-medium leading-none text-white">{{ substr($report->user->name ?? 'U', 0, 2) }}</span>
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $report->user->name ?? 'Utilisateur supprimé' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $report->reason }}</div>
                            <div class="text-sm text-gray-500">{{ Str::limit($report->details, 50) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">
                                {{ class_basename($report->reportable_type) }} #{{ $report->reportable_id }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $report->status === 'resolved' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $report->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $report->status === 'dismissed' ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ ucfirst($report->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $report->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @if($report->status === 'pending')
                                <form action="{{ route('admin.moderation.reports.status', $report->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <input type="hidden" name="status" value="resolved">
                                    <button type="submit" class="text-green-600 hover:text-green-900 mr-2">Résoudre</button>
                                </form>
                                <form action="{{ route('admin.moderation.reports.status', $report->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <input type="hidden" name="status" value="dismissed">
                                    <button type="submit" class="text-gray-600 hover:text-gray-900">Ignorer</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Aucun signalement trouvé.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $reports->links() }}
    </div>
</div>
@endsection
