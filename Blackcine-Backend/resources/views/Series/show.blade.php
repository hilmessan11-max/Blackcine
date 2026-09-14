@extends('layouts.app')

@section('title', $title->name . ' - Fiche Titre')

@section('content')
    @include('catalog.show', ['title' => $title])
@endsection
