@extends('layouts.app')

@section('title', 'Sélections éditoriales - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Sélections éditoriales</h1>
        <p class="text-gray-600 mt-2">Gérer les coups de cœur, carrousels et contenus à la une</p>
    </div>
    <a href="{{ route('admin.editorial.selections.create') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center shadow-sm transition-colors">
        <span class="material-symbols-outlined mr-2">add_circle</span>
        Créer une sélection
    </a>
</div>

<!-- Stats rapides -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total</p>
                <p class="text-2xl font-bold text-gray-900">{{ $selections->total() }}</p>
            </div>
            <div class="bg-blue-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-blue-600">collections</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Actives</p>
                <p class="text-2xl font-bold text-gray-900">{{ $selections->where('is_active', true)->count() }}</p>
            </div>
            <div class="bg-green-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-green-600">check_circle</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Coups de cœur</p>
                <p class="text-2xl font-bold text-gray-900">{{ $selections->where('type', 'coup_de_coeur')->count() }}</p>
            </div>
            <div class="bg-red-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-red-600">favorite</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Carrousels</p>
                <p class="text-2xl font-bold text-gray-900">{{ $selections->where('type', 'carousel')->count() }}</p>
            </div>
            <div class="bg-purple-50 p-3 rounded-full">
                <span class="material-symbols-outlined text-purple-600">view_carousel</span>
            </div>
        </div>
    </div>
</div>

<!-- Liste -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100">
    <div class="table-container">
        <table class="w-full min-w-max">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Sélection</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ordre</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($selections as $selection)
                <tr class="hover:bg-gray-50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-start">
                            @if($selection->type === 'coup_de_coeur')
                                <div class="bg-red-50 group-hover:bg-red-100 p-2 rounded-lg mr-3 transition-colors">
                                    <span class="material-symbols-outlined text-red-600">favorite</span>
                                </div>
                            @elseif($selection->type === 'carousel')
                                <div class="bg-purple-50 group-hover:bg-purple-100 p-2 rounded-lg mr-3 transition-colors">
                                    <span class="material-symbols-outlined text-purple-600">view_carousel</span>
                                </div>
                            @else
                                <div class="bg-green-50 group-hover:bg-green-100 p-2 rounded-lg mr-3 transition-colors">
                                    <span class="material-symbols-outlined text-green-600">stars</span>
                                </div>
                            @endif
                            
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $selection->title }}</div>
                                @if($selection->description)
                                    <div class="text-xs text-gray-500 mt-0.5">{{ Str::limit($selection->description, 60) }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($selection->type === 'coup_de_coeur')
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 border border-red-200">
                                Coup de cœur
                            </span>
                        @elseif($selection->type === 'carousel')
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800 border border-purple-200">
                                Carousel
                            </span>
                        @else
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 border border-green-200">
                                À ne pas manquer
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2 py-1 rounded bg-gray-100 text-gray-700 text-sm font-mono">
                            #{{ $selection->display_order ?? 0 }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($selection->is_active)
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 border border-green-200">
                                Actif
                            </span>
                        @else
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                                Inactif
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('admin.editorial.selections.edit', $selection->id) }}" class="p-1 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded transition-colors" title="Modifier">
                                <span class="material-symbols-outlined">edit</span>
                            </a>
                            <form action="{{ route('admin.editorial.selections.destroy', $selection->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cette sélection ?');">
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
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="bg-gray-100 p-4 rounded-full mb-3">
                                <span class="material-symbols-outlined text-3xl text-gray-400">collections</span>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Aucune sélection</h3>
                            <p class="text-gray-500 mt-1">Créez votre première sélection éditoriale.</p>
                            <a href="{{ route('admin.editorial.selections.create') }}" class="mt-4 text-red-600 hover:text-red-700 font-medium">
                                Créer une sélection
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $selections->links() }}
    </div>
</div>
@endsection
