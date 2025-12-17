<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaspadinhaHistory extends Model
{
    use HasFactory;

    protected $table = 'raspadinha_history';

    protected $fillable = [
        'user_id',
        'raspadinha_id',
        'raspadinha_item_id',
        'amount_paid',
        'amount_won',
        'prize_type',
        'prize_description',
        'is_turbo',
        'is_auto',
        'auto_quantity',
        'status',
        'results',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'amount_won' => 'decimal:2',
        'is_turbo' => 'boolean',
        'is_auto' => 'boolean',
        'auto_quantity' => 'integer',
        'results' => 'array',
    ];

    /**
     * Relacionamento com o usuário
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com a raspadinha
     */
    public function raspadinha()
    {
        return $this->belongsTo(Raspadinha::class);
    }

    /**
     * Relacionamento com o item premiado
     */
    public function raspadinhaItem()
    {
        return $this->belongsTo(RaspadinhaItem::class);
    }

    /**
     * Escopo para buscar jogadas completas
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Escopo para buscar jogadas de um usuário
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Verificar se a jogada foi premiada
     */
    public function isWinner()
    {
        return $this->amount_won > 0;
    }

    /**
     * Accessor para valor pago formatado
     */
    public function getFormattedAmountPaidAttribute()
    {
        return 'R$ ' . number_format($this->amount_paid, 2, ',', '.');
    }

    /**
     * Accessor para valor ganho formatado
     */
    public function getFormattedAmountWonAttribute()
    {
        return 'R$ ' . number_format($this->amount_won, 2, ',', '.');
    }

    /**
     * Obter total de jogadas de um usuário
     */
    public static function getTotalPlaysByUser($userId)
    {
        return self::where('user_id', $userId)->count();
    }

    /**
     * Obter total ganho por um usuário
     */
    public static function getTotalWonByUser($userId)
    {
        return self::where('user_id', $userId)->sum('amount_won');
    }

    /**
     * Obter total investido por um usuário
     */
    public static function getTotalInvestedByUser($userId)
    {
        return self::where('user_id', $userId)->sum('amount_paid');
    }
} 