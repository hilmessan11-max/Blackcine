@extends('layouts.app')

@section('title', 'Créer une séance - BlackCine Admin')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Créer une séance</h1>
    <p class="text-gray-600 mt-2">Programmer une nouvelle séance de cinéma</p>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.boxoffice.showtimes.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Film *</label>
                <select name="title_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Sélectionner un film</option>
                    @foreach($titles as $title)
                        <option value="{{ $title->id }}">{{ $title->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cinéma *</label>
                <select name="cinema_id" id="cinema_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Sélectionner un cinéma</option>
                    @foreach($cinemas as $cinema)
                        <option value="{{ $cinema->id }}">{{ $cinema->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Salle *</label>
                <select name="room_id" id="room_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Sélectionner une salle</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date & Heure *</label>
                <input type="datetime-local" name="starts_at" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Durée (minutes)</label>
                <input type="number" name="runtime_minutes" class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Prix de base (centimes) *</label>
                <input type="number" name="base_price_cents" required min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut *</label>
                <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="scheduled">Programmée</option>
                    <option value="active">Active</option>
                    <option value="cancelled">Annulée</option>
                    <option value="completed">Terminée</option>
                </select>
            </div>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.boxoffice.showtimes.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                Annuler
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Créer la séance
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById('cinema_id').addEventListener('change', function() {
    const cinemaId = this.value;
    const roomSelect = document.getElementById('room_id');
    roomSelect.innerHTML = '<option value="">Chargement...</option>';

    if (cinemaId) {
        fetch(`/admin/boxoffice/cinemas/${cinemaId}/rooms`)
            .then(response => response.json())
            .then(rooms => {
                roomSelect.innerHTML = '<option value="">Sélectionner une salle</option>';
                rooms.forEach(room => {
                    roomSelect.innerHTML += `<option value="${room.id}">${room.name}</option>`;
                });
            });
    } else {
        roomSelect.innerHTML = '<option value="">Sélectionner une salle</option>';
    }
});
</script>
@endsection

