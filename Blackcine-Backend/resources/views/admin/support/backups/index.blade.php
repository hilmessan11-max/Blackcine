@extends('layouts.app')

@section('title', 'Sauvegardes Système - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Sauvegardes Système</h1>
        <p class="text-gray-600 mt-2">Gestion des backups de la base de données et des fichiers</p>
    </div>
    <button class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center shadow-sm">
        <span class="material-symbols-outlined mr-2">backup</span>
        Créer une sauvegarde
    </button>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Liste des sauvegardes -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Historique des sauvegardes</h3>
                <span class="text-xs text-gray-500">Stockage utilisé : 4.2 GB / 50 GB</span>
            </div>
            <div class="table-container">
                <table class="w-full min-w-max">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fichier</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Taille</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <!-- Item 1 -->
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <span class="material-symbols-outlined text-green-600 mr-2">check_circle</span>
                                    <span class="text-sm font-medium text-gray-900">backup-2024-12-05-1200.zip</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">05/12/2024 12:00</td>
                            <td class="px-6 py-4 text-sm text-gray-600">145 MB</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Complet</span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <button class="text-blue-600 hover:text-blue-900 mr-3" title="Télécharger">
                                    <span class="material-symbols-outlined">download</span>
                                </button>
                                <button class="text-red-600 hover:text-red-900" title="Supprimer">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </td>
                        </tr>

                        <!-- Item 2 -->
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <span class="material-symbols-outlined text-green-600 mr-2">check_circle</span>
                                    <span class="text-sm font-medium text-gray-900">backup-2024-12-04-1200.zip</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">04/12/2024 12:00</td>
                            <td class="px-6 py-4 text-sm text-gray-600">142 MB</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Complet</span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <button class="text-blue-600 hover:text-blue-900 mr-3" title="Télécharger">
                                    <span class="material-symbols-outlined">download</span>
                                </button>
                                <button class="text-red-600 hover:text-red-900" title="Supprimer">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </td>
                        </tr>

                        <!-- Item 3 -->
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <span class="material-symbols-outlined text-green-600 mr-2">check_circle</span>
                                    <span class="text-sm font-medium text-gray-900">db-dump-2024-12-03.sql</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">03/12/2024 02:00</td>
                            <td class="px-6 py-4 text-sm text-gray-600">25 MB</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">Base de données</span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <button class="text-blue-600 hover:text-blue-900 mr-3" title="Télécharger">
                                    <span class="material-symbols-outlined">download</span>
                                </button>
                                <button class="text-red-600 hover:text-red-900" title="Supprimer">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Configuration -->
    <div class="lg:col-span-1 space-y-6">
        <!-- État du système -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">État du système</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700">Disque dur</span>
                        <span class="text-sm font-medium text-gray-700">65%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: 65%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700">Base de données</span>
                        <span class="text-sm font-medium text-gray-700">Healthy</span>
                    </div>
                    <div class="flex items-center text-green-600 text-sm">
                        <span class="material-symbols-outlined text-sm mr-1">check_circle</span>
                        Connexion active
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700">Dernier backup</span>
                        <span class="text-sm font-medium text-gray-700">Il y a 1h</span>
                    </div>
                    <div class="flex items-center text-green-600 text-sm">
                        <span class="material-symbols-outlined text-sm mr-1">check_circle</span>
                        Succès
                    </div>
                </div>
            </div>
        </div>

        <!-- Planification -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900">Planification</h3>
            </div>
            <div class="p-6">
                <form action="#" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Sauvegarde automatique</span>
                                <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                    <input type="checkbox" name="auto_backup" id="auto_backup" checked class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer"/>
                                    <label for="auto_backup" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                                </div>
                            </label>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fréquence</label>
                            <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm form-input p-2">
                                <option value="daily">Quotidienne (00:00)</option>
                                <option value="weekly">Hebdomadaire (Dimanche)</option>
                                <option value="monthly">Mensuelle (1er du mois)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Rétention</label>
                            <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm form-input p-2">
                                <option value="7">7 derniers jours</option>
                                <option value="30" selected>30 derniers jours</option>
                                <option value="90">3 derniers mois</option>
                            </select>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-900">
                                Mettre à jour
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Toggle Switch */
    .toggle-checkbox:checked {
        right: 0;
        border-color: #DC2626;
    }
    .toggle-checkbox:checked + .toggle-label {
        background-color: #DC2626;
    }
    .toggle-checkbox {
        right: 0;
        z-index: 1;
        border-color: #E5E7EB;
        transition: all 0.3s;
    }
    .toggle-label {
        width: 2.5rem;
        background-color: #E5E7EB;
        transition: all 0.3s;
    }
</style>
@endsection
