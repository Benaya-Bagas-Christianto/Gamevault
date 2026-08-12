<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'avatar',
        'pin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return strtolower($this->role) === 'admin';
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function wishlistedGames()
    {
        return $this->belongsToMany(Game::class, 'wishlists');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function cartGames()
    {
        return $this->belongsToMany(Game::class, 'carts');
    }

    public function cartBadgeCount()
    {
        $cartGames = $this->cartGames()->active()->get();
        $parentIds = $cartGames->where('type', 'game')->pluck('id')->toArray();
        
        $count = 0;
        $processed = [];
        
        foreach ($cartGames as $game) {
            if ($game->type === 'game') {
                $count++;
            } elseif ($game->type === 'dlc') {
                if (!in_array($game->parent_id, $parentIds)) {
                    // DLC without its parent in the cart.
                    // We group multiple orphan DLCs of the same parent as 1 item in the badge,
                    // or count them individually. Let's count them individually to be safe.
                    $count++;
                }
            }
        }
        
        return $count;
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function libraries()
    {
        return $this->hasMany(Library::class);
    }

    public function ownedGames()
    {
        return $this->belongsToMany(Game::class, 'libraries');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function otpCodes()
    {
        return $this->hasMany(OtpCode::class);
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=76b900&color=000&bold=true';
    }
}
