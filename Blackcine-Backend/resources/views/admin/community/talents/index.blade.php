@extends('layouts.app')

@section('title', 'Talents & Portfolios - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div><h1 class="text-3xl font-bold text-gray-800">Talents & Portfolios</h1><p class="text-gray-600 mt-2">Profils professionnels vérifiés</p></div>
    <a href="{{ route('admin.community.talents.create') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center shadow-sm transition-colors"><span class="material-symbols-outlined mr-2">person_add</span>Créer un talent</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4"><div class="flex items-center justify-between"><div><p class="text-sm text-gray-500">Total</p><p class="text-2xl font-bold text-gray-900">{{ $talents->total() }}</p></div><div class="bg-blue-50 p-3 rounded-full"><span class="material-symbols-outlined text-blue-600">star</span></div></div></div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4"><div class="flex items-center justify-between"><div><p class="text-sm text-gray-500">Vérifiés</p><p class="text-2xl font-bold text-gray-900">{{ $talents->where('is_verified', true)->count() }}</p></div><div class="bg-green-50 p-3 rounded-full"><span class="material-symbols-outlined text-green-600">verified</span></div></div></div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4"><div class="flex items-center justify-between"><div><p class="text-sm text-gray-500">En attente</p><p class="text-2xl font-bold text-gray-900">{{ $talents->where('verification_status', 'pending')->count() }}</p></div><div class="bg-yellow-50 p-3 rounded-full"><span class="material-symbols-outlined text-yellow-600">schedule</span></div></div></div>
</div>

<div class="bg-white rounded-lg shadow-sm p-4 mb-6 border border-gray-100">
    <form method="GET" action="{{ route('admin.community.talents.index') }}" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative"><span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><span class="material-symbols-outlined text-gray-400">search</span></span><input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors"></div>
        <input type="text" name="profession" value="{{ request('profession') }}" placeholder="Profession..." class="w-full md:w-48 px-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
        <select name="verification_status" class="w-full md:w-48 px-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors"><option value="">Tous</option><option value="pending" {{ request('verification_status') === 'pending' ? 'selected' : '' }}>En attente</option><option value="verified" {{ request('verification_status') === 'verified' ? 'selected' : '' }}>Vérifié</option><option value="rejected" {{ request('verification_status') === 'rejected' ? 'selected' : '' }}>Rejeté</option></select>
        <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-900 transition-colors">Filtrer</button>
    </form>
</div>

<div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100">
    <div class="table-container"><table class="w-full min-w-max"><thead class="bg-gray-50 border-b border-gray-200"><tr><th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Talent</th><th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Profession</th><th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Exp.</th><th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Statut</th><th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th></tr></thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($talents as $talent)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4"><div class="flex items-center">@if($talent->photoAsset)<img src="{{ asset('storage/' . $talent->photoAsset->path) }}" alt="{{ $talent->first_name }}" class="w-10 h-10 rounded-full mr-3 ring-2 ring-gray-200">@else<div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full mr-3 flex items-center justify-center text-white font-bold">{{ substr($talent->first_name, 0, 1) }}</div>@endif<div><div class="text-sm font-medium text-gray-900">{{ $talent->first_name }} {{ $talent->last_name }}</div><div class="text-xs text-gray-500">{{ $talent->user->email ?? 'N/A' }}</div></div></div></td>
                <td class="px-6 py-4"><span class="px-2.5 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800 border border-purple-200">{{ $talent->profession }}</span></td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $talent->experience_years ?? 0 }} ans</td>
                <td class="px-6 py-4 whitespace-nowrap">@if($talent->is_verified)<span class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 border border-green-200 flex items-center w-fit"><span class="material-symbols-outlined text-xs mr-1">verified</span>Vérifié</span>@elseif($talent->verification_status === 'pending')<span class="px-2.5 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">En attente</span>@else<span class="px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 border border-red-200">Rejeté</span>@endif</td>
                <td class="px-6 py-4 whitespace-nowrap text-right"><div class="flex justify-end space-x-2"><a href="{{ route('admin.community.talents.show', $talent->id) }}" class="p-1 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded transition-colors" title="Voir"><span class="material-symbols-outlined">visibility</span></a>@if(!$talent->is_verified)<form action="{{ route('admin.community.talents.verify', $talent->id) }}" method="POST" class="inline">@csrf<button type="submit" class="p-1 text-green-600 hover:text-green-900 hover:bg-green-50 rounded transition-colors" title="Vérifier"><span class="material-symbols-outlined">verified</span></button></form>@endif</div></td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-12 text-center"><div class="flex flex-col items-center"><div class="bg-gray-100 p-4 rounded-full mb-3"><span class="material-symbols-outlined text-3xl text-gray-400">star</span></div><h3 class="text-lg font-medium text-gray-900">Aucun talent</h3></div></td></tr>
            @endforelse
        </tbody>
    </table></div>
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">{{ $talents->links() }}</div>
</div>
@endsection
