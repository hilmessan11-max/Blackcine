<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Slide;
use App\Models\Title;
use App\Models\Video;
use App\Models\Showtime;
use App\Models\Selection;
use App\Models\Festival;
use App\Models\Casting;
use App\Models\Project;
use App\Models\Contest;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $data = $this->rememberHome('home_page_data', 600, function () {
            return [
                'featured_articles' => Article::where('status', 'published')
                    ->latest('published_at')
                    ->limit(5)
                    ->get()
                    ->map(function ($article) {
                        return [
                            'id' => $article->id,
                            'title' => $article->title,
                            'excerpt' => $article->excerpt,
                            'slug' => $article->slug,
                            'author' => 'Rédaction BlackCiné',
                            'published_at' => $article->published_at?->format('Y-m-d'),
                            'views_count' => $article->views_count ?? $article->views ?? 0,
                        ];
                    }),

                'featured_videos' => Video::where('status', 'published')
                    ->latest('published_at')
                    ->limit(6)
                    ->get()
                    ->map(function ($video) {
                        return [
                            'id' => $video->id,
                            'title' => $video->title,
                            'slug' => $video->slug,
                            'source_type' => $video->source_type ?? 'youtube',
                            'source_url' => $video->source_url,
                            'thumbnail' => $video->thumbnail?->path ? asset('storage/' . $video->thumbnail->path) : null,
                            'views_count' => $video->views_count ?? 0,
                            'duration' => $video->duration_seconds ?? 0,
                        ];
                    }),

                'top_films' => Title::where('type', 'movie')
                    ->where('status', 'published')
                    ->with(['genres', 'images'])
                    ->withRatingAggregates()
                    ->orderBy('views_count', 'desc')
                    ->limit(10)
                    ->get()
                    ->map(fn($title) => $this->formatTitle($title)),

                'top_series' => Title::where('type', 'series')
                    ->where('status', 'published')
                    ->with(['genres', 'images'])
                    ->withRatingAggregates()
                    ->orderBy('views_count', 'desc')
                    ->limit(10)
                    ->get()
                    ->map(fn($title) => $this->formatTitle($title)),

                'now_showing' => Showtime::where('starts_at', '>=', now())
                    ->with([
                        'title' => fn($q) => $q->with(['genres', 'images'])->withRatingAggregates(),
                        'room.cinema',
                    ])
                    ->orderBy('starts_at')
                    ->limit(8)
                    ->get()
                    ->map(function ($showtime) {
                        $title = $showtime->title;
                        $cinema = $showtime->room?->cinema;
                        $poster = $title?->images->first()?->path ? asset('storage/' . $title->images->first()->path) : null;

                        return [
                            'id' => $showtime->id,
                            'title' => $title?->name ?? 'Film à l\'affiche',
                            'title_id' => $showtime->title_id,
                            'synopsis' => $title?->synopsis ?? '',
                            'poster' => $poster,
                            'genres' => $title?->genres->pluck('name')->toArray() ?? [],
                            'cinema' => $cinema?->name ?? 'Cinéma partenaire',
                            'city' => $cinema?->city ?? 'Ville',
                            'show_date' => $showtime->starts_at?->format('Y-m-d'),
                            'show_time' => $showtime->starts_at?->format('H:i'),
                            'available_seats' => $showtime->room?->capacity ?? 100,
                            'total_seats' => $showtime->room?->capacity ?? 150,
                            'price_cents' => $showtime->base_price_cents ?? 0,
                        ];
                    }),

                'editorial_selection' => Selection::where('is_active', true)
                    ->orderBy('display_order')
                    ->limit(6)
                    ->get()
                    ->map(function ($selection) {
                        return [
                            'id' => $selection->id,
                            'title' => $selection->title,
                            'slug' => $selection->slug,
                            'description' => $selection->description,
                            'type' => $selection->type,
                        ];
                    }),

                'slides' => Slide::where('is_active', true)
                    ->orderBy('display_order')
                    ->limit(5)
                    ->get()
                    ->map(function ($slide) {
                        return [
                            'id' => $slide->id,
                            'title' => $slide->title,
                            'subtitle' => $slide->subtitle ?? '',
                            'link' => $slide->link ?? '#',
                            'image' => $slide->image_path ? asset('storage/' . $slide->image_path) : null,
                        ];
                    }),

                'active_festival' => $this->getActiveFestival(),

                'active_castings' => Casting::where('status', 'open')
                    ->where('is_active', true)
                    ->latest()
                    ->limit(3)
                    ->get()
                    ->map(function ($casting) {
                        return [
                            'id' => $casting->id,
                            'title' => $casting->title,
                            'slug' => $casting->slug,
                            'description' => $casting->description,
                            'role' => $casting->role ?? '',
                            'end_date' => $casting->end_date?->format('Y-m-d'),
                            'country' => $casting->country ?? '',
                            'city' => $casting->city ?? '',
                        ];
                    }),

                'active_projects' => Project::where('status', 'development')
                    ->where('is_active', true)
                    ->latest()
                    ->limit(3)
                    ->get()
                    ->map(function ($project) {
                        return [
                            'id' => $project->id,
                            'title' => $project->title,
                            'slug' => $project->slug,
                            'description' => $project->description,
                            'project_type' => $project->project_type ?? '',
                            'end_date' => $project->end_date?->format('Y-m-d'),
                            'country' => $project->country ?? '',
                            'city' => $project->city ?? '',
                        ];
                    }),

                'active_contests' => Contest::where('status', 'open')
                    ->where('is_active', true)
                    ->latest()
                    ->limit(3)
                    ->get()
                    ->map(function ($contest) {
                        return [
                            'id' => $contest->id,
                            'title' => $contest->title,
                            'slug' => $contest->slug,
                            'description' => $contest->description,
                            'contest_type' => $contest->contest_type ?? '',
                            'registration_deadline' => $contest->registration_deadline?->format('Y-m-d'),
                        ];
                    }),

                'classics' => Title::where('type', 'classic')
                    ->where('status', 'published')
                    ->with(['genres', 'images'])
                    ->withRatingAggregates()
                    ->latest('release_date')
                    ->limit(6)
                    ->get()
                    ->map(fn($title) => $this->formatTitle($title)),

                'partners' => Partner::where('is_active', true)
                    ->limit(8)
                    ->get()
                    ->map(function ($partner) {
                        return [
                            'id' => $partner->id,
                            'name' => $partner->name,
                            'slug' => $partner->slug,
                            'description' => $partner->description ?? '',
                            'website_url' => $partner->website_url ?? '',
                            'logo' => $partner->logo_path ? asset('storage/' . $partner->logo_path) : null,
                            'partner_type' => $partner->partner_type ?? '',
                        ];
                    }),
            ];
        });

        return response()->json(['data' => $data]);
    }

    private function formatTitle($title)
    {
        $posterImage = $title->images->first();
        $poster = $posterImage?->path ? asset('storage/' . $posterImage->path) : null;

        return [
            'id' => $title->id,
            'name' => $title->name,
            'title' => $title->name,
            'slug' => $title->slug,
            'type' => $title->type,
            'synopsis' => $title->synopsis,
            'overview' => $title->synopsis,
            'release_date' => $title->release_date?->format('Y-m-d'),
            'year' => $title->release_date?->year,
            'runtime_minutes' => $title->runtime_minutes,
            'origin_country' => $title->origin_country ?? '',
            'country' => $title->origin_country ?? '',
            'original_language' => $title->original_language ?? '',
            'poster' => $poster,
            'genres' => $title->genres->pluck('name')->toArray(),
            'views_count' => $title->views_count ?? 0,
            'is_paid' => $title->is_paid ?? false,
            'price_cents' => $title->price_cents ?? 0,
            'average_rating' => $title->average_rating,
            'total_reviews' => $title->total_reviews,
        ];
    }

    private function getActiveFestival()
    {
        $festival = Festival::where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->first();

        if (!$festival) {
            return null;
        }

        return [
            'id' => $festival->id,
            'name' => $festival->name,
            'slug' => $festival->slug,
            'description' => $festival->description ?? '',
            'starts_at' => $festival->starts_at?->format('Y-m-d'),
            'ends_at' => $festival->ends_at?->format('Y-m-d'),
            'country' => $festival->country ?? '',
            'city' => $festival->city ?? '',
        ];
    }

    private function rememberHome(string $key, int $ttl, \Closure $callback)
    {
        try {
            $store = Cache::getStore();
            if (method_exists($store, 'tags') || Cache::supportsTags()) {
                return Cache::tags(['home'])->remember($key, $ttl, $callback);
            }
        } catch (\Throwable $e) {
            // Fallback si tags non supportés (database, array)
        }
        return Cache::remember($key, $ttl, $callback);
    }

    public static function clearHomeCache(): void
    {
        try {
            if (Cache::supportsTags()) {
                Cache::tags(['home'])->flush();
            }
        } catch (\Throwable $e) {}
        Cache::forget('home_page_data');
        Cache::forget('footer_partners');
        Cache::forget('footer_stats');
    }
}
