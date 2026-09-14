@extends('layouts.app')

@section('title', 'Communication - BlackCine Admin')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Communication</h1>
        <p class="text-gray-600 mt-2">Vue d'ensemble des notifications, emails, push et newsletter.</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.notifications.templates.index') }}" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 flex items-center shadow-sm transition-colors">
            <span class="material-symbols-outlined mr-2">email</span>
            Modèles
        </a>
        <a href="{{ route('admin.newsletter.campaigns.index') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center shadow-sm transition-colors">
            <span class="material-symbols-outlined mr-2">campaign</span>
            Campagne
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <p class="text-sm text-gray-500">Modèles email</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['email_templates']['total'] }}</p>
        <p class="text-sm text-green-600 mt-1">{{ $stats['email_templates']['active'] }} actifs</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <p class="text-sm text-gray-500">Push notifications</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['push_notifications']['total'] }}</p>
        <p class="text-sm text-blue-600 mt-1">{{ $stats['push_notifications']['sent'] }} envoyées, {{ $stats['push_notifications']['scheduled'] }} planifiées</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <p class="text-sm text-gray-500">Abonnés newsletter</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['newsletter']['subscribers'] }}</p>
        <p class="text-sm text-green-600 mt-1">{{ $stats['newsletter']['active_subscribers'] }} actifs</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
        <p class="text-sm text-gray-500">Campagnes newsletter</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['newsletter']['campaigns'] }}</p>
        <p class="text-sm text-purple-600 mt-1">{{ $stats['newsletter']['sent_campaigns'] }} envoyées</p>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-900">Modèles récents</h2>
            <a href="{{ route('admin.notifications.templates.index') }}" class="text-sm text-red-600 hover:text-red-700">Voir tout</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentTemplates as $template)
                <div class="px-5 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">{{ $template->name }}</p>
                            <p class="text-sm text-gray-500">{{ $template->key }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full {{ $template->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $template->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="px-5 py-8 text-center text-sm text-gray-500">Aucun modèle disponible.</div>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-900">Push récents</h2>
            <a href="{{ route('admin.notifications.push.index') }}" class="text-sm text-red-600 hover:text-red-700">Voir tout</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentPushNotifications as $notification)
                <div class="px-5 py-4">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="font-medium text-gray-900">{{ $notification->title }}</p>
                            <p class="text-sm text-gray-500">{{ $notification->target_audience }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full {{ $notification->status === 'sent' ? 'bg-green-100 text-green-700' : ($notification->status === 'scheduled' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600') }}">
                            {{ ucfirst($notification->status) }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="px-5 py-8 text-center text-sm text-gray-500">Aucune notification push.</div>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-900">Campagnes récentes</h2>
            <a href="{{ route('admin.newsletter.campaigns.index') }}" class="text-sm text-red-600 hover:text-red-700">Voir tout</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentCampaigns as $campaign)
                <div class="px-5 py-4">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="font-medium text-gray-900">{{ $campaign->name }}</p>
                            <p class="text-sm text-gray-500">{{ $campaign->subject }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full {{ $campaign->status === 'sent' ? 'bg-green-100 text-green-700' : ($campaign->status === 'scheduled' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600') }}">
                            {{ ucfirst($campaign->status) }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="px-5 py-8 text-center text-sm text-gray-500">Aucune campagne disponible.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
