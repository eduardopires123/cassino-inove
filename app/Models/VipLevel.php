<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VipLevel extends Model
{
    use HasFactory;
    
    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'level',
        'min_deposit',
        'max_deposit',
        'image',
        'benefits',
        'active',
        'order',
        'coins_reward',
        'balance_reward',
        'balance_bonus_reward',
        'free_spins_reward'
    ];
    
    /**
     * Os atributos que devem ser convertidos.
     *
     * @var array
     */
    protected $casts = [
        'min_deposit' => 'float',
        'max_deposit' => 'float',
        'active' => 'boolean',
        'order' => 'integer',
        'level' => 'integer',
        'coins_reward' => 'integer',
        'balance_reward' => 'decimal:2',
        'balance_bonus_reward' => 'decimal:2',
        'free_spins_reward' => 'integer'
    ];
    
    /**
     * Retorna todos os níveis VIP ativos, ordenados por ordem/nível
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAllActive()
    {
        return self::where('active', true)
            ->orderBy('order', 'asc')
            ->orderBy('level', 'asc')
            ->get();
    }
    
    /**
     * Determina o nível VIP com base no valor de depósitos
     *
     * @param float $totalDeposits
     * @return \App\Models\VipLevel
     */
    public static function getCurrentLevelByDeposit($totalDeposits)
    {
        // Converter para garantir que é float
        $totalDeposits = (float) $totalDeposits;
        
        $query = self::where('active', true)
            ->where('min_deposit', '<=', $totalDeposits)
            ->where(function ($query) use ($totalDeposits) {
                $query->whereNull('max_deposit')
                    ->orWhere('max_deposit', '>=', $totalDeposits);
            })
            ->orderBy('level', 'desc');
        
        $result = $query->first();
        
        if (!$result) {
            // Verificar se existem níveis cadastrados
            $allLevels = self::where('active', true)->orderBy('min_deposit')->get();
            
            // Se não encontrar o nível específico, mas existirem níveis,
            // encontrar o nível com o maior min_deposit que seja menor que o totalDeposits
            if ($allLevels->count() > 0) {
                // Buscar especificamente apenas o nível com o maior min_deposit que seja menor ou igual ao valor de depósito
                $result = self::where('active', true)
                    ->where('min_deposit', '<=', $totalDeposits)
                    ->orderBy('min_deposit', 'desc')
                    ->first();
                
                if (!$result) {
                    // Se ainda não encontrar, pegar o nível com menor min_deposit
                    $result = self::where('active', true)
                        ->orderBy('min_deposit', 'asc')
                        ->first();
                }
            }
        }
        
        return $result;
    }
    
    /**
     * Obtém o próximo nível após o nível atual
     *
     * @return \App\Models\VipLevel|null
     */
    public function getNextLevel()
    {
        return self::where('active', true)
            ->where('level', '>', $this->level)
            ->orderBy('level', 'asc')
            ->first();
    }
}
