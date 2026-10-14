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
        $isBundleInCart = false;
        $isOwned       = false;
        $canReview     = false;

        if (Auth::check()) {
            $user = Auth::user();
            $isInWishlist = $user->wishlistedGames()->where('games.id', $game->id)->exists();
            
            $cartIds = $user->cartGames()->pluck('games.id')->toArray();
            $isInCart = in_array($game->id, $cartIds);
            
            // Check if bundle is in cart
            if ($game->dlcs && $game->dlcs->count() > 0) {
                $isBundleInCart = $isInCart;
                if ($isBundleInCart) {
                    foreach ($game->dlcs as $dlc) {
                        if (!in_array($dlc->id, $cartIds)) {
                            $isBundleInCart = false;
                            break;
                        }
                    }
                }
            }
            
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
            'isBundleInCart',
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

    public function bundle(Game $game)
    {
        abort_if(! $game->is_active, 404);

        $game->load(['dlcs' => function ($q) {
            $q->where('is_active', true);
        }]);

        // If no DLCs, redirect to game show
        if (!$game->dlcs || $game->dlcs->count() === 0) {
            return redirect()->route('game.show', $game->slug);
        }

        $cartGameIds = [];
        $isBundleInCart = false;
        $isOwned = false;

        if (Auth::check()) {
            $user = Auth::user();
            $cartGameIds = $user->cartGames()->pluck('games.id')->toArray();
            $isInCart = in_array($game->id, $cartGameIds);

            $isBundleInCart = $isInCart;
            if ($isBundleInCart) {
                foreach ($game->dlcs as $dlc) {
                    if (!in_array($dlc->id, $cartGameIds)) {
                        $isBundleInCart = false;
                        break;
                    }
                }
            }

            $isOwned = $user->ownedGames()->where('games.id', $game->id)->exists();
        }

        // Pricing calculations
        $totalOriginalPrice = $game->price + $game->dlcs->sum('price');
        $bundleDiscountPercent = 15;
        $bundleDiscountAmount = round($totalOriginalPrice * ($bundleDiscountPercent / 100));
        $bundlePrice = $totalOriginalPrice - $bundleDiscountAmount;

        // Bundle information logic
        $bundleDescription = null;
        if (!empty($game->packages) && is_array($game->packages)) {
            foreach ($game->packages as $pkg) {
                if (isset($pkg['name']) && (stripos($pkg['name'], 'bundle') !== false || stripos($pkg['name'], 'edition') !== false || stripos($pkg['name'], 'complete') !== false)) {
                    $bundleDescription = "Paket resmi " . $pkg['name'] . " untuk " . $game->title . " mencakup game dasar dan konten tambahan resmi pilihan.";
                    break;
                }
            }
        }

        if (!$bundleDescription) {
            $dlcCount = $game->dlcs->count();
            $bundleDescription = "Paket Bundle Lengkap {$game->title} (Complete Collection) dirancang khusus bagi Anda yang ingin menikmati petualangan dan seluruh konten game ini secara menyeluruh. Paket ini mencakup game dasar beserta {$dlcCount} konten tambahan (DLC) resmi, termasuk seluruh ekspansi cerita, item kosmetik eksklusif, serta seluruh fitur tambahan digital. Dengan memilih paket bundle ini, Anda mendapatkan potongan harga istimewa sebesar {$bundleDiscountPercent}% dibandingkan membeli setiap item secara terpisah.";
        }

        return view('game.bundle', compact(
            'game',
            'cartGameIds',
            'isBundleInCart',
            'isOwned',
            'totalOriginalPrice',
            'bundleDiscountPercent',
            'bundleDiscountAmount',
            'bundlePrice',
            'bundleDescription'
        ));
    }
}
