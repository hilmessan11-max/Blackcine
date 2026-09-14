@extends('layouts.app')

@section('title', 'Ticket #' . $ticket->id . ' - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <div class="flex items-center gap-3">
            <h1 class="text-3xl font-bold text-gray-800">Ticket #{{ $ticket->id }}</h1>
            @php
                $sColors = ['open' => 'bg-yellow-100 text-yellow-800', 'in_progress' => 'bg-blue-100 text-blue-800', 'closed' => 'bg-green-100 text-green-800'];
            @endphp
            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $sColors[$ticket->status] ?? 'bg-gray-100 text-gray-800' }}">
                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
            </span>
        </div>
        <p class="text-gray-600 mt-2">{{ $ticket->subject }}</p>
    </div>
    <a href="{{ route('admin.support.tickets.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors"><span class="material-symbols-outlined mr-1">arrow_back</span>Retour</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Conversation -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Message initial -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="bg-gray-200 rounded-full p-2"><span class="material-symbols-outlined text-gray-600">person</span></div>
                    <div>
                        <p class="text-sm font-bold text-gray-900">{{ $ticket->user_name ?? 'Utilisateur' }}</p>
                        <p class="text-xs text-gray-500">{{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                <span class="text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded">Message initial</span>
            </div>
            <div class="p-6 text-gray-800 prose max-w-none">
                {!! nl2br(e($ticket->message)) !!}
            </div>
        </div>

        <!-- Réponses (Placeholder pour l'instant, à implémenter si le modèle a des réponses) -->
        @if(isset($ticket->replies) && $ticket->replies->count() > 0)
            @foreach($ticket->replies as $reply)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden {{ $reply->is_admin ? 'ml-8 border-l-4 border-l-red-500' : 'mr-8' }}">
                    <div class="px-6 py-3 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="{{ $reply->is_admin ? 'bg-red-100' : 'bg-gray-200' }} rounded-full p-1.5">
                                <span class="material-symbols-outlined {{ $reply->is_admin ? 'text-red-600' : 'text-gray-600' }} text-sm">
                                    {{ $reply->is_admin ? 'support_agent' : 'person' }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ $reply->user_name ?? ($reply->is_admin ? 'Support' : 'Utilisateur') }}</p>
                                <p class="text-xs text-gray-500">{{ $reply->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 text-gray-800 text-sm">
                        {!! nl2br(e($reply->message)) !!}
                    </div>
                </div>
            @endforeach
        @endif

        <!-- Formulaire de réponse -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Répondre</h3>
            <form action="{{ route('admin.support.tickets.reply', $ticket->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <textarea name="message" rows="4" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition-colors shadow-sm" placeholder="Votre réponse..."></textarea>
                </div>
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="close_ticket" class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-600">Fermer le ticket après réponse</span>
                        </label>
                    </div>
                    <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors shadow-sm font-medium flex items-center">
                        <span class="material-symbols-outlined mr-2 text-sm">send</span>Envoyer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Actions rapides</h3>
            <form action="{{ route('admin.support.tickets.update-status', $ticket->id) }}" method="POST" class="space-y-3">
                @csrf @method('PATCH')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Changer statut</label>
                    <select name="status" onchange="this.form.submit()" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 text-sm">
                        <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Ouvert</option>
                        <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>En cours</option>
                        <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Fermé</option>
                    </select>
                </div>
            </form>
            <form action="{{ route('admin.support.tickets.update-priority', $ticket->id) }}" method="POST" class="mt-3">
                @csrf @method('PATCH')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Changer priorité</label>
                    <select name="priority" onchange="this.form.submit()" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 text-sm">
                        <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>Basse</option>
                        <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>Moyenne</option>
                        <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>Haute</option>
                        <option value="urgent" {{ $ticket->priority == 'urgent' ? 'selected' : '' }}>Urgente</option>
                    </select>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Info Client</h3>
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <div class="bg-gray-100 p-2 rounded-full"><span class="material-symbols-outlined text-gray-500">person</span></div>
                    <div>
                        <p class="text-xs text-gray-500">Nom</p>
                        <p class="text-sm font-medium text-gray-900">{{ $ticket->user_name ?? 'Anonyme' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-gray-100 p-2 rounded-full"><span class="material-symbols-outlined text-gray-500">email</span></div>
                    <div>
                        <p class="text-xs text-gray-500">Email</p>
                        <p class="text-sm font-medium text-gray-900">{{ $ticket->user_email }}</p>
                    </div>
                </div>
                @if($ticket->user_id)
                    <div class="pt-3 border-t border-gray-100">
                        <a href="{{ route('admin.users.show', $ticket->user_id) }}" class="text-blue-600 hover:text-blue-800 text-sm flex items-center justify-center">
                            Voir profil client <span class="material-symbols-outlined text-sm ml-1">arrow_forward</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
