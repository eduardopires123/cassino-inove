<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BingoDrawnNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'bingo_game_id',
        'number',
        'order',
        'drawn_at',
    ];

    protected $casts = [
        'drawn_at' => 'datetime',
    ];

    public function game()
    {
        return $this->belongsTo(BingoGame::class, 'bingo_game_id');
    }
} 