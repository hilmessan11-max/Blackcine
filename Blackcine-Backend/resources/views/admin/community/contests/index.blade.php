@extends('layouts.app')

@section('title', 'Concours - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Concours & Appels à films</h1>
        <p class="text-gray-600 mt-2">Gérez les concours et opportunités</p>
    </div>
    <a href="{{ route('admin.community.contests.create') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center shadow-sm transition-colors">
        <span class="material-symbols-outlined mr-2">add_circle</span>
        Créer un concours
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total</p>
                <p class="text-2xl font-bold text-gray-900">{{ $contests->total() }}</p>
            </div>
            <div class="bg-yellow-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-yellow-600">trophy</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Ouverts</p>
                <p class="text-2xl font-bold text-gray-900">{{ $contests->where('status', 'open')->count() }}</p>
            </div>
            <div class="bg-green-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-green-600">lock_open</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Fermés</p>
                <p class="text-2xl font-bold text-gray-900">{{ $contests->where('status', 'closed')->count() }}</p>
            </div>
            <div class="bg-red-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-red-600">lock</span>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm p-4 mb-6 border border-gray-100">
    <form method="GET" action="{{ route('admin.community.contests.index') }}" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="material-symbols-outlined text-gray-400">search</span>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..." 
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
        </div>
        <select name="contest_type" class="w-full md:w-48 px-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
            <option value="">Tous les types</option>
            <option value="film_festival" {{ request('contest_type') === 'film_festival' ? 'selected' : '' }}>Festival</option>
            <option value="short_film" {{ request('contest_type') === 'short_film' ? 'selected' : '' }}>Court métrage</option>
            <option value="script" {{ request('contest_type') === 'script' ? 'selected' : '' }}>Scénario</option>
        </select>
        <select name="status" class="w-full md:w-48 px-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
            <option value="">Tous statuts</option>
            <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Ouvert</option>
            <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Fermé</option>
            <option value="judging" {{ request('status') === 'judging' ? 'selected' : '' }}>Jugement</option>
        </select>
        <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-900 transition-colors">Filtrer</button>
    </form>
</div>

<div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100">
    <div class="table-container">
        <table class="w-full min-w-max">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Concours</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Dates</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($contests as $contest)
                <tr class="hover:bg-gray-50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-start">
                            <div class="bg-yellow-50 group-hover:bg-yellow-100 p-2 rounded-lg mr-3 transition-colors">
                                <span class="material-symbols-outlined text-yellow-600">trophy</span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $contest->title }}</div>
                                @if($contest->description)
                                    <div class="text-xs text-gray-500 mt-0.5">{{ Str::limit($contest->description, 60) }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4"><span class="px-2.5 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800 border border-purple-200">{{ ucfirst(str_replace('_', ' ', $contest->contest_type)) }}</span></td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        @if($contest->start_date && $contest->end_date)
                            <div class="flex items-center"><span class="material-symbols-outlined text-gray-400 mr-1 text-base">event</span><div><div>{{ $contest->start_date->format('d/m/Y') }}</div><div class="text-xs text-gray-400">au {{ $contest->end_date->format('d/m/Y') }}</div></div></div>
                        @else N/A @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($contest->status === 'open')<span class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 border border-green-200">Ouvert</span>
                        @elseif($contest->status === 'closed')<span class="px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 border border-red-200">Fermé</span>
                        @else<span class="px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 border border-gray-200">{{ ucfirst($contest->status) }}</span>@endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('admin.community.contests.edit', $contest->id) }}" class="p-1 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded transition-colors" title="Modifier"><span class="material-symbols-outlined">edit</span></a>
                            <form action="{{ route('admin.community.contests.destroy', $contest->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ?');"> @csrf @method('DELETE') <button type="submit" class="p-1 text-red-600 hover:text-red-900 hover:bg-red-50 rounded transition-colors" title="Supprimer"><span class="material-symbols-outlined">delete</span></button></form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-12 text-center"><div class="flex flex-col items-center"><div class="bg-gray-100 p-4 rounded-full mb-3"><span class="material-symbols-outlined text-3xl text-gray-400">trophy</span></div><h3 class="text-lg font-medium text-gray-900">Aucun concours</h3></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">{{ $contests->links() }}</div>
</div>
@endsection
