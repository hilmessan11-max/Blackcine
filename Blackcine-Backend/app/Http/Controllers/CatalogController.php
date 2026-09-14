<?php

namespace App\Http\Controllers;

use App\Helpers\SanitizeHelper;
use App\Models\Genre;
use App\Models\Review;
use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CatalogController extends Controller
{
    public function index()
    {
        $titles = Title::with('genres')->latest()->paginate(20);

        $stats = [
            'total' => Title::count(),
            'movies' => Title::where('type', 'movie')->count(),
            'series' => Title::where('type', 'series')->count(),
            'classics' => Title::where('type', 'classic')->count(),
        ];

        return view('admin.catalog.index', compact('titles', 'stats'));
    }

    public function create()
    {
        return view('admin.catalog.create', [
            'types' => $this->types(),
            'genres' => Genre::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateTitle($request);
        $genres = $data['genres'] ?? [];
        unset($data['genres']);

        $title = Title::create($data);
        $title->genres()->sync($genres);

        return redirect()->route('admin.catalog.index')->with('success', 'Titre cree.');
    }

    public function edit($id)
    {
        $title = Title::with('genres')->findOrFail($id);

        return view('admin.catalog.edit', [
            'title' => $title,
            'types' => $this->types(),
            'genres' => Genre::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $title = Title::findOrFail($id);
        $data = $this->validateTitle($request, $title->id);
        $genres = $data['genres'] ?? [];
        unset($data['genres']);

        $title->update($data);
        $title->genres()->sync($genres);

        return redirect()->route('admin.catalog.index')->with('success', 'Titre mis a jour.');
    }

    public function destroy($id)
    {
        Title::findOrFail($id)->delete();

        return redirect()->route('admin.catalog.index')->with('success', 'Titre supprime.');
    }

    public function films(Request $request)
    {
        return $this->renderPublicListing(
            type: 'movie',
            title: 'Films',
            request: $request,
            emptyLabel: 'film'
        );
    }

    public function series(Request $request)
    {
        return $this->renderPublicListing(
            type: 'series',
            title: 'Series',
            request: $request,
            emptyLabel: 'serie'
        );
    }

    public function byGenre(Request $request, string $slug)
    {
        $genre = Genre::where('slug', $slug)->firstOrFail();
        $query = Title::with(['genres', 'posterAsset'])
            ->published()
            ->whereHas('genres', function ($inner) use ($slug) {
                $inner->where('slug', $slug);
            });

        return $this->renderPublicListing(
            type: null,
            title: $genre->name,
            request: $request,
            genre: $genre,
            query: $query,
            emptyLabel: 'titre'
        );
    }

    public function show(string $slug)
    {
        $title = Title::with([
            'genres',
            'posterAsset',
            'backdropAsset',
            'trailers',
            'seasons.episodes',
            'credits.person',
            'reviews' => fn ($query) => $query->approved()->with('user')->latest(),
        ])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        return view($title->type === 'series' ? 'Series.show' : 'Film.show', compact('title'));
    }

    public function storeReview(Request $request, string $slug)
    {
        $title = Title::published()->where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:2000'],
            'contains_spoiler' => ['nullable', 'boolean'],
        ]);

        $title->reviews()->create([
            'user_id' => $request->user()->id,
            'rating' => $validated['rating'],
            'title' => $validated['title'] ?? null,
            'content' => $validated['content'],
            'contains_spoiler' => $request->boolean('contains_spoiler'),
            'is_verified_purchase' => false,
            'status' => 'pending',
            'helpful_count' => 0,
            'not_helpful_count' => 0,
        ]);

        return redirect()->route('catalog.title.show', $slug)
            ->with('success', 'Votre avis a été envoyé et attend la modération.');
    }

    private function renderPublicListing(
        ?string $type,
        string $title,
        Request $request,
        ?Genre $genre = null,
        ?\Illuminate\Database\Eloquent\Builder $query = null,
        string $emptyLabel = 'titre'
    ) {
        $query = $query ?? Title::with(['genres', 'posterAsset'])->published();

        if ($type !== null) {
            $query->where('type', $type);
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($inner) use ($search) {
                SanitizeHelper::whereLike($inner, 'name', $search);
                SanitizeHelper::orWhereLike($inner, 'synopsis', $search);
            });
        }

        if ($request->filled('country')) {
            $query->where('origin_country', $request->input('country'));
        }

        if ($request->filled('year')) {
            $query->whereYear('release_date', (int) $request->input('year'));
        }

        if ($request->filled('genre') && $genre === null) {
            $query->whereHas('genres', function ($inner) use ($request) {
                $inner->where('slug', $request->input('genre'));
            });
        }

        match ($request->string('sort')->toString() ?: 'newest') {
            'oldest' => $query->oldest('published_at'),
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            'views' => $query->orderBy('views_count', 'desc'),
            default => $query->latest('published_at'),
        };

        $titles = $query->paginate(24)->withQueryString();
        $genres = Genre::withCount(['titles' => fn ($inner) => $inner->published()])
            ->orderBy('name')
            ->get();

        $pageTitle = $genre ? "Genre : {$genre->name}" : $title;
        $pageDescription = $genre
            ? "Tous les titres publies pour le genre {$genre->name}."
            : "Parcourez les {$emptyLabel}s publies dans le catalogue.";

        return view('catalog.list', compact(
            'titles',
            'genres',
            'genre',
            'pageTitle',
            'pageDescription',
            'title',
            'type',
            'emptyLabel'
        ));
    }

    private function types(): array
    {
        return [
            'movie' => 'Film',
            'series' => 'Serie',
            'classic' => 'Classique',
        ];
    }

    private function validateTitle(Request $request, ?int $ignoreId = null): array
    {
        $slugRule = Rule::unique('titles', 'slug');
        if ($ignoreId !== null) {
            $slugRule->ignore($ignoreId);
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', $slugRule],
            'type' => ['required', 'in:movie,series,classic'],
            'synopsis' => ['nullable', 'string'],
            'release_date' => ['nullable', 'date'],
            'origin_country' => ['nullable', 'string', 'max:2'],
            'original_language' => ['nullable', 'string', 'max:5'],
            'certification' => ['nullable', 'string', 'max:255'],
            'runtime_minutes' => ['nullable', 'integer', 'min:0'],
            'is_paid' => ['nullable', 'boolean'],
            'price_cents' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:draft,published,archived'],
            'published_at' => ['nullable', 'date'],
            'genres' => ['nullable', 'array'],
            'genres.*' => ['integer', 'exists:genres,id'],
        ];

        return $request->validate($rules);
    }
}
