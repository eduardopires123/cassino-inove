<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RouletteItem extends Model
{
    use HasFactory;

    protected $table = 'roulette_items';

    protected $fillable = [
        'name',
        'free_spins',
        'game_name',
        'color_code',
        'coupon_code',
        'probability',
        'deposit_value',
        'show_modal',
        'is_active'
    ];

    protected $casts = [
        'free_spins' => 'integer',
        'probability' => 'float',
        'deposit_value' => 'decimal:2',
        'show_modal' => 'boolean',
        'is_active' => 'boolean'
    ];

    /**
     * Escopo para itens ativos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Escopo para ordenar por probabilidade
     */
    public function scopeOrderedByProbability($query)
    {
        return $query->orderBy('probability', 'desc');
    }

    /**
     * Obter a probabilidade em porcentagem
     */
    public function getProbabilityPercentageAttribute()
    {
        return round($this->probability * 100, 2);
    }

    /**
     * Verificar se o item tem giros grátis
     */
    public function hasFreeSpins()
    {
        return $this->free_spins > 0;
    }

    /**
     * Verificar se o item tem cupom
     */
    public function hasCoupon()
    {
        return !empty($this->coupon_code) && $this->coupon_code !== 'NADA';
    }

    /**
     * Relacionamento com os giros deste item
     */
    public function spins()
    {
        return $this->hasMany(RouletteSpin::class, 'item_id');
    }

    /**
     * Obter estatísticas do item (quantas vezes foi sorteado)
     */
    public function getStatsAttribute()
    {
        return [
            'total_spins' => $this->spins()->count(),
            'today_spins' => $this->spins()->today()->count(),
            'last_spin' => $this->spins()->latest()->first()?->created_at
        ];
    }
} 