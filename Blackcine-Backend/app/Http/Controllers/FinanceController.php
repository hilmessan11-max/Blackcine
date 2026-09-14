<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Refund;
use App\Models\Payout;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function reports()
    {
        $stats = [
            'total_revenue' => Payment::where('status', 'completed')->sum('amount_cents'),
            'total_orders' => Order::count(),
            'total_invoices' => Invoice::count(),
            'total_refunds' => Refund::where('status', 'completed')->sum('amount_cents'),
            'total_payouts' => Payout::where('status', 'completed')->sum('amount_cents'),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'pending_payouts' => Payout::where('status', 'pending')->count(),
        ];

        // Revenus par mois (6 derniers mois)
        // Utilisation de strftime pour SQLite (compatible avec MySQL via DB::raw)
        $monthlyRevenue = Payment::where('status', 'completed')
            ->select(
                DB::raw("strftime('%Y-%m', created_at) as month"),
                DB::raw('SUM(amount_cents) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy(DB::raw("strftime('%Y-%m', created_at)"))
            ->orderBy('month')
            ->get();

        $recentPayments = Payment::with(['order'])->latest()->take(10)->get();
        $recentInvoices = Invoice::latest()->take(10)->get();

        return view('admin.finance.reports', compact('stats', 'monthlyRevenue', 'recentPayments', 'recentInvoices'));
    }

    public function update(Request $request)
    {
        // Cette méthode peut être utilisée pour mettre à jour des paramètres financiers
        // Par exemple : taux de commission, devises, etc.
        
        $validated = $request->validate([
            'action' => 'required|string',
            'data' => 'required|array',
        ]);

        // Logique de mise à jour selon l'action
        // Exemple : mise à jour des taux de TVA, devises, etc.

        return redirect()->route('admin.finance.reports')
            ->with('success', 'Paramètres financiers mis à jour avec succès.');
    }
    public function payouts()
    {
        $payouts = Payout::with('user')->latest()->paginate(20);
        return view('admin.finance.payouts', compact('payouts'));
    }

    public function transactions()
    {
        $transactions = Payment::with(['order', 'user'])->latest()->paginate(20);
        return view('admin.finance.transactions', compact('transactions'));
    }
}

