<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaspadinhaItem extends Model
{
    use HasFactory;

    protected $table = 'raspadinha_items';

    protected $fillable = [
        'raspadinha_id',
        'name',
        'image',
        'value',
        'premio_type',
        'product_description',
        'probability',
        'position',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'probability' => 'decimal:2',
        'position' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Tipos de prêmio disponíveis
     */
    const PREMIO_TYPES = [
        'saldo_real' => 'Saldo Real',
        'saldo_bonus' => 'Saldo Bônus',
        'rodadas_gratis' => 'Rodadas Grátis',
        'produto' => 'Produto'
    ];

    /**
     * Relacionamento com a raspadinha
     */
    public function raspadinha()
    {
        return $this->belongsTo(Raspadinha::class);
    }

    /**
     * Relacionamento com o histórico
     */
    public function history()
    {
        return $this->hasMany(RaspadinhaHistory::class);
    }

    /**
     * Escopo para buscar apenas itens ativos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Escopo para ordenar por posição
     */
    public function scopeOrderedByPosition($query)
    {
        return $query->orderBy('position', 'asc');
    }

    /**
     * Accessor para formatar o valor em moeda brasileira
     */
    public function getFormattedValueAttribute()
    {
        if ($this->premio_type === 'produto') {
            return $this->product_description ?: 'Produto';
        }
        
        if ($this->premio_type === 'rodadas_gratis') {
            return $this->value . ' rodadas grátis';
        }
        
        return 'R$ ' . number_format($this->value, 2, ',', '.');
    }

    /**
     * Accessor para descrição do prêmio
     */
    public function getPremioDescriptionAttribute()
    {
        switch ($this->premio_type) {
            case 'saldo_real':
                return 'Saldo Real: ' . $this->formatted_value;
            case 'saldo_bonus':
                return 'Saldo Bônus: ' . $this->formatted_value;
            case 'rodadas_gratis':
                return $this->value . ' Rodadas Grátis';
            case 'produto':
                return 'Produto: ' . ($this->product_description ?: $this->name);
            default:
                return $this->formatted_value;
        }
    }

    /**
     * Verifica se o prêmio requer valor monetário
     */
    public function requiresMonetaryValue()
    {
        return in_array($this->premio_type, ['saldo_real', 'saldo_bonus']);
    }

    /**
     * Verifica se o prêmio é um produto
     */
    public function isProduct()
    {
        return $this->premio_type === 'produto';
    }

    /**
     * Accessor para URL da imagem
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('raspadinha/' . $this->image);
        }
        return null;
    }
} 