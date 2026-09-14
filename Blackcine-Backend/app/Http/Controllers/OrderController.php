<?php

namespace App\Http\Controllers;

use App\Helpers\SanitizeHelper;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'payment', 'invoice', 'tickets']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                SanitizeHelper::whereLike($q, 'email', $search);
                SanitizeHelper::orWhereLike($q, 'id', $search);
            });
        }

        $orders = $query->latest()->paginate(20);
        $statuses = ['pending', 'paid', 'cancelled', 'refunded'];

        return view('admin.boxoffice.orders.index', compact('orders', 'statuses'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'payment', 'invoice', 'tickets.showtime.title', 'tickets.showtime.room.cinema'])->findOrFail($id);

        return view('admin.boxoffice.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,paid,cancelled,refunded',
        ]);

        $order->update($validated);

        return redirect()->route('admin.boxoffice.orders.show', $order->id)
            ->with('success', 'Statut de la commande mis à jour.');
    }

    public function export(Request $request)
    {
        $query = Order::with(['user', 'payment']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->get();

        $filename = 'commandes_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['ID', 'Email', 'Statut', 'Total', 'Date']);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->email,
                    $order->status,
                    number_format($order->total_cents / 100, 2) . ' €',
                    $order->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

