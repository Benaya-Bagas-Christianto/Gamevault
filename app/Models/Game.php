<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'genre',
        'cover_image',
        'developer',
        'publisher',
        'release_date',
        'is_active',
        'type',
        'parent_id',
        'discount_percent',
        'original_price',
        'pc_requirements',
        'packages',
    ];

    protected $casts = [
        'genre' => 'array',
        'packages' => 'array',
        'release_date' => 'date',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'pc_requirements' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($game) {
            if (empty($game->slug)) {
                $slug = Str::slug($game->title);
                if (empty($slug)) {
                    $slug = 'game-' . Str::random(8);
                }
                $game->slug = $slug;
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getCoverImageUrlAttribute(): string
    {
        if ($this->cover_image) {
            if (Str::startsWith($this->cover_image, ['http://', 'https://'])) {
                return $this->cover_image;
            }
            return asset('storage/' . $this->cover_image);
        }
        return asset('images/default-game.jpg');
    }

    public function getReviewTierAttribute(): string
    {
        $reviews = $this->reviews;
        $count = $reviews->count();
        if ($count == 0) return '-';

        $avg = $reviews->avg('rating');
        if ($avg >= 4.5) return 'LUAR BIASA POSITIF';
        if ($avg >= 4.0) return 'SANGAT POSITIF';
        if ($avg >= 3.0) return 'POSITIF';
        if ($avg >= 2.0) return 'CAMPURAN';
        if ($avg >= 1.0) return 'NEGATIF';
        return 'SANGAT NEGATIF';
    }

    public function getReviewColorAttribute(): string
    {
        $count = $this->reviews->count();
        if ($count == 0) return 'var(--c-mute)';

        $avg = $this->reviews->avg('rating');
        if ($avg >= 3.0) return '#66C0F4';
        if ($avg >= 2.0) return '#B9A074';
        return '#A34C25';
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function wishlistedByUsers()
    {
        return $this->belongsToMany(User::class, 'wishlists');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function galleries()
    {
        return $this->hasMany(GameGallery::class);
    }

    public function libraries()
    {
        return $this->hasMany(Library::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->count();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->whereNotNull('cover_image');
    }

    public function scopeByGenre($query, array $genres)
    {
        if (empty($genres)) return $query;
        return $query->where(function ($q) use ($genres) {
            foreach ($genres as $genre) {
                $q->orWhereJsonContains('genre', $genre);
            }
        });
    }

    public function scopeByPrice($query, ?float $min = null, ?float $max = null)
    {
        if ($min !== null) $query->where('price', '>=', $min);
        if ($max !== null) $query->where('price', '<=', $max);
        return $query;
    }

    public function parent()
    {
        return $this->belongsTo(Game::class, 'parent_id');
    }

    public function dlcs()
    {
        return $this->hasMany(Game::class, 'parent_id')->where('type', 'dlc');
    }
}
