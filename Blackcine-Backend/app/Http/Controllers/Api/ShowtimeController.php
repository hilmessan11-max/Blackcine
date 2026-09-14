<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Showtime;
use App\Models\Cinema;
use Illuminate\Http\Request;

class ShowtimeController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'cinema_id' => 'nullable|integer|exists:cinemas,id',
            'title_id' => 'nullable|integer|exists:titles,id',
            'date' => 'nullable|date|after_or_equal:today',
            'upcoming' => 'nullable|boolean',
            'page' => 'nullable|integer|min:1',
        ]);

        $query = Showtime::with([
            'title' => fn($q) => $q->with(['genres', 'images'])->withRatingAggregates(),
            'room.cinema',
        ]);

        if ($request->filled('cinema_id')) {
            $query->whereHas('room', fn($q) => $q->where('cinema_id', $request->cinema_id));
        }

        if ($request->filled('title_id')) {
            $query->where('title_id', $request->title_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('starts_at', $request->date);
        }

        if ($request->boolean('upcoming')) {
            $query->where('starts_at', '>=', now());
        }

        $showtimes = $query->orderBy('starts_at')->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $showtimes->items(),
            'pagination' => [
                'current_page' => $showtimes->currentPage(),
                'last_page' => $showtimes->lastPage(),
                'per_page' => $showtimes->perPage(),
                'total' => $showtimes->total(),
            ],
        ]);
    }

    public function nowShowing()
    {
        $showtimes = Showtime::where('starts_at', '>=', now())
            ->with([
                'title' => fn($q) => $q->with(['genres', 'images'])->withRatingAggregates(),
                'room.cinema',
            ])
            ->orderBy('starts_at')
            ->limit(10)
            ->get()
            ->map(function ($showtime) {
                $title = $showtime->title;
                $cinema = $showtime->room?->cinema;
                $posterImage = $title?->images->first();

                return [
                    'id' => $showtime->id,
                    'title' => $title?->name ?? 'Film',
                    'title_id' => $showtime->title_id,
                    'synopsis' => $title?->synopsis ?? '',
                    'poster' => $posterImage?->path ? asset('storage/' . $posterImage->path) : null,
                    'genres' => $title?->genres->pluck('name')->toArray() ?? [],
                    'cinema' => $cinema?->name ?? '',
                    'city' => $cinema?->city ?? '',
                    'show_date' => $showtime->starts_at?->format('Y-m-d'),
                    'show_time' => $showtime->starts_at?->format('H:i'),
                    'available_seats' => $showtime->room?->capacity ?? 100,
                    'total_seats' => $showtime->room?->capacity ?? 150,
                    'price_cents' => $showtime->base_price_cents ?? 0,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $showtimes,
        ]);
    }

    public function show($id)
    {
        $showtime = Showtime::with([
            'title' => fn($q) => $q->with(['genres', 'images', 'trailers'])->withRatingAggregates(),
            'room.cinema',
        ])->find($id);

        if (!$showtime) {
            return response()->json([
                'success' => false,
                'message' => 'Séance non trouvée.'
            ], 404);
        }

        $title = $showtime->title;
        $cinema = $showtime->room?->cinema;
        $posterImage = $title?->images->first();

        return response()->json([
            'success' => true,
            'id' => $showtime->id,
            'title' => $title?->name ?? 'Film',
            'title_id' => $showtime->title_id,
            'synopsis' => $title?->synopsis ?? '',
            'poster' => $posterImage?->path ? asset('storage/' . $posterImage->path) : null,
            'genres' => $title?->genres->pluck('name')->toArray() ?? [],
            'cinema' => $cinema?->name ?? '',
            'city' => $cinema?->city ?? '',
            'show_date' => $showtime->starts_at?->format('Y-m-d'),
            'show_time' => $showtime->starts_at?->format('H:i'),
            'available_seats' => $showtime->room?->capacity ?? 100,
            'total_seats' => $showtime->room?->capacity ?? 100,
            'price_cents' => $showtime->base_price_cents ?? 0,
            'base_price_cents' => $showtime->base_price_cents ?? 0,
            'runtime_minutes' => $showtime->runtime_minutes ?? $title?->runtime_minutes ?? 0,
            'status' => $showtime->status,
            'data' => [
                'id' => $showtime->id,
                'title' => $title?->name ?? 'Film',
                'title_id' => $showtime->title_id,
                'synopsis' => $title?->synopsis ?? '',
                'poster' => $posterImage?->path ? asset('storage/' . $posterImage->path) : null,
                'genres' => $title?->genres->pluck('name')->toArray() ?? [],
                'cinema' => [
                    'id' => $cinema?->id,
                    'name' => $cinema?->name ?? '',
                    'city' => $cinema?->city ?? '',
                    'address' => $cinema?->address_line1 ?? '',
                ],
                'room' => [
                    'id' => $showtime->room?->id,
                    'name' => $showtime->room?->name ?? '',
                    'capacity' => $showtime->room?->capacity ?? 0,
                    'screen_type' => $showtime->room?->screen_type ?? '',
                ],
                'show_date' => $showtime->starts_at?->format('Y-m-d'),
                'show_time' => $showtime->starts_at?->format('H:i'),
                'runtime_minutes' => $showtime->runtime_minutes ?? $title?->runtime_minutes ?? 0,
                'base_price_cents' => $showtime->base_price_cents ?? 0,
                'status' => $showtime->status,
            ],
        ]);
    }

    public function cinemas()
    {
        $cinemas = Cinema::where('is_active', true)
            ->with('rooms')
            ->get()
            ->map(fn($cinema) => [
                'id' => $cinema->id,
                'name' => $cinema->name,
                'city' => $cinema->city ?? '',
                'country' => $cinema->country ?? '',
                'rooms' => $cinema->rooms->map(fn($room) => [
                    'id' => $room->id,
                    'name' => $room->name,
                    'capacity' => $room->capacity,
                    'screen_type' => $room->screen_type ?? '',
                ]),
            ]);

        return response()->json([
            'success' => true,
            'data' => $cinemas,
        ]);
    }
}
