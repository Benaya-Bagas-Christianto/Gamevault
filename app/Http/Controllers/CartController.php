<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Game;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cartGames   = $user->cartGames()->active()->get();
        $total       = $cartGames->sum('price');
        $subtotal    = $total;
        $discount    = 0;
        
        $wishlisted = $user->wishlistedGames;
        $owned = $user->ownedGames;

        $wishlistIds = $wishlisted->pluck('id')->toArray();
        $ownedIds    = $owned->pluck('id')->toArray();
        $cartIds     = $cartGames->pluck('id')->toArray();

        // Extract all genres from user's interested games
        $interestedGames = collect()->concat($cartGames)->concat($wishlisted)->concat($owned);
        $genres = [];
        foreach ($interestedGames as $game) {
            if (is_array($game->genre)) {
                $genres = array_merge($genres, $game->genre);
            }
        }
        $genres = array_unique($genres);

        // Get recommendations based on genres
        $recommendations = Game::where('is_active', true)
            ->where('type', 'game')
            ->whereNotIn('id', array_merge($ownedIds, $cartIds));
            
        if (!empty($genres)) {
            $recommendations->where(function($q) use ($genres) {
                foreach ($genres as $g) {
                    $q->orWhere('genre', 'LIKE', '%"'.$g.'"%');
                }
            });
        }
        
        $recommendations = $recommendations->inRandomOrder()->take(3)->get();

        // If not enough recommendations, fallback to random
        if ($recommendations->count() < 3) {
            $excludeIds = array_merge($ownedIds, $cartIds, $recommendations->pluck('id')->toArray());
            $fallback = Game::where('is_active', true)
                ->where('type', 'game')
                ->whereNotIn('id', $excludeIds)
                ->inRandomOrder()
                ->take(3 - $recommendations->count())
                ->get();
            $recommendations = $recommendations->concat($fallback);
        }

        $cartGroups = [];
        $processedIds = [];
        
        foreach ($cartGames as $game) {
            if (in_array($game->id, $processedIds)) continue;

            if ($game->type === 'game') {
                $dlcs = $cartGames->where('type', 'dlc')->where('parent_id', $game->id);
                if ($dlcs->isNotEmpty()) {
                    $items = collect([$game])->concat($dlcs);
                    $cartGroups[] = [
                        'is_bundle' => true,
                        'parent' => $game,
                        'items' => $items,
                        'title' => $game->title . ' Bundle',
                        'price' => $items->sum('price'),
                        'original_price' => $items->sum('original_price'),
                    ];
                    $processedIds = array_merge($processedIds, $items->pluck('id')->toArray());
                } else {
                    $cartGroups[] = [
                        'is_bundle' => false,
                        'item' => $game
                    ];
                    $processedIds[] = $game->id;
                }
            }
        }
        
        foreach ($cartGames as $game) {
            if ($game->type === 'dlc' && !in_array($game->id, $processedIds)) {
                $cartGroups[] = [
                    'is_bundle' => false,
                    'item' => $game
                ];
                $processedIds[] = $game->id;
            }
        }

        return view('cart.index', compact('cartGroups', 'total', 'subtotal', 'discount', 'wishlistIds', 'ownedIds', 'recommendations'));
    }

    public function toggle(Game $game)
    {
        $user = Auth::user();

        // Check if already owned
        if ($user->ownedGames()->where('games.id', $game->id)->exists()) {
            return response()->json(['error' => 'Game sudah ada di library kamu.'], 422);
        }

        $exists = $user->carts()->where('game_id', $game->id)->exists();

        if ($exists) {
            $user->carts()->where('game_id', $game->id)->delete();
            $inCart = false;
        } else {
            $user->carts()->create(['game_id' => $game->id]);
            $inCart = true;
        }

        $count = $user->cartBadgeCount();

        return response()->json([
            'in_cart' => $inCart,
            'count'   => $count,
            'message' => $inCart ? 'Ditambahkan ke keranjang' : 'Dihapus dari keranjang',
        ]);
    }

    public function remove(Game $game)
    {
        $user = Auth::user();
        $user->carts()->where('game_id', $game->id)->delete();
        
        $count = $user->cartBadgeCount();
        $total = $user->cartGames()->active()->sum('price');

        return response()->json([
            'success' => true,
            'in_cart' => false,
            'count'   => $count,
            'total'   => $total,
            'message' => 'Game dihapus dari keranjang',
        ]);
    }

    public function removeBundle(Game $game)
    {
        $user = Auth::user();
        
        $itemsToRemove = collect([$game])->concat($game->dlcs)->pluck('id')->toArray();
        $user->carts()->whereIn('game_id', $itemsToRemove)->delete();
        
        $count = $user->cartBadgeCount();
        $total = $user->cartGames()->active()->sum('price');

        return response()->json([
            'success' => true,
            'count'   => $count,
            'total'   => $total,
            'message' => 'Bundle dihapus dari keranjang',
        ]);
    }

    public function addBundle(Game $game)
    {
        $user = Auth::user();
        
        // Items to add: Base game + its DLCs
        $items = collect([$game])->concat($game->dlcs);
        
        $added = 0;
        foreach ($items as $item) {
            // Check if already owned
            if (!$user->ownedGames()->where('games.id', $item->id)->exists()) {
                // Check if already in cart
                if (!$user->carts()->where('game_id', $item->id)->exists()) {
                    $user->carts()->create(['game_id' => $item->id]);
                    $added++;
                }
            }
        }
        
        $count = $user->cartBadgeCount();
        
        return response()->json([
            'success' => true,
            'count'   => $count,
            'message' => "Berhasil menambahkan {$added} item ke keranjang",
        ]);
    }
}
