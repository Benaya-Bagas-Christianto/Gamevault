<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Game;
use App\Models\Library;
use App\Models\Review;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function show(Game $game)
    {
        abort_if(! $game->is_active, 404);

        $game->load(['reviews.user', 'dlcs', 'galleries']);
        
        // Lazy load short_description if empty
        if (empty($game->short_description) && $game->cover_image) {
            preg_match('/apps\/(\d+)\//', $game->cover_image, $matches);
            if (isset($matches[1])) {
                $appId = $matches[1];
                $url = "https://store.steampowered.com/api/appdetails?appids={$appId}&l=indonesian";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 3);
                $response = curl_exec($ch);
                curl_close($ch);
                
                $data = json_decode($response, true);
                if ($data && isset($data[$appId]['success']) && $data[$appId]['success']) {
                    $game->short_description = $data[$appId]['data']['short_description'] ?? '';
                    $game->save();
                }
            }
        }

        $userReview    = null;
        $isInWishlist  = false;
        $isInCart      = false;
        $isOwned       = false;
        $canReview     = false;

        if (Auth::check()) {
            $user = Auth::user();
            $isInWishlist = $user->wishlistedGames()->where('games.id', $game->id)->exists();
            $isInCart     = $user->cartGames()->where('games.id', $game->id)->exists();
            $isOwned      = $user->ownedGames()->where('games.id', $game->id)->exists();
            $userReview   = $user->reviews()->where('game_id', $game->id)->first();
            $canReview    = $isOwned && ! $userReview;
        }

        $avgRating    = $game->reviews()->avg('rating') ?? 0;
        $reviewsCount = $game->reviews()->count();

        // Related games (same genre, exclude current)
        $relatedGames = Game::active()
            ->where('type', 'game')
            ->where('id', '!=', $game->id)
            ->where(function ($q) use ($game) {
                foreach ($game->genre ?? [] as $g) {
                    $q->orWhereJsonContains('genre', $g);
                }
            })
            ->inRandomOrder()
            ->take(20)
            ->get();

        return view('game.show', compact(
            'game',
            'userReview',
            'isInWishlist',
            'isInCart',
            'isOwned',
            'canReview',
            'avgRating',
            'reviewsCount',
            'relatedGames'
        ));
    }

    public function dlc(Game $game)
    {
        abort_if(! $game->is_active, 404);

        $game->load('dlcs');

        $cartGameIds = [];
        if (Auth::check()) {
            $cartGameIds = Auth::user()->cartGames()->pluck('games.id')->toArray();
        }

        return view('game.dlc', compact('game', 'cartGameIds'));
    }
}
