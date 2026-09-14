@extends('layouts.app')

@section('title', 'Gestion des commentaires - BlackCine Admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Commentaires</h1>
        <p class="text-gray-600 mt-2">Gérer les commentaires des utilisateurs</p>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <div class="flex space-x-2">
            <a href="{{ route('admin.moderation.comments') }}" class="px-3 py-1 rounded-md text-sm {{ !request('status') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">Tous</a>
            <a href="{{ route('admin.moderation.comments', ['status' => 'pending']) }}" class="px-3 py-1 rounded-md text-sm {{ request('status') == 'pending' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">En attente</a>
            <a href="{{ route('admin.moderation.comments', ['status' => 'approved']) }}" class="px-3 py-1 rounded-md text-sm {{ request('status') == 'approved' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">Approuvés</a>
            <a href="{{ route('admin.moderation.comments', ['status' => 'rejected']) }}" class="px-3 py-1 rounded-md text-sm {{ request('status') == 'rejected' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">Rejetés</a>
            <a href="{{ route('admin.moderation.comments', ['status' => 'spam']) }}" class="px-3 py-1 rounded-md text-sm {{ request('status') == 'spam' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">Spam</a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auteur</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contenu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sur</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($comments as $comment)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8">
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-500">
                                        <span class="text-xs font-medium leading-none text-white">{{ substr($comment->user->name ?? 'U', 0, 2) }}</span>
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $comment->user->name ?? 'Utilisateur supprimé' }}</div>
                                    <div class="text-sm text-gray-500">{{ $comment->user->email ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $comment->content }}">{{Str::limit($comment->content, 50)}}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">
                                {{ class_basename($comment->commentable_type) }} #{{ $comment->commentable_id }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $comment->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $comment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $comment->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $comment->status === 'spam' ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ ucfirst($comment->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $comment->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @if($comment->status === 'pending')
                                <form action="{{ route('admin.moderation.comments.status', $comment->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="text-green-600 hover:text-green-900 mr-2">Approuver</button>
                                </form>
                                <form action="{{ route('admin.moderation.comments.status', $comment->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="text-red-600 hover:text-red-900">Rejeter</button>
                                </form>
                            @else
                                <form action="{{ route('admin.moderation.comments.status', $comment->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <input type="hidden" name="status" value="pending">
                                    <button type="submit" class="text-gray-600 hover:text-gray-900">Réinitialiser</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Aucun commentaire trouvé.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $comments->links() }}
    </div>
</div>
@endsection
