@extends('layouts.app')

@section('title', 'Catalogue - BlackCine Admin')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Catalogue</h1>
        <p class="text-gray-600 mt-2">Titres unifies du catalogue BlackCine</p>
    </div>
    <a href="{{ route('admin.catalog.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
        Creer un titre
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4">
        <div class="text-sm text-gray-500">Total</div>
        <div class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</div>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <div class="text-sm text-gray-500">Films</div>
        <div class="text-2xl font-bold text-gray-900">{{ $stats['movies'] }}</div>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <div class="text-sm text-gray-500">Series</div>
        <div class="text-2xl font-bold text-gray-900">{{ $stats['series'] }}</div>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <div class="text-sm text-gray-500">Classiques</div>
        <div class="text-2xl font-bold text-gray-900">{{ $stats['classics'] }}</div>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($titles as $title)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900">{{ $title->name }}</div>
                        <div class="text-sm text-gray-500">{{ $title->release_date?->format('Y') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $title->type }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $title->status }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="{{ route('admin.catalog.edit', $title->id) }}" class="text-blue-600 hover:text-blue-800">Modifier</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $titles->links() }}
</div>
@endsection
