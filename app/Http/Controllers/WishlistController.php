<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Game;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistItems = Auth::user()->wishlistedGames()->with('reviews')->active()->get();
        $cartIds = Auth::user()->cartGames()->pluck('games.id')->toArray();
        return view('wishlist.index', compact('wishlistItems', 'cartIds'));
    }

    public function toggle(Game $game)
    {
        $user = Auth::user();

        // Check if already owned
        if ($user->ownedGames()->where('games.id', $game->id)->exists()) {
            return response()->json(['error' => 'Game sudah ada di library kamu.'], 422);
        }

        $exists = $user->wishlists()->where('game_id', $game->id)->exists();

        if ($exists) {
            $user->wishlists()->where('game_id', $game->id)->delete();
            $inWishlist = false;
        } else {
            $user->wishlists()->create(['game_id' => $game->id]);
            $inWishlist = true;
        }

        $count = $user->wishlists()->count();

        return response()->json([
            'success'     => true,
            'in_wishlist' => $inWishlist,
            'count'       => $count,
            'message'     => $inWishlist ? 'Ditambahkan ke wishlist' : 'Dihapus dari wishlist',
        ]);
    }
}
