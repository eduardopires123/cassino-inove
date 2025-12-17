<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameHistory extends Model
{
    use HasFactory;

    protected $table = 'games_history';

    protected $fillable = [
        'user_id',
        'amount',
        'provider',
        'provider_tx_id',
        'game',
        'action',
        'round_id',
        'session_token',
        'json',
    ];

    // Relação com o usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
