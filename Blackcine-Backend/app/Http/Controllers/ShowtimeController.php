<?php

namespace App\Http\Controllers;

use App\Models\Showtime;
use App\Models\Title;
use App\Models\Room;
use App\Models\Cinema;
use Illuminate\Http\Request;

class ShowtimeController extends Controller
{
    public function index(Request $request)
    {
        $query = Showtime::with(['title', 'room.cinema']);

        if ($request->has('cinema_id')) {
            $query->whereHas('room', function($q) use ($request) {
                $q->where('cinema_id', $request->cinema_id);
            });
        }

        if ($request->has('title_id')) {
            $query->where('title_id', $request->title_id);
        }

        if ($request->has('date')) {
            $query->whereDate('starts_at', $request->date);
        }

        $showtimes = $query->latest('starts_at')->paginate(20);
        $cinemas = Cinema::where('is_active', true)->get();
        $titles = Title::where('type', 'movie')->where('status', 'published')->get();

        return view('admin.boxoffice.showtimes.index', compact('showtimes', 'cinemas', 'titles'));
    }

    public function create()
    {
        $titles = Title::where('type', 'movie')->where('status', 'published')->get();
        $cinemas = Cinema::where('is_active', true)->with('rooms')->get();

        return view('admin.boxoffice.showtimes.create', compact('titles', 'cinemas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'title_id' => 'required|exists:titles,id',
            'starts_at' => 'required|date|after:now',
            'runtime_minutes' => 'nullable|integer|min:1',
            'base_price_cents' => 'required|integer|min:0',
            'currency_id' => 'nullable|exists:currencies,id',
            'status' => 'required|in:scheduled,active,cancelled,completed',
        ]);

        Showtime::create($validated);

        return redirect()->route('admin.boxoffice.showtimes.index')
            ->with('success', 'Séance créée avec succès.');
    }

    public function edit($id)
    {
        $showtime = Showtime::with(['title', 'room.cinema'])->findOrFail($id);
        $titles = Title::where('type', 'movie')->where('status', 'published')->get();
        $cinemas = Cinema::where('is_active', true)->with('rooms')->get();

        return view('admin.boxoffice.showtimes.edit', compact('showtime', 'titles', 'cinemas'));
    }

    public function update(Request $request, $id)
    {
        $showtime = Showtime::findOrFail($id);

        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'title_id' => 'required|exists:titles,id',
            'starts_at' => 'required|date',
            'runtime_minutes' => 'nullable|integer|min:1',
            'base_price_cents' => 'required|integer|min:0',
            'currency_id' => 'nullable|exists:currencies,id',
            'status' => 'required|in:scheduled,active,cancelled,completed',
        ]);

        $showtime->update($validated);

        return redirect()->route('admin.boxoffice.showtimes.index')
            ->with('success', 'Séance mise à jour avec succès.');
    }

    public function destroy($id)
    {
        $showtime = Showtime::findOrFail($id);
        $showtime->delete();

        return redirect()->route('admin.boxoffice.showtimes.index')
            ->with('success', 'Séance supprimée avec succès.');
    }
}

