<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Game;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Hero Carousel Games (Biggest discounts first, fallback to most reviewed)
        $heroGames = Game::active()->where('type', 'game')
            ->withCount('reviews')
            ->with('galleries')
            ->orderByDesc('discount_percent')
            ->orderByDesc('reviews_count')
            ->take(5)
            ->get();

        // Trending Games (Dynamic daily: based on today's seed, prioritizes discounts)
        $seed = \Carbon\Carbon::now()->format('Ymd');
        $trendingGames = Game::active()->where('type', 'game')
            ->orderByDesc('discount_percent')
            ->inRandomOrder($seed)
            ->take(12)
            ->get();

        // Popular Games (All-time most reviewed/popular)
        $popularGames = Game::active()->where('type', 'game')
            ->withCount('reviews')
            ->orderByDesc('reviews_count')
            ->take(12)
            ->get();

        // Discounted Games (Special Offers)
        $discountGames = Game::active()->where('type', 'game')
            ->where('discount_percent', '>', 0)
            ->orderByDesc('discount_percent')
            ->take(12)
            ->get();

        // Free Games
        $freeGames = Game::active()->where('type', 'game')
            ->where('price', 0)
            ->latest()
            ->take(12)
            ->get();

        // User data
        $wishlistIds = Auth::check()
            ? Auth::user()->wishlistedGames()->pluck('games.id')->toArray()
            : [];
        $cartIds = Auth::check()
            ? Auth::user()->cartGames()->pluck('games.id')->toArray()
            : [];
        $ownedIds = Auth::check()
            ? Auth::user()->ownedGames()->pluck('games.id')->toArray()
            : [];

        return view('home.index', compact(
            'heroGames',
            'trendingGames',
            'popularGames',
            'discountGames',
            'freeGames',
            'wishlistIds',
            'cartIds',
            'ownedIds'
        ));
    }

    public function store(Request $request)
    {
        $query = Game::active()->where('type', 'game');

        // Search
        if ($search = $request->get('search')) {
            $matchedIds = $this->getFuzzyMatchingIds($search);
            $query->where(function ($q) use ($matchedIds, $search) {
                $q->whereIn('id', $matchedIds)
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Genre filter
        if ($genres = $request->get('genres', [])) {
            $query->byGenre($genres);
        }

        // Price filter
        $priceRanges = $request->get('price', []);
        if (!empty($priceRanges)) {
            $query->where(function ($q) use ($priceRanges) {
                foreach ($priceRanges as $range) {
                    match ($range) {
                        'free'   => $q->orWhere('price', 0),
                        'under50' => $q->orWhereBetween('price', [1, 50000]),
                        'under100' => $q->orWhereBetween('price', [50001, 100000]),
                        'under200' => $q->orWhereBetween('price', [100001, 200000]),
                        'above200' => $q->orWhere('price', '>', 200000),
                        default  => null,
                    };
                }
            });
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($sort === 'title') {
            $query->orderBy('title', 'asc');
        } else {
            // Default newest
            $query->orderBy('release_date', 'desc')->latest();
        }

        // Detect mobile for specific pagination limit
        $isMobile = false;
        if ($userAgent = $request->header('User-Agent')) {
            $isMobile = preg_match('/Mobile|Android|BlackBerry|iPhone|iPad|iPod|Opera Mini|IEMobile/i', $userAgent);
        }
        $perPage = $isMobile ? 10 : 15;

        $games = $query->paginate($perPage)->onEachSide($isMobile ? 1 : 3)->withQueryString();

        $allGenres = Game::active()->pluck('genre')->flatten()->unique()->sort()->values();

        $wishlistIds = Auth::check()
            ? Auth::user()->wishlistedGames()->pluck('games.id')->toArray()
            : [];
        $cartIds = Auth::check()
            ? Auth::user()->cartGames()->pluck('games.id')->toArray()
            : [];
        $ownedIds = Auth::check()
            ? Auth::user()->ownedGames()->pluck('games.id')->toArray()
            : [];

        return view('home.store', compact('games', 'allGenres', 'wishlistIds', 'cartIds', 'ownedIds'));
    }

    public function categories(Request $request)
    {
        $query = Game::active()->where('type', 'game')->latest();

        // Search
        if ($search = $request->get('search')) {
            $matchedIds = $this->getFuzzyMatchingIds($search);
            $query->where(function ($q) use ($matchedIds, $search) {
                $q->whereIn('id', $matchedIds)
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Genre filter (array of genres)
        $genreDipilih = $request->get('genre', []);
        if (!empty($genreDipilih)) {
            $query->byGenre($genreDipilih);
        }

        // Price filter (array of price keys)
        $hargaDipilih = $request->get('harga', []);
        if (!empty($hargaDipilih)) {
            $query->where(function ($q) use ($hargaDipilih) {
                foreach ($hargaDipilih as $range) {
                    match ($range) {
                        'gratis'   => $q->orWhere('price', 0),
                        '<100' => $q->orWhereBetween('price', [1, 100000]),
                        '100-250' => $q->orWhereBetween('price', [100001, 250000]),
                        '250-500' => $q->orWhereBetween('price', [250001, 500000]),
                        '>500' => $q->orWhere('price', '>', 500000),
                        default  => null,
                    };
                }
            });
        }

        // Detect mobile for specific pagination limit
        $isMobile = false;
        if ($userAgent = $request->header('User-Agent')) {
            $isMobile = preg_match('/Mobile|Android|BlackBerry|iPhone|iPad|iPod|Opera Mini|IEMobile/i', $userAgent);
        }
        $perPage = $isMobile ? 10 : 25;

        $games = $query->paginate($perPage)->onEachSide($isMobile ? 1 : 3)->withQueryString();

        $allGenres = Game::active()->pluck('genre')->flatten()->unique()->sort()->values();

        $wishlistIds = Auth::check()
            ? Auth::user()->wishlistedGames()->pluck('games.id')->toArray()
            : [];
        $cartIds = Auth::check()
            ? Auth::user()->cartGames()->pluck('games.id')->toArray()
            : [];
        $ownedIds = Auth::check()
            ? Auth::user()->ownedGames()->pluck('games.id')->toArray()
            : [];

        // Determine if request is AJAX
        if ($request->ajax()) {
            return view('home.partials.category_grid', compact('games', 'wishlistIds', 'cartIds', 'ownedIds'))->render();
        }

        return view('home.categories', compact('games', 'allGenres', 'genreDipilih', 'hargaDipilih', 'wishlistIds', 'cartIds', 'ownedIds'));
    }

    public function searchSuggestions(Request $request)
    {
        $query = $request->input('q');
        
        $ownedIds = Auth::check() ? Auth::user()->ownedGames()->pluck('games.id')->toArray() : [];

        if (empty($query)) {
            $gamesData = Game::active()->where('type', 'game')
                ->withCount('reviews')
                ->orderByDesc('reviews_count')
                ->take(5)
                ->get();
            
            // Just some popular genres for "Browse by" or "Top genres"
            $genres = ['Action', 'RPG', 'Adventure', 'Indie'];
        } else {
            $matchedIds = $this->getFuzzyMatchingIds($query);
            $gamesData = Game::active()->where('type', 'game')
                ->whereIn('id', $matchedIds)
                ->withCount('reviews')
                ->orderByDesc('reviews_count')
                ->take(5)
                ->get();
                
            $genres = ['Action', 'RPG', 'Adventure', 'Indie'];
        }

        $mappedGames = $gamesData->map(function ($game) use ($ownedIds) {
            return [
                'title' => $game->title,
                'slug' => $game->slug,
                'cover_image_url' => $game->cover_image_url,
                'price' => $game->price,
                'original_price' => $game->original_price,
                'discount_percent' => $game->discount_percent,
                'is_free' => $game->price == 0,
                'formatted_price' => number_format($game->price, 0, ',', '.'),
                'formatted_original' => number_format($game->original_price, 0, ',', '.'),
                'is_owned' => in_array($game->id, $ownedIds)
            ];
        });

        return response()->json([
            'games' => $mappedGames,
            'genres' => $genres
        ]);
    }

    public function creator($name)
    {
        $games = Game::active()
            ->where('type', 'game')
            ->where(function($q) use ($name) {
                $q->where('developer', $name)
                  ->orWhere('publisher', $name);
            })
            ->orderBy('release_date', 'desc')
            ->get();

        $wishlistIds = Auth::check()
            ? Auth::user()->wishlistedGames()->pluck('games.id')->toArray()
            : [];
        $cartIds = Auth::check()
            ? Auth::user()->cartGames()->pluck('games.id')->toArray()
            : [];
        $ownedIds = Auth::check()
            ? Auth::user()->ownedGames()->pluck('games.id')->toArray()
            : [];

        // Determine if they are primarily a developer or publisher (just for display)
        $isPublisher = $games->where('publisher', $name)->count() > 0;
        
        $featuredGames = $games->take(2);
        $otherGames = $games->skip(2);

        return view('home.creator', compact('name', 'games', 'featuredGames', 'otherGames', 'wishlistIds', 'cartIds', 'ownedIds', 'isPublisher'));
    }

    private function getFuzzyMatchingIds($search)
    {
        $searchNorm = strtolower(preg_replace('/[^a-z0-9]/i', '', $search));
        $searchWords = array_filter(explode(' ', strtolower(preg_replace('/[^a-z0-9\s]/i', '', $search))));
        
        $games = Game::active()->select('id', 'title', 'developer')->get();
        
        $matchedIds = [];
        foreach ($games as $game) {
            $titleNorm = strtolower(preg_replace('/[^a-z0-9]/i', '', $game->title));
            $devNorm = strtolower(preg_replace('/[^a-z0-9]/i', '', $game->developer ?? ''));
            
            // 1. Direct normalized match (ignores spaces and symbols)
            if (str_contains($titleNorm, $searchNorm) || str_contains($devNorm, $searchNorm)) {
                $matchedIds[] = $game->id;
                continue;
            }
            
            // 2. Word by word typo tolerance (Levenshtein)
            $titleWords = array_filter(explode(' ', strtolower(preg_replace('/[^a-z0-9\s]/i', '', $game->title))));
            
            $allWordsMatched = true;
            foreach ($searchWords as $sWord) {
                $wordMatched = false;
                foreach ($titleWords as $tWord) {
                    if (str_contains($tWord, $sWord) || str_contains($sWord, $tWord)) {
                        $wordMatched = true;
                        break;
                    }
                    $maxTypos = strlen($sWord) <= 3 ? 0 : (strlen($sWord) <= 5 ? 1 : 2);
                    if (levenshtein($sWord, $tWord) <= $maxTypos) {
                        $wordMatched = true;
                        break;
                    }
                }
                if (!$wordMatched) {
                    $allWordsMatched = false;
                    break;
                }
            }
            
            if ($allWordsMatched && count($searchWords) > 0) {
                $matchedIds[] = $game->id;
            }
        }
        
        return $matchedIds;
    }
}
