<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><title>{{ $pageTitle }}</title></head>
<body>
<h1>{{ $title }}</h1>
@foreach($titles as $title)
    <div class="title-card">
        <a href="{{ route('catalog.title.show', $title->slug) }}">{{ $title->name }}</a>
        @if($title->posterAsset)
            <img src="{{ $title->posterAsset->path }}" alt="{{ $title->name }}">
        @endif
        <span>{{ $title->origin_country }}</span>
    </div>
@endforeach
</body>
</html>
