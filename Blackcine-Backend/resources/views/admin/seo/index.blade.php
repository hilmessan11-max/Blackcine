@extends('layouts.app')

@section('title', 'SEO - BlackCine Admin')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Gestion SEO</h1>
    <p class="text-gray-600 mt-2">Optimisez le référencement de votre contenu</p>
</div>

<!-- Statistiques -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-600 text-sm">Métadonnées totales</p>
        <p class="text-3xl font-bold text-gray-800">{{ number_format($stats['total_meta']) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-600 text-sm">Titres avec SEO</p>
        <p class="text-3xl font-bold text-green-600">{{ number_format($stats['titles_with_seo']) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-600 text-sm">Articles avec SEO</p>
        <p class="text-3xl font-bold text-blue-600">{{ number_format($stats['articles_with_seo']) }}</p>
    </div>
</div>

<!-- Liste des métadonnées SEO -->
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-800">Métadonnées SEO</h2>
    </div>
    <div class="table-container">
        <table class="w-full min-w-max">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Titre Meta</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($seoMetas as $seoMeta)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ class_basename($seoMeta->seoable_type) }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ Str::limit($seoMeta->meta_title ?? 'N/A', 40) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($seoMeta->meta_description ?? 'N/A', 50) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $seoMeta->created_at->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openEditModal({{ $seoMeta->id }})" class="text-blue-600 hover:text-blue-900">Modifier</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Aucune métadonnée SEO trouvée</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $seoMetas->links() }}
    </div>
</div>

<!-- Modal Éditer SEO -->
<div id="editModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Modifier les métadonnées SEO</h3>
            <form action="{{ route('admin.seo.update') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="seoable_type">Type *</label>
                    <select name="seoable_type" id="seoable_type" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="App\Models\Title">Title</option>
                        <option value="App\Models\Article">Article</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="seoable_id">ID *</label>
                    <input type="number" name="seoable_id" id="seoable_id" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="meta_title">Titre Meta (max 60 caractères)</label>
                    <input type="text" name="meta_title" id="meta_title" maxlength="60"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="meta_description">Description Meta (max 160 caractères)</label>
                    <textarea name="meta_description" id="meta_description" rows="3" maxlength="160"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="meta_keywords">Mots-clés</label>
                    <input type="text" name="meta_keywords" id="meta_keywords"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="canonical_url">URL Canonique</label>
                    <input type="url" name="canonical_url" id="canonical_url"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="flex items-center justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="text-gray-600 hover:text-gray-800">
                        Annuler
                    </button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditModal(seoMetaId) {
    // Charger les données du SEO Meta et remplir le formulaire
    document.getElementById('editModal').classList.remove('hidden');
}
</script>
@endsection

