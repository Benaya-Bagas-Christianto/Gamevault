<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameGallery extends Model
{
    protected $fillable = ['game_id', 'type', 'path', 'thumbnail'];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
