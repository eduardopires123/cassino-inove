<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raspadinha extends Model
{
    use HasFactory;

    protected $table = 'raspadinhas';

    protected $fillable = [
        'name',
        'description',
        'image',
        'price',
        'turbo_price',
        'is_active',
        'rtp_percentage',
        'turbo_boost_percentage',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'turbo_price' => 'decimal:2',
        'is_active' => 'boolean',
        'rtp_percentage' => 'decimal:2',
        'turbo_boost_percentage' => 'decimal:2',
    ];

    /**
     * Relacionamento com os itens da raspadinha
     */
    public function items()
    {
        return $this->hasMany(RaspadinhaItem::class)->orderBy('position', 'asc');
    }

    /**
     * Relacionamento com o histórico de jogadas
     */
    public function history()
    {
        return $this->hasMany(RaspadinhaHistory::class);
    }

    /**
     * Escopo para buscar apenas raspadinhas ativas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Accessor para URL da imagem
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('raspadinha/' . $this->image);
        }
        return asset('img/raspadinha/default.png');
    }

    /**
     * Escopo para buscar raspadinhas mais jogadas
     */
    public function scopeMostPlayed($query, $limit = 10)
    {
        return $query->active()
            ->withCount(['history as plays_count'])
            ->orderBy('plays_count', 'desc')
            ->limit($limit);
    }

    /**
     * Gerar resultado da raspadinha baseado nas probabilidades e RTP da casa
     * Sistema de RTP: sempre garante margem de lucro para a casa
     * @param bool $isTurbo Se true, aplica boost nas probabilidades
     */
    public function generateResults($isTurbo = false)
    {
        $items = $this->items()->active()->get();
        $results = [];
        
        // RTP da casa (configurável por raspadinha, padrão 75%)
        $rtpPercentage = $this->rtp_percentage ?? 75; // 75% para jogadores, 25% para casa
        $houseEdge = 100 - $rtpPercentage; // 25% margem da casa
        
        // Verificar se deve aplicar controle de RTP
        $shouldApplyRTP = $this->shouldApplyRTPControl($rtpPercentage);
        
        if ($shouldApplyRTP) {
            // Casa precisa reter mais, forçar derrota
            $results = $this->generateLosingResults($items);
            \Log::info("RTP Control Applied - Forced Loss for Raspadinha ID: {$this->id}");
        } else {
            // Seguir probabilidades normais
            $totalItemsProbability = $items->sum('probability');
            $lossProbability = max(0, 100 - $totalItemsProbability);
            
            // Determinar se o jogador vai ganhar ou perder
            $randomValue = mt_rand(1, 10000) / 100;
            $willWin = $randomValue > $lossProbability;
            
            if ($willWin && $items->count() >= 3) {
                // JOGADOR VAI GANHAR
                $winningItem = $this->selectWinningItem($items, $isTurbo);
                $results = $this->generateWinningResults($winningItem, $items);
                
                // Registrar vitória para controle de RTP
                $this->recordRTPEvent('win', $winningItem->value);
            } else {
                // JOGADOR VAI PERDER
                $results = $this->generateLosingResults($items);
                
                // Registrar derrota para controle de RTP
                $this->recordRTPEvent('loss', 0);
            }
        }
        
        // Embaralhar os resultados para randomizar posições
        shuffle($results);
        
        return $results;
    }
    
    /**
     * Verificar se deve aplicar controle de RTP
     */
    private function shouldApplyRTPControl($targetRTP)
    {
        // Buscar estatísticas dos últimos 100 jogos desta raspadinha
        $recentGames = \DB::table('raspadinha_history')
            ->where('raspadinha_id', $this->id)
            ->where('created_at', '>=', now()->subDays(1)) // Últimas 24 horas
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get();
            
        if ($recentGames->count() < 20) {
            // Poucos jogos, não aplicar controle ainda
            return false;
        }
        
        $totalPaid = $recentGames->sum('amount_paid');
        $totalWon = $recentGames->sum('amount_won');
        
        if ($totalPaid == 0) {
            return false;
        }
        
        // Calcular RTP atual
        $currentRTP = ($totalWon / $totalPaid) * 100;
        
        // Se RTP atual está acima do target + margem de segurança, aplicar controle
        $safetyMargin = 5; // 5% de margem de segurança
        return $currentRTP > ($targetRTP + $safetyMargin);
    }
    
    /**
     * Registrar evento para controle de RTP
     */
    private function recordRTPEvent($type, $value)
    {
        // Este método pode ser expandido para logging detalhado se necessário
        \Log::info("RTP Event - Raspadinha ID: {$this->id}, Type: {$type}, Value: {$value}");
    }

    /**
     * Selecionar item vencedor baseado nas probabilidades dos itens
     * @param bool $isTurbo Se true, aplica boost nas probabilidades
     */
    private function selectWinningItem($items, $isTurbo = false)
    {
        $turboBoost = $isTurbo ? ($this->turbo_boost_percentage ?? 0) : 0;
        
        // Calcular probabilidades com boost se for turbo
        $adjustedItems = $items->map(function($item) use ($turboBoost) {
            $adjustedProbability = $item->probability + $turboBoost;
            // Não permitir probabilidade maior que 100%
            $adjustedProbability = min($adjustedProbability, 100);
            
            return (object)[
                'item' => $item,
                'probability' => $adjustedProbability
            ];
        });
        
        $randomValue = mt_rand(1, 10000) / 100;
        $currentSum = 0;
        
        foreach ($adjustedItems as $adjustedItem) {
            $currentSum += $adjustedItem->probability;
            if ($randomValue <= $currentSum) {
                return $adjustedItem->item;
            }
        }
        
        // Fallback: retorna o primeiro item se algo der errado
        return $items->first();
    }

    /**
     * Gerar resultados de vitória (3 itens iguais + outros diferentes)
     */
    private function generateWinningResults($winningItem, $allItems)
    {
        $results = [];
        
        // Adicionar 3 itens vencedores
        for ($i = 0; $i < 3; $i++) {
            $results[] = $winningItem->id;
        }
        
        // Adicionar 6 itens diferentes (sem repetir o item vencedor)
        $otherItems = $allItems->where('id', '!=', $winningItem->id);
        
        if ($otherItems->count() > 0) {
            for ($i = 0; $i < 6; $i++) {
                $randomItem = $otherItems->random();
                $results[] = $randomItem->id;
            }
        } else {
            // Se não há outros itens, usar itens com valor 0.00 da raspadinha
            $zeroValueItems = $allItems->where('value', 0.00);
            
            if ($zeroValueItems->count() > 0) {
                for ($i = 0; $i < 6; $i++) {
                    $zeroItem = $zeroValueItems->first();
                    $results[] = $zeroItem->id;
                }
            } else {
                // Fallback: usar o primeiro item disponível
                for ($i = 0; $i < 6; $i++) {
                    $firstItem = $allItems->first();
                    $results[] = $firstItem->id;
                }
            }
        }
        
        return $results;
    }

    /**
     * Gerar resultados de perda (garantir que não há 3 iguais)
     */
    private function generateLosingResults($items)
    {
        $results = [];
        $itemCounts = [];
        
        // Gerar 9 itens garantindo que nenhum aparece 3 vezes
        for ($i = 0; $i < 9; $i++) {
            $availableItems = $items->filter(function($item) use ($itemCounts) {
                return ($itemCounts[$item->id] ?? 0) < 2; // Máximo 2 de cada item
            });
            
            if ($availableItems->count() == 0) {
                // Se não há itens disponíveis, usar itens com valor 0.00
                $zeroValueItems = $items->where('value', 0.00);
                
                if ($zeroValueItems->count() > 0) {
                    $selectedItem = $zeroValueItems->first();
                } else {
                    // Fallback: usar qualquer item
                    $selectedItem = $items->first();
                }
            } else {
                $selectedItem = $availableItems->random();
            }
            
            $itemCounts[$selectedItem->id] = ($itemCounts[$selectedItem->id] ?? 0) + 1;
            $results[] = $selectedItem->id;
        }
        
        return $results;
    }

    /**
     * Calcular prêmio baseado nos resultados (verificar se há 3 iguais)
     */
    public function calculatePrize($results)
    {
        // Agrupar por ID do item
        $itemCounts = [];
        $winningItemId = null;
        
        foreach ($results as $itemId) {
            $itemCounts[$itemId] = ($itemCounts[$itemId] ?? 0) + 1;
            
            // Se encontrou 3 iguais, é o item vencedor
            if ($itemCounts[$itemId] >= 3) {
                $winningItemId = $itemId;
                break;
            }
        }
        
        if ($winningItemId) {
            $winningItem = $this->items()->where('id', $winningItemId)->first();
            
            if ($winningItem) {
                return [
                    'has_prize' => true,
                    'item_id' => $winningItem->id,
                    'prize_value' => $winningItem->value,
                    'prize_type' => $winningItem->premio_type,
                    'prize_description' => $this->getPrizeDescription($winningItem),
                    'prize_name' => $winningItem->name,
                    'prize_image' => $winningItem->image_url,
                    'winning_item' => $winningItem
                ];
            }
        }
        
        return [
            'has_prize' => false,
            'item_id' => null,
            'prize_value' => 0,
            'prize_type' => null,
            'prize_description' => 'Sem prêmio',
            'prize_name' => '',
            'prize_image' => '',
            'winning_item' => null
        ];
    }

    /**
     * Obter descrição do prêmio baseada no tipo
     */
    private function getPrizeDescription($item)
    {
        switch ($item->premio_type) {
            case 'saldo_real':
                return 'Saldo Real: R$ ' . number_format($item->value, 2, ',', '.');
            case 'saldo_bonus':
                return 'Saldo Bônus: R$ ' . number_format($item->value, 2, ',', '.');
            case 'rodadas_gratis':
                return $item->value . ' Rodadas Grátis';
            case 'produto':
                return 'Produto: ' . ($item->product_description ?: $item->name);
            default:
                return $item->name;
        }
    }

    /**
     * Obter estatísticas de RTP atual
     */
    public function getRTPStats($days = 7)
    {
        $games = \DB::table('raspadinha_history')
            ->where('raspadinha_id', $this->id)
            ->where('created_at', '>=', now()->subDays($days))
            ->get();
            
        $totalGames = $games->count();
        $totalPaid = $games->sum('amount_paid');
        $totalWon = $games->sum('amount_won');
        $winners = $games->where('amount_won', '>', 0)->count();
        
        $currentRTP = $totalPaid > 0 ? ($totalWon / $totalPaid) * 100 : 0;
        $winRate = $totalGames > 0 ? ($winners / $totalGames) * 100 : 0;
        $houseProfit = $totalPaid - $totalWon;
        $houseProfitPercentage = $totalPaid > 0 ? ($houseProfit / $totalPaid) * 100 : 0;
        
        return [
            'total_games' => $totalGames,
            'total_paid' => $totalPaid,
            'total_won' => $totalWon,
            'house_profit' => $houseProfit,
            'current_rtp' => round($currentRTP, 2),
            'target_rtp' => $this->rtp_percentage ?? 75,
            'house_profit_percentage' => round($houseProfitPercentage, 2),
            'win_rate' => round($winRate, 2),
            'winners_count' => $winners,
            'days_analyzed' => $days,
            'rtp_status' => $currentRTP > ($this->rtp_percentage ?? 75) ? 'above_target' : 'within_target'
        ];
    }

    /**
     * Obter estatísticas de probabilidade
     */
    public function getProbabilityStats()
    {
        $totalItemsProbability = $this->items()->active()->sum('probability');
        $lossProbability = max(0, 100 - $totalItemsProbability);
        
        return [
            'items_probability' => $totalItemsProbability,
            'loss_probability' => $lossProbability,
            'win_chance' => $totalItemsProbability,
            'lose_chance' => $lossProbability
        ];
    }
} 