<div class="space-y-8">
    <section class="overflow-hidden rounded-lg border border-gray-100 bg-white shadow-sm">
        <div class="relative">
            <div class="h-56 bg-gray-200 sm:h-72 lg:h-96">
                @if($title->backdrop_url)
                    <img src="{{ $title->backdrop_url }}" alt="{{ $title->name }}" class="h-full w-full object-cover">
                @else
                    <div class="h-full w-full bg-gradient-to-r from-gray-900 via-gray-700 to-gray-900"></div>
                @endif
            </div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-black/0"></div>

            <div class="absolute inset-x-0 bottom-0 p-6 sm:p-8">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end">
                    <div class="w-32 shrink-0 overflow-hidden rounded-lg border border-white/20 bg-gray-100 shadow-xl sm:w-40">
                        <div class="aspect-[2/3]">
                            @if($title->poster_url)
                                <img src="{{ $title->poster_url }}" alt="{{ $title->name }}" class="h-full w-full object-cover">
                            @else
                                <div class="flex h-full items-center justify-center bg-gradient-to-br from-gray-200 to-gray-400 text-gray-500">
                                    <span class="material-symbols-outlined text-6xl">{{ $title->type === 'series' ? 'tv' : 'movie' }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="min-w-0 flex-1 text-white">
                        <div class="mb-3 flex flex-wrap gap-2">
                            <span class="rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-wide">
                                {{ $title->type === 'movie' ? 'Film' : ($title->type === 'series' ? 'Serie' : 'Classique') }}
                            </span>
                            @foreach($title->genres->take(3) as $genre)
                                <span class="rounded-full bg-white/10 px-3 py-1 text-xs font-medium">{{ $genre->name }}</span>
                            @endforeach
                        </div>

                        <h1 class="text-3xl font-bold sm:text-4xl lg:text-5xl">{{ $title->name }}</h1>
                        <p class="mt-3 max-w-3xl text-sm leading-6 text-white/85 sm:text-base">
                            {{ $title->synopsis ?? 'Aucun synopsis disponible pour ce titre.' }}
                        </p>

                        <div class="mt-5 flex flex-wrap gap-4 text-sm text-white/80">
                            <span>{{ $title->release_date?->format('Y') ?? 'Date inconnue' }}</span>
                            @if($title->runtime_minutes)
                                <span>{{ $title->runtime_minutes }} min</span>
                            @endif
                            <span>{{ $title->origin_country ?? 'Monde' }}</span>
                            <span>{{ number_format($title->views_count ?? 0) }} vues</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <div class="rounded-lg border border-gray-100 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900">Informations</h2>
                <dl class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-gray-500">Type</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900">{{ $title->type }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-gray-500">Statut</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900">{{ $title->status }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-gray-500">Pays</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900">{{ $title->origin_country ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-gray-500">Langue</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900">{{ $title->original_language ?? 'N/A' }}</dd>
                    </div>
                </dl>
            </div>

            @if($title->trailers->isNotEmpty())
                <div class="rounded-lg border border-gray-100 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900">Bandes-annonces</h2>
                    <div class="mt-4 space-y-3">
                        @foreach($title->trailers as $trailer)
                            <div class="flex items-center justify-between rounded-lg border border-gray-100 bg-gray-50 px-4 py-3">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $trailer->title }}</div>
                                    <div class="text-sm text-gray-500">{{ ucfirst($trailer->source_type) }}</div>
                                </div>
                                @if($trailer->source_url)
                                    <a href="{{ $trailer->source_url }}" target="_blank" rel="noreferrer" class="text-sm font-medium text-red-600 hover:text-red-700">
                                        Ouvrir
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($title->type === 'series' && $title->seasons->isNotEmpty())
                <div class="rounded-lg border border-gray-100 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900">Saisons</h2>
                    <div class="mt-4 space-y-4">
                        @foreach($title->seasons as $season)
                            <div class="rounded-lg border border-gray-100 p-4">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <div class="font-semibold text-gray-900">
                                            Saison {{ $season->season_number }}
                                            @if($season->name)
                                                - {{ $season->name }}
                                            @endif
                                        </div>
                                        @if($season->synopsis)
                                            <p class="mt-1 text-sm text-gray-600">{{ $season->synopsis }}</p>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $season->episodes->count() }} episode(s)</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($title->credits->isNotEmpty())
                <div class="rounded-lg border border-gray-100 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900">Credits principaux</h2>
                    <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
                        @foreach($title->credits->take(6) as $credit)
                            <div class="rounded-lg border border-gray-100 bg-gray-50 p-4">
                                <div class="font-medium text-gray-900">{{ $credit->person->name ?? 'Inconnu' }}</div>
                                <div class="text-sm text-gray-500">
                                    {{ $credit->job }}
                                    @if($credit->character_name)
                                        <span> - {{ $credit->character_name }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="rounded-lg border border-gray-100 bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Avis</h2>
                        <p class="mt-1 text-sm text-gray-500">{{ $title->total_reviews }} avis approuvés</p>
                    </div>
                    <div class="rounded-full bg-amber-50 px-3 py-1 text-sm font-semibold text-amber-700">
                        {{ number_format($title->average_rating, 1) }}/5
                    </div>
                </div>

                @auth
                    <form method="POST" action="{{ route('catalog.title.reviews.store', $title->slug) }}" class="mt-5 space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                            <div>
                                <label for="rating" class="block text-sm font-medium text-gray-700 mb-1">Note</label>
                                <select name="rating" id="rating" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-200">
                                    @for ($rating = 5; $rating >= 1; $rating--)
                                        <option value="{{ $rating }}" {{ old('rating', 5) == $rating ? 'selected' : '' }}>{{ $rating }}/5</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="sm:col-span-2">
                                <label for="review_title" class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
                                <input type="text" name="title" id="review_title" value="{{ old('title') }}" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-200">
                            </div>
                        </div>
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Votre avis</label>
                            <textarea name="content" id="content" rows="4" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-200">{{ old('content') }}</textarea>
                        </div>
                        <label class="flex items-center gap-3 text-sm text-gray-600">
                            <input type="checkbox" name="contains_spoiler" value="1" {{ old('contains_spoiler') ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                            Ce message contient un spoiler
                        </label>
                        <div class="flex justify-end">
                            <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">Publier l'avis</button>
                        </div>
                    </form>
                @else
                    <div class="mt-5 rounded-lg bg-gray-50 p-4 text-sm text-gray-600">
                        Connectez-vous pour laisser un avis.
                    </div>
                @endauth

                <div class="mt-6 space-y-4">
                    @forelse($title->reviews as $review)
                        <article class="rounded-lg border border-gray-100 bg-gray-50 p-4">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $review->title ?? 'Avis' }}</div>
                                    <div class="text-xs text-gray-500">
                                        {{ $review->user->name ?? 'Utilisateur' }} · {{ $review->created_at->format('d/m/Y') }}
                                    </div>
                                </div>
                                <div class="rounded-full bg-white px-3 py-1 text-sm font-semibold text-amber-700">
                                    {{ $review->rating }}/5
                                </div>
                            </div>
                            <p class="mt-3 text-sm leading-6 text-gray-700">{{ $review->content }}</p>
                            @if($review->contains_spoiler)
                                <span class="mt-3 inline-flex rounded-full bg-red-50 px-3 py-1 text-xs font-medium text-red-600">Contient un spoiler</span>
                            @endif
                        </article>
                    @empty
                        <div class="rounded-lg border border-dashed border-gray-200 p-4 text-sm text-gray-500">
                            Aucun avis approuvé pour le moment.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <aside class="space-y-6">
            <div class="rounded-lg border border-gray-100 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900">Apercu</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-600">
                    <div class="flex items-center justify-between">
                        <span>Genres</span>
                        <span class="font-medium text-gray-900">{{ $title->genres->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Trailers</span>
                        <span class="font-medium text-gray-900">{{ $title->trailers->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Saisons</span>
                        <span class="font-medium text-gray-900">{{ $title->seasons->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Credits</span>
                        <span class="font-medium text-gray-900">{{ $title->credits->count() }}</span>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-gray-100 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900">Genres</h2>
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach($title->genres as $genre)
                        <span class="rounded-full bg-gray-100 px-3 py-1 text-sm text-gray-700">{{ $genre->name }}</span>
                    @endforeach
                </div>
            </div>
        </aside>
    </section>
</div>
