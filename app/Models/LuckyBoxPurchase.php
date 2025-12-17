<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LuckyBoxPurchase extends Model
{
    use HasFactory;

    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'level',
        'cost',
        'prize',
        'prize_type',
        'spins_amount',
    ];

    /**
     * Os atributos que devem ser convertidos.
     *
     * @var array
     */
    protected $casts = [
        'cost' => 'integer',
        'prize' => 'decimal:2',
        'spins_amount' => 'integer',
    ];

    /**
     * Obtém o usuário associado a esta compra.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 