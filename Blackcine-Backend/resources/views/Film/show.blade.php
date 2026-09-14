<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><title>{{ $title->name }}</title></head>
<body>
<h1>{{ $title->name }}</h1>
<p>Apercu: {{ $title->synopsis }}</p>
<span>{{ $title->origin_country }}</span>
<span>{{ $title->release_date?->format('Y') }}</span>
@if($title->posterAsset)
    <img src="{{ $title->posterAsset->path }}" alt="{{ $title->name }}">
@endif
@foreach($title->genres as $genre)
    <span>{{ $genre->name }}</span>
@endforeach
@if($title->reviews->count())
    <h2>Avis</h2>
    @foreach($title->reviews as $review)
        <div>
            <strong>{{ $review->title }}</strong>
            <p>{{ $review->content }}</p>
            <span>{{ $review->rating }}/5</span>
        </div>
    @endforeach
@endif
</body>
</html>
