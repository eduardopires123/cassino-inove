<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SportBetSummary extends Model
{
    protected $table = 'sportbetsummary';

    protected $fillable = [
        'provider',
        'user_id',
        'transactionId',
        'operation',
        'dedn',
        'status',
        'statusel',
        'reason',
        'amount',
        'amount_win',
        'cashout',
        'stake',
        'transaction',
        'betslip',
    ];

    /**
     * Relacionamento com usuário
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Verifica se é uma aposta do provedor BetBy
     */
    public function isBetBy()
    {
        return $this->provider === 'betby';
    }

    /**
     * Verifica se é uma aposta do provedor Digitain
     */
    public function isDigitain()
    {
        return $this->provider === 'digitain';
    }

    /**
     * Verifica se a aposta foi ganha
     */
    public function isWon()
    {
        if ($this->isBetBy()) {
            return $this->status === 'win';
        }
        return $this->operation === 'credit';
    }

    /**
     * Verifica se a aposta foi perdida
     */
    public function isLost()
    {
        if ($this->isBetBy()) {
            return $this->status === 'lost';
        }
        return $this->operation === 'lose';
    }

    /**
     * Verifica se a aposta está pendente
     */
    public function isPending()
    {
        if ($this->isBetBy()) {
            return $this->status === 'Pending';
        }
        return $this->status === 'Pending';
    }

    /**
     * Obtém o valor do ganho baseado no provedor
     */
    public function getWinAmountAttribute()
    {
        if ($this->isBetBy() && $this->isWon()) {
            return $this->amount_win;
        }
        if ($this->isDigitain() && $this->isWon()) {
            return $this->amount;
        }
        return 0;
    }

    /**
     * Scope para filtrar por provedor
     */
    public function scopeProvider($query, $provider)
    {
        return $query->where('provider', $provider);
    }

    /**
     * Scope para apostas ganhas
     */
    public function scopeWon($query)
    {
        return $query->where(function($q) {
            $q->where(function($betby) {
                $betby->where('provider', 'betby')->where('status', 'win');
            })->orWhere(function($digitain) {
                $digitain->where('provider', 'digitain')->where('operation', 'credit');
            });
        });
    }

    /**
     * Scope para apostas perdidas
     */
    public function scopeLost($query)
    {
        return $query->where(function($q) {
            $q->where(function($betby) {
                $betby->where('provider', 'betby')->where('status', 'lost');
            })->orWhere(function($digitain) {
                $digitain->where('provider', 'digitain')->where('operation', 'lose');
            });
        });
    }

    /**
     * Scope para apostas pendentes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }
}
