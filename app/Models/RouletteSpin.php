<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RouletteSpin extends Model
{
    use HasFactory;

    protected $table = 'roulette_spins';

    protected $fillable = [
        'user_id',
        'item_id',
        'item_name',
        'coupon_code',
        'prize_type',
        'prize_awarded',
        'is_free_spin',
        'ip_address'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'item_id' => 'integer',
        'prize_awarded' => 'integer',
        'is_free_spin' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relacionamento com o usuário
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com o item da roleta
     */
    public function rouletteItem()
    {
        return $this->belongsTo(RouletteItem::class, 'item_id');
    }

    /**
     * Escopo para giros de hoje
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Escopo para giros de um usuário específico
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Contar giros de um usuário hoje
     */
    public static function countUserSpinsToday($userId)
    {
        return static::forUser($userId)->today()->count();
    }

    /**
     * Obter histórico de giros de um usuário
     */
    public static function getUserHistory($userId, $limit = 50)
    {
        return static::forUser($userId)
            ->with('rouletteItem')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
} 