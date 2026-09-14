<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cinema;
use App\Models\Order;
use App\Models\Showtime;
use App\Models\Ticket;

class BoxOfficeController extends Controller
{
    public function index()
    {
        $stats = [
            'showtimes' => Showtime::count(),
            'upcoming_showtimes' => Showtime::upcoming()->count(),
            'cinemas' => Cinema::count(),
            'active_cinemas' => Cinema::where('is_active', true)->count(),
            'orders' => Order::count(),
            'tickets' => Ticket::count(),
            'revenue_cents' => Order::where('status', 'paid')->sum('total_cents'),
        ];

        $recentShowtimes = Showtime::with(['title', 'room.cinema'])
            ->latest('starts_at')
            ->take(5)
            ->get();

        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.boxoffice.index', compact('stats', 'recentShowtimes', 'recentOrders'));
    }
}
