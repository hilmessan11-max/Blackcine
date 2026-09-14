@extends('layouts.app')

@section('title', 'SMS & OTP - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">SMS & OTP</h1>
        <p class="text-gray-600 mt-2">Gestion des notifications SMS et codes de vérification</p>
    </div>
    <button class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center shadow-sm">
        <span class="material-symbols-outlined mr-2">send</span>
        Envoyer un SMS test
    </button>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Configuration -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
                <p class="text-sm font-medium text-gray-500">SMS envoyés (Mois)</p>
                <p class="text-2xl font-bold text-gray-900">1,245</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
                <p class="text-sm font-medium text-gray-500">Taux de livraison</p>
                <p class="text-2xl font-bold text-gray-900">98.5%</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
                <p class="text-sm font-medium text-gray-500">Coût estimé</p>
                <p class="text-2xl font-bold text-gray-900">45.20 €</p>
            </div>
        </div>

        <!-- Paramètres Gateway -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Configuration Passerelle</h3>
                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active: Twilio</span>
            </div>
            <div class="p-6">
                <form action="#" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fournisseur</label>
                            <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm form-input p-2">
                                <option value="twilio" selected>Twilio</option>
                                <option value="nexmo">Nexmo / Vonage</option>
                                <option value="aws_sns">AWS SNS</option>
                                <option value="local">Local (Dev)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Account SID / API Key</label>
                            <input type="password" value="ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxx" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm form-input p-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Auth Token / API Secret</label>
                            <input type="password" value="xxxxxxxxxxxxxxxxxxxxxxxxxxxxx" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm form-input p-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Numéro d'envoi (Sender ID)</label>
                            <input type="text" value="BlackCine" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm form-input p-2">
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-900">
                            Sauvegarder
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modèles de messages -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900">Modèles de Messages</h3>
            </div>
            <div class="divide-y divide-gray-200">
                <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer">
                    <div class="flex justify-between">
                        <p class="text-sm font-medium text-gray-900">Code de vérification (OTP)</p>
                        <span class="text-xs text-gray-500">ID: otp_verification</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">Votre code de vérification BlackCiné est : {code}. Valide pour 10 minutes.</p>
                </div>
                <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer">
                    <div class="flex justify-between">
                        <p class="text-sm font-medium text-gray-900">Confirmation de commande</p>
                        <span class="text-xs text-gray-500">ID: order_confirm</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">Merci pour votre commande #{order_id}. Vos billets sont disponibles dans l'application.</p>
                </div>
                <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer">
                    <div class="flex justify-between">
                        <p class="text-sm font-medium text-gray-900">Rappel de séance</p>
                        <span class="text-xs text-gray-500">ID: showtime_reminder</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">Rappel : Votre séance pour "{movie}" commence dans 30 minutes. Bon film !</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Logs récents -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow overflow-hidden h-full">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Logs d'envoi</h3>
                <a href="#" class="text-xs text-blue-600 hover:text-blue-800">Voir tout</a>
            </div>
            <div class="overflow-y-auto max-h-[800px]">
                <ul class="divide-y divide-gray-200">
                    <li class="p-4">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs font-semibold text-green-600 bg-green-100 px-2 py-0.5 rounded">Livré</span>
                            <span class="text-xs text-gray-500">Il y a 2 min</span>
                        </div>
                        <p class="text-sm font-medium text-gray-900">+33 6 12 34 56 78</p>
                        <p class="text-xs text-gray-500 truncate">Votre code de vérification BlackCiné est : 4589...</p>
                    </li>
                    <li class="p-4">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs font-semibold text-green-600 bg-green-100 px-2 py-0.5 rounded">Livré</span>
                            <span class="text-xs text-gray-500">Il y a 15 min</span>
                        </div>
                        <p class="text-sm font-medium text-gray-900">+33 6 98 76 54 32</p>
                        <p class="text-xs text-gray-500 truncate">Rappel : Votre séance pour "Dune 2" commence...</p>
                    </li>
                    <li class="p-4">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs font-semibold text-red-600 bg-red-100 px-2 py-0.5 rounded">Échec</span>
                            <span class="text-xs text-gray-500">Il y a 42 min</span>
                        </div>
                        <p class="text-sm font-medium text-gray-900">+33 7 00 00 00 00</p>
                        <p class="text-xs text-gray-500 truncate">Votre code de vérification BlackCiné est : 1234...</p>
                        <p class="text-xs text-red-500 mt-1">Erreur: Numéro invalide</p>
                    </li>
                    <li class="p-4">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs font-semibold text-green-600 bg-green-100 px-2 py-0.5 rounded">Livré</span>
                            <span class="text-xs text-gray-500">Il y a 1h</span>
                        </div>
                        <p class="text-sm font-medium text-gray-900">+33 6 55 44 33 22</p>
                        <p class="text-xs text-gray-500 truncate">Merci pour votre commande #9988. Vos billets...</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
