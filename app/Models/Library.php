<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Library extends Model
{
    protected $fillable = ['user_id', 'game_id', 'transaction_detail_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function transactionDetail()
    {
        return $this->belongsTo(TransactionDetail::class);
    }
}
