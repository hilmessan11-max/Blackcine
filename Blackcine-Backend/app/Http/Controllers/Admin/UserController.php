<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereDoesntHave('roles', function ($q) {
            $q->whereIn('name', ['SuperAdmin', 'Admin']);
        })->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'Utilisateur supprimé avec succès.');
    }
}
