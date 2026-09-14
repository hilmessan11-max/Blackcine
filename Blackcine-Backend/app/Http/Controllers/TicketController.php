<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Order;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with(['order', 'showtime'])
            ->latest()
            ->paginate(20);

        $stats = [
            'total' => Ticket::count(),
            'pending' => Ticket::where('status', 'pending')->count(),
            'confirmed' => Ticket::where('status', 'confirmed')->count(),
            'cancelled' => Ticket::where('status', 'cancelled')->count(),
        ];

        return view('admin.tickets.index', compact('tickets', 'stats'));
    }

    public function create()
    {
        $showtimes = \App\Models\Showtime::with(['movie', 'cinema'])->where('start_time', '>', now())->get();
        return view('admin.tickets.create', compact('showtimes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'showtime_id' => 'required|exists:showtimes,id',
            'holder_name' => 'required|string|max:255',
            'seat_label' => 'required|string|max:50',
            'price_cents' => 'required|integer|min:0',
            'status' => 'required|in:pending,confirmed',
        ]);

        // Create a dummy order for this ticket since it's manually added
        $order = Order::create([
            'user_id' => auth()->id(),
            'subtotal_cents' => $validated['price_cents'],
            'tax_cents' => 0,
            'total_cents' => $validated['price_cents'],
            'currency_id' => 1,
            'status' => 'completed',
        ]);

        $ticket = Ticket::create([
            'order_id' => $order->id,
            'showtime_id' => $validated['showtime_id'],
            'holder_name' => $validated['holder_name'],
            'seat_label' => $validated['seat_label'],
            'price_cents' => $validated['price_cents'],
            'currency_id' => 1, // Default currency ID if needed, or remove if not in fillable
            'status' => $validated['status'],
            'qr_code' => \Illuminate\Support\Str::random(32),
        ]);

        return redirect()->route('admin.tickets.index')
            ->with('success', 'Ticket créé avec succès.');
    }

    public function resolve(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,refunded',
            'notes' => 'nullable|string|max:1000',
        ]);

        $ticket->update($validated);

        return redirect()->route('admin.tickets.index')
            ->with('success', 'Ticket résolu avec succès.');
    }
}

