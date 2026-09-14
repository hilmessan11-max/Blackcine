@extends('layouts.app')

@section('title', 'Créer un cinéma - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Créer un cinéma</h1>
        <p class="text-gray-600 mt-2">Ajouter un nouveau cinéma partenaire</p>
    </div>
    <a href="{{ route('admin.boxoffice.cinemas.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
        <span class="material-symbols-outlined mr-1">arrow_back</span>
        Retour aux cinémas
    </a>
</div>

<form action="{{ route('admin.boxoffice.cinemas.store') }}" method="POST">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonne principale -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations générales -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informations générales</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                    </div>

                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">Nom de l'entreprise</label>
                        <input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                    </div>
                </div>
            </div>

            <!-- Adresse -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Adresse</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="address_line1" class="block text-sm font-medium text-gray-700 mb-1">Adresse ligne 1</label>
                        <input type="text" name="address_line1" id="address_line1" value="{{ old('address_line1') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                    </div>

                    <div>
                        <label for="address_line2" class="block text-sm font-medium text-gray-700 mb-1">Adresse ligne 2</label>
                        <input type="text" name="address_line2" id="address_line2" value="{{ old('address_line2') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                            <input type="text" name="city" id="city" value="{{ old('city') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                        </div>

                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">Code postal</label>
                            <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                        </div>

                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Pays</label>
                            <input type="text" name="country" id="country" value="{{ old('country') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne latérale -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Paramètres -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Paramètres</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="commission_rate" class="block text-sm font-medium text-gray-700 mb-1">Commission (%)</label>
                        <input type="number" name="commission_rate" id="commission_rate" value="{{ old('commission_rate', 0) }}" step="0.01" min="0" max="100"
                            class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm">
                    </div>

                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <input type="checkbox" name="is_active" id="is_active" value="1" checked class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                        <label for="is_active" class="ml-2 text-sm text-gray-700 font-medium">Cinéma actif</label>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="space-y-3">
                    <button type="submit" class="w-full bg-red-600 text-white py-2.5 px-4 rounded-lg hover:bg-red-700 transition-all shadow-md font-medium flex justify-center items-center">
                        <span class="material-symbols-outlined mr-2 text-sm">save</span>
                        Créer le cinéma
                    </button>
                    <a href="{{ route('admin.boxoffice.cinemas.index') }}" class="block w-full bg-gray-100 text-gray-700 py-2.5 px-4 rounded-lg hover:bg-gray-200 transition-all font-medium text-center">
                        Annuler
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
