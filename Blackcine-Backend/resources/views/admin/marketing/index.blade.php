@extends('layouts.app')

@section('title', 'Marketing - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Marketing</h1>
        <p class="text-gray-600 mt-2">Gestion des campagnes marketing</p>
    </div>
    <button onclick="document.getElementById('campaignModal').classList.remove('hidden')" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
        + Créer une campagne
    </button>
</div>

<!-- Statistiques -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-600 text-sm">Total slides</p>
        <p class="text-3xl font-bold text-gray-800">{{ number_format($stats['total_slides']) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-600 text-sm">Slides actifs</p>
        <p class="text-3xl font-bold text-green-600">{{ number_format($stats['active_slides']) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-600 text-sm">Total sélections</p>
        <p class="text-3xl font-bold text-gray-800">{{ number_format($stats['total_selections']) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-600 text-sm">Sélections actives</p>
        <p class="text-3xl font-bold text-green-600">{{ number_format($stats['active_selections']) }}</p>
    </div>
</div>

<!-- Slides -->
<div class="bg-white rounded-lg shadow mb-8">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-800">Slides</h2>
    </div>
    <div class="table-container">
        <table class="w-full min-w-max">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Titre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">CTA</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ordre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($slides as $slide)
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $slide->title }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        @if($slide->cta_label)
                            <a href="{{ $slide->cta_url }}" class="text-blue-600 hover:underline">{{ $slide->cta_label }}</a>
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $slide->display_order }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full {{ $slide->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $slide->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $slide->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Aucun slide trouvé</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Sélections -->
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-800">Sélections</h2>
    </div>
    <div class="table-container">
        <table class="w-full min-w-max">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Titre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ordre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($selections as $selection)
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $selection->title }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $selection->type }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($selection->description ?? 'N/A', 50) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $selection->display_order }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full {{ $selection->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $selection->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $selection->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Aucune sélection trouvée</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Créer Campagne -->
<div id="campaignModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Créer une campagne</h3>
            <form action="{{ route('admin.marketing.campaign') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="type">Type *</label>
                    <select name="type" id="type" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="slide">Slide</option>
                        <option value="selection">Sélection</option>
                        <option value="notification">Notification</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="title">Titre *</label>
                    <input type="text" name="title" id="title" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Description</label>
                    <textarea name="description" id="description" rows="3"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="start_date">Date de début</label>
                    <input type="date" name="start_date" id="start_date"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="end_date">Date de fin</label>
                    <input type="date" name="end_date" id="end_date"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" class="mr-2">
                        <span class="text-gray-700 text-sm">Actif</span>
                    </label>
                </div>
                <div class="flex items-center justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('campaignModal').classList.add('hidden')" class="text-gray-600 hover:text-gray-800">
                        Annuler
                    </button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Créer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

