<?php

namespace App\Traits;

use App\Models\VipLevel;
use Illuminate\Support\Facades\Log;

trait HasRanking
{
    /**
     * Retorna o nível do usuário baseado no valor total de depósitos
     *
     * @return array
     */
    public function getRanking()
    {
        try {
            // Obter o total de depósitos aprovados (type=1, status=1)
            $totalDeposits = $this->transactions()
                ->where('type', 1)
                ->where('status', 1)
                ->sum('amount');
            
            // Garantir que seja um float
            $totalDeposits = (float) $totalDeposits;
            
            // Buscar todos os níveis VIP para referência
            $allLevels = VipLevel::getAllActive();
            
            // Se não há depósitos ou níveis definidos, usar valores padrão
            if ($totalDeposits <= 0 || $allLevels->isEmpty()) {
                return [
                    'level' => 1,
                    'name' => 'Bronze',
                    'image' => 'img/ranking/1.png',
                    'current_deposit' => 0,
                    'next_level' => 2,
                    'next_level_deposit' => 200,
                    'progress' => 0,
                    'has_reward' => false,
                    'reward_id' => null
                ];
            }
            
            // Tentar obter o nível atual com base no depósito do banco de dados
            $currentLevelObj = VipLevel::getCurrentLevelByDeposit($totalDeposits);
            
            // Se não encontrar o nível no banco, tente encontrar o nível mais próximo
            if (!$currentLevelObj && !$allLevels->isEmpty()) {
                // Ordenar níveis por min_deposit
                $sortedLevels = $allLevels->sortBy('min_deposit');
                
                // Encontrar o nível apropriado
                $currentLevelObj = null;
                foreach ($sortedLevels as $level) {
                    if ($totalDeposits >= $level->min_deposit) {
                        $currentLevelObj = $level;
                    } else {
                        break;
                    }
                }
                
                // Se ainda não encontrou um nível, usar o primeiro nível
                if (!$currentLevelObj) {
                    $currentLevelObj = $sortedLevels->first();
                }
            }
            
            // Se ainda não tiver um nível definido, use um valor padrão
            if (!$currentLevelObj) {
                return [
                    'level' => 1,
                    'name' => 'Bronze',
                    'image' => 'img/ranking/1.png',
                    'current_deposit' => $totalDeposits,
                    'next_level' => 2,
                    'next_level_deposit' => 200,
                    'progress' => ($totalDeposits / 200) * 100,
                    'has_reward' => false,
                    'reward_id' => null
                ];
            }
            
            // Se encontrou o nível no banco
            $currentLevel = $currentLevelObj->level;
            $currentLevelData = $currentLevelObj->toArray();
            
            // Obter o próximo nível
            $nextLevelObj = $currentLevelObj->getNextLevel();
            $nextLevel = $nextLevelObj ? $nextLevelObj->level : null;
            $nextLevelData = $nextLevelObj ? $nextLevelObj->toArray() : null;
            
            // Calcular progresso para o próximo nível
            $progress = 0;
            if ($nextLevelObj) {
                // Obter valor mínimo do nível atual
                $currentLevelMinDeposit = $currentLevelData['min_deposit'];
                
                // Calcular quanto falta para o próximo nível
                $depositRange = $nextLevelData['min_deposit'] - $currentLevelMinDeposit;
                $currentProgress = $totalDeposits - $currentLevelMinDeposit;
                
                // Calcular a porcentagem de progresso
                $progress = $depositRange > 0 ? min(100, ($currentProgress / $depositRange) * 100) : 0;
            } else {
                // Se já está no nível máximo
                $progress = 100;
            }
            
            // Verificar recompensa disponível
            $hasReward = false;
            $rewardId = null;
            if (class_exists('App\Models\VipReward')) {
                $reward = \App\Models\VipReward::where('user_id', $this->id)
                    ->where('vip_level_id', $currentLevelObj->id)
                    ->first();
                
                $hasReward = !$reward || !$reward->is_claimed;
                $rewardId = $reward ? $reward->id : null;
            }
            
            // Retornar array com dados de ranking
            return [
                'level' => $currentLevel,
                'name' => $currentLevelData['name'],
                'image' => $currentLevelData['image'],
                'current_deposit' => $totalDeposits,
                'next_level' => $nextLevel,
                'next_level_deposit' => $nextLevelData ? $nextLevelData['min_deposit'] : null,
                'progress' => $progress,
                'has_reward' => $hasReward,
                'reward_id' => $rewardId
            ];
        } catch (\Exception $e) {
            // Em caso de erro, retornar valores padrão seguros
            return [
                'level' => 1,
                'name' => 'Bronze',
                'image' => 'img/ranking/1.png',
                'current_deposit' => 0,
                'next_level' => 2,
                'next_level_deposit' => 200,
                'progress' => 0,
                'has_reward' => false,
                'reward_id' => null
            ];
        }
    }
} 