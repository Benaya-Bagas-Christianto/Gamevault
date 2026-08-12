<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'user')->withCount(['transactions', 'libraries'])->latest();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(20)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $transactions = $user->transactions()->with('details.game')->latest()->get();
        $cartItems    = $user->cartGames()->get();
        $libraryItems = $user->libraries()->with('game')->get();

        return view('admin.users.show', compact('user', 'transactions', 'cartItems', 'libraryItems'));
    }
}
