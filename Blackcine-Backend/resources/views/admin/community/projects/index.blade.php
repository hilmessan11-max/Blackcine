@extends('layouts.app')

@section('title', 'Projets - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Projets</h1>
        <p class="text-gray-600 mt-2">Gérez les projets en recherche d'équipe</p>
    </div>
    <a href="{{ route('admin.community.projects.create') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center shadow-sm transition-colors">
        <span class="material-symbols-outlined mr-2">add_circle</span>
        Créer un projet
    </a>
</div>

<!-- Stats rapides -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total projets</p>
                <p class="text-2xl font-bold text-gray-900">{{ $projects->total() }}</p>
            </div>
            <div class="bg-blue-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-blue-600">work</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">En casting</p>
                <p class="text-2xl font-bold text-gray-900">{{ $projects->where('status', 'casting')->count() }}</p>
            </div>
            <div class="bg-yellow-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-yellow-600">groups</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">En production</p>
                <p class="text-2xl font-bold text-gray-900">{{ $projects->where('status', 'production')->count() }}</p>
            </div>
            <div class="bg-green-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-green-600">movie</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Films</p>
                <p class="text-2xl font-bold text-gray-900">{{ $projects->where('project_type', 'film')->count() }}</p>
            </div>
            <div class="bg-purple-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-purple-600">video_library</span>
            </div>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="bg-white rounded-lg shadow-sm p-4 mb-6 border border-gray-100">
    <form method="GET" action="{{ route('admin.community.projects.index') }}" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="material-symbols-outlined text-gray-400">search</span>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..." 
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
        </div>
        <div class="w-full md:w-48">
            <select name="project_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
                <option value="">Tous les types</option>
                <option value="film" {{ request('project_type') === 'film' ? 'selected' : '' }}>Film</option>
                <option value="series" {{ request('project_type') === 'series' ? 'selected' : '' }}>Série</option>
                <option value="documentary" {{ request('project_type') === 'documentary' ? 'selected' : '' }}>Documentaire</option>
                <option value="short_film" {{ request('project_type') === 'short_film' ? 'selected' : '' }}>Court métrage</option>
            </select>
        </div>
        <div class="w-full md:w-48">
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
                <option value="">Tous les statuts</option>
                <option value="casting" {{ request('status') === 'casting' ? 'selected' : '' }}>Casting</option>
                <option value="pre_production" {{ request('status') === 'pre_production' ? 'selected' : '' }}>Pré-production</option>
                <option value="production" {{ request('status') === 'production' ? 'selected' : '' }}>Production</option>
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
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Projet</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Porteur</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Besoins</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($projects as $project)
                <tr class="hover:bg-gray-50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-start">
                            <div class="bg-blue-50 group-hover:bg-blue-100 p-2 rounded-lg mr-3 transition-colors">
                                <span class="material-symbols-outlined text-blue-600">work</span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $project->title }}</div>
                                @if($project->description)
                                    <div class="text-xs text-gray-500 mt-0.5">{{ Str::limit($project->description, 60) }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800 border border-purple-200">
                            {{ ucfirst($project->project_type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined text-gray-400 mr-1 text-base">person</span>
                            {{ $project->user->name ?? 'N/A' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($project->status === 'production')
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 border border-green-200">Production</span>
                        @elseif($project->status === 'casting')
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">Casting</span>
                        @else
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 border border-gray-200">{{ ucfirst($project->status) }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-1">
                            @if($project->needs_crew)
                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded border border-blue-200">Équipe</span>
                            @endif
                            @if($project->needs_equipment)
                                <span class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded border border-green-200">Matériel</span>
                            @endif
                            @if($project->needs_location)
                                <span class="text-xs bg-purple-100 text-purple-800 px-2 py-0.5 rounded border border-purple-200">Lieu</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('admin.community.projects.edit', $project->id) }}" class="p-1 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded transition-colors" title="Modifier">
                                <span class="material-symbols-outlined">edit</span>
                            </a>
                            <form action="{{ route('admin.community.projects.destroy', $project->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce projet ?');">
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
                                <span class="material-symbols-outlined text-3xl text-gray-400">work</span>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Aucun projet</h3>
                            <p class="text-gray-500 mt-1">Créez votre premier projet.</p>
                            <a href="{{ route('admin.community.projects.create') }}" class="mt-4 text-red-600 hover:text-red-700 font-medium">
                                Créer un projet
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $projects->links() }}
    </div>
</div>
@endsection
