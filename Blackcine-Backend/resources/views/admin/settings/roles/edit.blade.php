@extends('layouts.app')

@section('title', 'Modifier rôle - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div><h1 class="text-3xl font-bold text-gray-800">Modifier: {{ $role->name }}</h1><p class="text-gray-600 mt-2">Mise à jour des permissions</p></div>
    <a href="{{ route('admin.settings.roles.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors"><span class="material-symbols-outlined mr-1">arrow_back</span>Retour</a>
</div>

<form action="{{ route('admin.settings.roles.update', $role) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonne principale : Permissions -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center"><span class="material-symbols-outlined mr-2 text-red-600">vpn_key</span>Permissions</h3>
                    <div class="text-sm text-gray-500">{{ $role->permissions->count() }} active(s)</div>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        @foreach($permissions as $category => $perms)
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <div class="bg-gray-50 px-4 py-2 border-b border-gray-200 flex items-center justify-between cursor-pointer" onclick="toggleCategory('{{ Str::slug($category) }}')">
                                    <h4 class="font-medium text-gray-800 capitalize flex items-center">
                                        <span class="material-symbols-outlined mr-2 text-gray-500 text-sm">folder</span>
                                        {{ str_replace('_', ' ', $category) }}
                                    </h4>
                                    <span class="material-symbols-outlined text-gray-400 transform transition-transform" id="icon-{{ Str::slug($category) }}">expand_more</span>
                                </div>
                                <div id="content-{{ Str::slug($category) }}" class="p-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach($perms as $permission)
                                        <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors group {{ $role->hasPermissionTo($permission->name) ? 'bg-red-50 border-red-200' : '' }}">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }} class="h-4 w-4 text-red-600 rounded border-gray-300 focus:ring-red-500">
                                            <span class="ml-3 text-sm text-gray-700 group-hover:text-gray-900">{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne latérale : Info Rôle -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informations</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom du rôle <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $role->name) }}" required class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm" {{ in_array($role->name, ['SuperAdmin', 'Admin']) ? 'readonly' : '' }}>
                        @if(in_array($role->name, ['SuperAdmin', 'Admin']))
                            <p class="text-xs text-red-500 mt-1">Le nom de ce rôle système ne peut pas être modifié.</p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">{{ old('description', $role->description) }}</textarea>
                    </div>

                    <div class="pt-4 border-t border-gray-100">
                        <button type="submit" class="w-full bg-red-600 text-white py-2.5 px-4 rounded-lg hover:bg-red-700 transition-all shadow-md font-medium flex justify-center items-center">
                            <span class="material-symbols-outlined mr-2 text-sm">save</span>Enregistrer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    function toggleCategory(id) {
        const content = document.getElementById('content-' + id);
        const icon = document.getElementById('icon-' + id);
        
        if (content.style.display === 'none') {
            content.style.display = 'grid';
            icon.style.transform = 'rotate(0deg)';
        } else {
            content.style.display = 'none';
            icon.style.transform = 'rotate(-90deg)';
        }
    }
</script>
@endsection
