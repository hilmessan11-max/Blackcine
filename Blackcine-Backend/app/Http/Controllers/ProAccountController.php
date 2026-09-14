<?php

namespace App\Http\Controllers;

use App\Helpers\SanitizeHelper;
use App\Models\ProAccount;
use App\Models\Asset;
use Illuminate\Http\Request;

class ProAccountController extends Controller
{
    public function index(Request $request)
    {
        $query = ProAccount::with(['user', 'idDocument', 'businessDocument']);

        if ($request->has('verification_status')) {
            $query->where('verification_status', $request->verification_status);
        }

        if ($request->has('kyc_status')) {
            $query->where('kyc_status', $request->kyc_status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                SanitizeHelper::whereLike($q, 'company_name', $search);
                SanitizeHelper::orWhereLike($q, 'email', $search);
            });
        }

        $accounts = $query->latest()->paginate(20);

        return view('admin.users.pro-accounts.index', compact('accounts'));
    }

    public function show($id)
    {
        $account = ProAccount::with(['user', 'idDocument', 'businessDocument'])->findOrFail($id);

        return view('admin.users.pro-accounts.show', compact('account'));
    }

    public function verify($id)
    {
        $account = ProAccount::findOrFail($id);
        $account->update([
            'verification_status' => 'verified',
            'verified_at' => now(),
            'kyc_status' => 'approved',
        ]);

        return redirect()->back()
            ->with('success', 'Compte Pro vérifié avec succès.');
    }

    public function reject(Request $request, $id)
    {
        $account = ProAccount::findOrFail($id);

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $account->update([
            'verification_status' => 'rejected',
            'kyc_status' => 'rejected',
        ]);

        return redirect()->back()
            ->with('success', 'Compte Pro rejeté.');
    }
}

