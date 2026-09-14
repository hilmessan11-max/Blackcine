<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::with(['user', 'assignedTo'])->latest()->paginate(20);
        
        $stats = [
            'total' => SupportTicket::count(),
            'open' => SupportTicket::where('status', 'open')->count(),
            'in_progress' => SupportTicket::where('status', 'in_progress')->count(),
            'resolved' => SupportTicket::where('status', 'resolved')->count(),
        ];

        return view('admin.support.tickets.index', compact('tickets', 'stats'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('admin.support.tickets.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:technical,billing,account,content,other',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        SupportTicket::create($validated);

        return redirect()->route('admin.support.tickets.index')
            ->with('success', 'Ticket créé avec succès.');
    }

    public function show(SupportTicket $ticket)
    {
        $ticket->load(['user', 'assignedTo']);
        $admins = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['Admin', 'SuperAdmin']);
        })->get();
        
        return view('admin.support.tickets.show', compact('ticket', 'admins'));
    }

    public function update(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,waiting_user,resolved,closed',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
            'resolution_note' => 'nullable|string',
        ]);

        if ($validated['status'] === 'resolved' && $ticket->status !== 'resolved') {
            $validated['resolved_at'] = now();
        }

        $ticket->update($validated);

        return redirect()->route('admin.support.tickets.show', $ticket)
            ->with('success', 'Ticket mis à jour.');
    }

    public function destroy(SupportTicket $ticket)
    {
        $ticket->delete();
        return redirect()->route('admin.support.tickets.index')
            ->with('success', 'Ticket supprimé.');
    }
}
