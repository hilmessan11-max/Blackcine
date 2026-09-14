@extends('layouts.app')

@section('title', $title . ' - BlackCine Admin')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-12 text-center">
    <div class="mb-4">
        <span class="text-6xl">🚧</span>
    </div>
    <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $title }}</h1>
    <p class="text-gray-600 text-lg">Ce module est en cours de développement.</p>
    <div class="mt-8">
        <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800 font-medium">
            ← Retour au tableau de bord
        </a>
    </div>
</div>
@endsection
