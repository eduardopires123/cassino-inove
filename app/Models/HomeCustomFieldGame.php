<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeCustomFieldGame extends Model
{
    use HasFactory;

    protected $table = 'home_custom_field_games';

    protected $fillable = [
        'custom_field_id',
        'game_id',
        'position',
    ];

    protected $casts = [
        'custom_field_id' => 'integer',
        'game_id' => 'integer',
        'position' => 'integer',
    ];

    /**
     * Relacionamento com o campo personalizado
     */
    public function customField()
    {
        return $this->belongsTo(HomeCustomField::class, 'custom_field_id');
    }

    /**
     * Relacionamento com o jogo
     */
    public function game()
    {
        return $this->belongsTo(GamesApi::class, 'game_id');
    }
}

