@extends('layouts.app')

@section('title', 'Ajouter un Ticket - BlackCine Admin')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Ajouter un Ticket</h1>
            <p class="text-gray-600 mt-2">Créer un nouveau ticket manuellement</p>
        </div>
        <a href="{{ route('admin.tickets.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400">
            ← Retour
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6 max-w-2xl mx-auto">
    <form action="{{ route('admin.tickets.store') }}" method="POST">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="showtime_id">Séance *</label>
            <select name="showtime_id" id="showtime_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Sélectionnez une séance</option>
                @foreach($showtimes as $showtime)
                    <option value="{{ $showtime->id }}">
                        {{ $showtime->movie->title ?? 'Film inconnu' }} - {{ $showtime->cinema->name ?? 'Cinéma inconnu' }} ({{ $showtime->start_time->format('d/m/Y H:i') }})
                    </option>
                @endforeach
            </select>
            @error('showtime_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="holder_name">Nom du titulaire *</label>
            <input type="text" name="holder_name" id="holder_name" required value="{{ old('holder_name') }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Ex: Jean Dupont">
            @error('holder_name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="seat_label">Siège *</label>
                <input type="text" name="seat_label" id="seat_label" required value="{{ old('seat_label') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Ex: A12">
                @error('seat_label')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="price_cents">Prix (en centimes) *</label>
                <input type="number" name="price_cents" id="price_cents" required value="{{ old('price_cents') }}" min="0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Ex: 1000 pour 10.00€">
                <p class="text-xs text-gray-500 mt-1">Ex: 1000 = 10.00 EUR</p>
                @error('price_cents')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="status">Statut *</label>
            <select name="status" id="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="confirmed" selected>Confirmé</option>
                <option value="pending">En attente</option>
            </select>
            @error('status')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 font-semibold">
            Créer le Ticket
        </button>
    </form>
</div>
@endsection
