@extends('layouts.app')

@section('title', 'Séries - BlackCine Admin')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Séries</h1>
    <p class="text-gray-600 mt-2">Gestion des séries et saisons</p>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="table-container">
        <table class="w-full min-w-max">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Série</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Saisons</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Épisodes</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($series as $serie)
                <tr>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $serie->name }}</div>
                        <div class="text-sm text-gray-500">{{ $serie->synopsis ? Str::limit($serie->synopsis, 50) : '' }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $serie->seasons->count() }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $serie->seasons->sum(function($season) { return $season->episodes->count(); }) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full {{ $serie->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $serie->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('admin.catalog.series.show', $serie->id) }}" class="text-blue-600 hover:text-blue-900">Gérer</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Aucune série trouvée</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $series->links() }}
    </div>
</div>
@endsection

