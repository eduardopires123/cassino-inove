<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transactions;
use App\Models\VipLevel;
use App\Models\VipReward;
use Illuminate\Support\Facades\DB;
use App\Models\GameHistory;
use App\Models\Tournament;

class VipController extends Controller
{
    /**
     * Mostrar a página de níveis VIP
     *
     * @return \Illuminate\View\View
     */
    public function levels()
    {
        $user = Auth::user();

        // Calcular o total de depósitos do usuário
        $totalDeposits = 0;
        if ($user) {
            // Obtém o total de depósitos (type=0) aprovados (status=1) para o usuário atual
            $totalDeposits = Transactions::where('user_id', $user->id)
                ->where('type', 0)
                ->where('status', 1)
                ->sum('amount');

            // Converter para float para garantir formato correto
            $totalDeposits = (float) $totalDeposits;
        }

        // Buscar todos os níveis VIP ativos do banco de dados
        $levels = VipLevel::getAllActive();

        // Converter para array para usar na view
        $levelsArray = $levels->toArray();

        // Determinar o nível atual do usuário com base no totalDeposits
        $currentLevel = 1; // Nível padrão (Bronze)
        $nextLevel = 2;
        $currentLevelData = null;
        $nextLevelData = null;
        $currentLevelObj = null;

        // Se não houver níveis definidos, criar um array de fallback
        if (empty($levelsArray)) {
            // Criar níveis básicos para que a página não quebre
            $levelsArray = [
                [
                    'level' => 1,
                    'name' => 'Bronze',
                    'min_deposit' => 0,
                    'image' => 'img/ranking/1.png',
                    'benefits' => 'Benefícios básicos do clube VIP'
                ],
                [
                    'level' => 2,
                    'name' => 'Prata',
                    'min_deposit' => 3, // Ajustando para 3 conforme solicitado
                    'image' => 'img/ranking/2.png',
                    'benefits' => 'Benefícios do nível prata'
                ]
            ];
        }

        if ($user) {
            // Obter o nível atual com base no depósito
            $currentLevelObj = VipLevel::getCurrentLevelByDeposit($totalDeposits);

            // Verificar manualmente com base nos valores conhecidos do depósito
            $actualLevel = null;
            foreach ($levels->sortBy('min_deposit') as $level) {
                if ($totalDeposits >= $level->min_deposit) {
                    $actualLevel = $level;
                }
            }

            if ($actualLevel) {
                // Usar o nível verificado manualmente se for diferente do determinado pelo banco de dados
                if (!$currentLevelObj || $currentLevelObj->level != $actualLevel->level) {
                    $currentLevelObj = $actualLevel;
                }
            }

            if (!$currentLevelObj && !$levels->isEmpty()) {
                // Se não encontrou um nível, tentar determinar manualmente
                foreach ($levels->sortBy('min_deposit') as $level) {
                    if ($totalDeposits >= $level->min_deposit) {
                        $currentLevelObj = $level;
                    }
                }
            }

            if ($currentLevelObj) {
                $currentLevel = $currentLevelObj->level;
                $currentLevelData = $currentLevelObj->toArray();

                // Obter o próximo nível
                $nextLevelObj = $currentLevelObj->getNextLevel();

                if ($nextLevelObj) {
                    $nextLevel = $nextLevelObj->level;
                    $nextLevelData = $nextLevelObj->toArray();
                } else {
                    $nextLevel = null;
                    $nextLevelData = null;
                }
            } else {
                // Caso não tenha nível atual (não deve acontecer se o seeder for executado)
                // Use o primeiro nível disponível
                if (!empty($levelsArray)) {
                    $currentLevelData = $levelsArray[0];
                    $currentLevel = $currentLevelData['level'];

                    if (count($levelsArray) > 1) {
                        $nextLevelData = $levelsArray[1];
                        $nextLevel = $nextLevelData['level'];
                    } else {
                        $nextLevelData = null;
                        $nextLevel = null;
                    }
                }
            }
        } else {
            // Usuário não autenticado, use o primeiro nível disponível
            if (!empty($levelsArray)) {
                $currentLevelData = $levelsArray[0];
                $currentLevel = $currentLevelData['level'];

                if (count($levelsArray) > 1) {
                    $nextLevelData = $levelsArray[1];
                    $nextLevel = $nextLevelData['level'];
                } else {
                    $nextLevelData = null;
                    $nextLevel = null;
                }
            }
        }

        // Inicializar o ranking com valores padrão
        $ranking = [
            'level' => $currentLevel,
            'name' => $currentLevelData['name'] ?? 'Bronze',
            'image' => $currentLevelData['image'] ?? 'img/ranking/1.png',
            'current_deposit' => $totalDeposits,
            'next_level' => $nextLevel,
            'next_level_deposit' => $nextLevelData['min_deposit'] ?? null,
            'has_reward' => false,
            'reward_id' => null
        ];

        // Se o usuário estiver autenticado e tiver um nível atual, verificar recompensa
        if ($user && $currentLevelObj) {
            // Verificar o último nível recompensado do usuário
            $lastVipLevel = $user->last_vip_level ?? 0;

            // Se o last_vip_level for zero, inicializar com o valor 0
            // Isso permite que o usuário resgata a recompensa do primeiro nível após o primeiro depósito
            if ($lastVipLevel === 0 && $totalDeposits > 0) {
                $user->last_vip_level = 0;
                $user->save();
                $lastVipLevel = 0;
            }

            // Corrigir o valor de last_vip_level se for maior que o nível atual
            if ($lastVipLevel > $currentLevel) {
                $user->last_vip_level = $currentLevel - 1;
                $user->save();
                $lastVipLevel = $user->last_vip_level;
            }

            // Verificar também se já existe um registro na tabela vip_level_rewards
            try {
                // Verificar existência de recompensa no banco de dados
                $existingReward = VipReward::where('user_id', $user->id)
                    ->where('vip_level_id', $currentLevelObj->id)
                    ->where('is_claimed', true)
                    ->exists();

                // DUPLA VERIFICAÇÃO: last_vip_level E registro na tabela vip_level_rewards
                // Só pode resgatar se o nível atual for maior que o último nível recompensado
                // ou se for o primeiro resgate E não tiver registro de resgate para este nível
                if (($currentLevel <= $lastVipLevel && $lastVipLevel !== 0) || $existingReward) {
                    $ranking['has_reward'] = false;
                } elseif (($currentLevel > $lastVipLevel || $lastVipLevel === 0) && !$existingReward) {
                    $ranking['has_reward'] = true;
                }
            } catch (\Exception $e) {
                // Em caso de erro, consideramos que a recompensa está disponível
                $ranking['has_reward'] = true;
            }
        }

        return view('vip.levels', compact('ranking', 'levelsArray', 'totalDeposits', 'currentLevel'));
    }

    /**
     * Processar o resgate de recompensa VIP
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function claimReward(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Você precisa estar logado para resgatar recompensas VIP.');
        }

        try {
            DB::beginTransaction();

            // Obter o nível atual do usuário
            $totalDeposits = Transactions::where('user_id', $user->id)
                ->where('type', 1)
                ->where('status', 1)
                ->sum('amount');

            // Verificar na tabela de transações se o usuário já fez os depósitos reportados
            // Esta é a verificação REAL do depósito atual
            $depositsMade = Transactions::where('user_id', $user->id)
                ->where('type', 0) // Tipo 0 é depósito na tabela mostrada pelo usuário
                ->where('status', 1)
                ->sum('amount');

            // Usar o depósito real para determinar o nível
            $realTotalDeposits = (float) $depositsMade;

            // Converter para float
            $totalDeposits = (float) $totalDeposits;

            $currentLevel = VipLevel::getCurrentLevelByDeposit($realTotalDeposits > 0 ? $realTotalDeposits : $totalDeposits);

            if (!$currentLevel) {
                return redirect()->back()
                    ->with('error', 'Você não possui um nível VIP válido para resgatar recompensas.');
            }

            // Verificar o último nível recompensado do usuário
            $lastVipLevel = $user->last_vip_level ?? 0;

            // Verificar se já existe um registro de recompensa para este nível e usuário
            $existingReward = VipReward::where('user_id', $user->id)
                ->where('vip_level_id', $currentLevel->id)
                ->where('is_claimed', true)
                ->exists();

            // DUPLA VERIFICAÇÃO: verificar se o usuário pode resgatar a recompensa
            // 1. O nível atual deve ser maior que o último nível recompensado (ou first_deposit)
            // 2. E não deve existir um registro de recompensa já resgatada para este nível
            if (($currentLevel->level <= $lastVipLevel && $lastVipLevel !== 0) || $existingReward) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Você já resgatou todas as recompensas disponíveis para seu nível VIP atual.');
            }

            // Criar novo registro de recompensa
            $reward = new VipReward();
            $reward->user_id = $user->id;
            $reward->vip_level_id = $currentLevel->id;
            $reward->is_claimed = true;
            $reward->claimed_at = now();

            // Processar recompensas
            $coinsRewarded = $currentLevel->coins_reward ?? 0;
            $balanceRewarded = $currentLevel->balance_reward ?? 0;
            $balanceBonusRewarded = $currentLevel->balance_bonus_reward ?? 0;
            $freeSpinsRewarded = $currentLevel->free_spins_reward ?? 0;

            // Registrar valores na recompensa
            $reward->coins_rewarded = $coinsRewarded;
            $reward->balance_rewarded = $balanceRewarded;
            $reward->balance_bonus_rewarded = $balanceBonusRewarded;
            $reward->free_spins_rewarded = $freeSpinsRewarded;
            $reward->save();

            // Obter a carteira do usuário
            $wallet = $user->wallet;

            if (!$wallet) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Erro ao processar sua recompensa: carteira não encontrada.');
            }

            // Adicionar moedas e saldo à carteira do usuário
            if ($coinsRewarded > 0) {
                $wallet->coin = ($wallet->coin ?? 0) + $coinsRewarded;
            }

            if ($balanceRewarded > 0) {
                $wallet->balance = ($wallet->balance ?? 0) + $balanceRewarded;
            }

            if ($balanceBonusRewarded > 0) {
                $wallet->balance_bonus = ($wallet->balance_bonus ?? 0) + $balanceBonusRewarded;
            }

            if ($freeSpinsRewarded > 0) {
                $wallet->free_spins = ($wallet->free_spins ?? 0) + $freeSpinsRewarded;
            }

            $wallet->save();

            // Atualizar o último nível VIP recompensado do usuário
            $user->last_vip_level = $currentLevel->level;
            $user->save();

            // Registrar um único log para todos os tipos de recompensa
            $rewardTypes = [];
            $oldValues = [];
            $newValues = [];
            
            if ($coinsRewarded > 0) {
                $rewardTypes[] = $coinsRewarded . ' coins';
                $oldValues[] = 'Coins: ' . number_format($wallet->coin - $coinsRewarded);
                $newValues[] = 'Coins: ' . number_format($wallet->coin);
            }
            
            if ($balanceRewarded > 0) {
                $rewardTypes[] = 'R$ ' . number_format($balanceRewarded, 2, ',', '.') . ' de saldo real';
                $oldValues[] = 'Saldo: R$ ' . number_format($wallet->balance - $balanceRewarded, 2, ',', '.');
                $newValues[] = 'Saldo: R$ ' . number_format($wallet->balance, 2, ',', '.');
            }
            
            if ($balanceBonusRewarded > 0) {
                $rewardTypes[] = 'R$ ' . number_format($balanceBonusRewarded, 2, ',', '.') . ' de saldo bônus';
                $oldValues[] = 'Bônus: R$ ' . number_format($wallet->balance_bonus - $balanceBonusRewarded, 2, ',', '.');
                $newValues[] = 'Bônus: R$ ' . number_format($wallet->balance_bonus, 2, ',', '.');
            }
            
            if ($freeSpinsRewarded > 0) {
                $rewardTypes[] = $freeSpinsRewarded . ' rodadas grátis';
                $oldValues[] = 'Rodadas: ' . number_format($wallet->free_spins - $freeSpinsRewarded);
                $newValues[] = 'Rodadas: ' . number_format($wallet->free_spins);
            }
            
            // Criar um único log contendo todos os prêmios
            if (!empty($rewardTypes)) {
                \App\Models\Admin\Logs::create([
                    'field_name' => 'Recompensa Nível VIP',
                    'old_value' => implode(', ', $oldValues),
                    'new_value' => implode(', ', $newValues),
                    'updated_by' => $currentLevel->id, // ID do nível VIP
                    'user_id' => $user->id,
                    'type' => 10, // Tipo 10 para recompensas de nível VIP (todos os tipos)
                    'log' => 'Usuário recebeu ' . implode(', ', $rewardTypes) . ' do nível VIP ' . $currentLevel->name
                ]);
            }

            DB::commit();

            // Mensagem de sucesso
            $successMessage = 'Parabéns! Você resgatou sua recompensa VIP com sucesso.';

            // Adicionar detalhes da recompensa à mensagem
            $rewardDetails = [];
            if ($coinsRewarded > 0) $rewardDetails[] = $coinsRewarded . ' moedas';
            if ($balanceRewarded > 0) $rewardDetails[] = 'R$ ' . number_format($balanceRewarded, 2, ',', '.') . ' de saldo';
            if ($balanceBonusRewarded > 0) $rewardDetails[] = 'R$ ' . number_format($balanceBonusRewarded, 2, ',', '.') . ' de saldo bônus';
            if ($freeSpinsRewarded > 0) $rewardDetails[] = $freeSpinsRewarded . ' rodadas grátis';

            if (!empty($rewardDetails)) {
                $successMessage .= ' Você recebeu: ' . implode(', ', $rewardDetails) . '.';
            }

            return redirect()->back()->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Ocorreu um erro ao processar sua recompensa VIP: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar a página de torneios
     *
     * @return \Illuminate\View\View
     */
    public function tournaments()
    {
        $user = Auth::user();

        // Definir um ranking padrão caso a função getRanking() retorne null
        $ranking = $user ? ($user->getRanking() ?? [
            'level' => 1,
            'name' => 'Bronze',
            'image' => 'img/ranking/1.png',
            'current_deposit' => 0,
            'next_level' => 2,
            'next_level_deposit' => 200,
            'progress' => 0,
            'has_reward' => false,
            'reward_id' => null
        ]) : [
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

        // Buscar torneios ativos do banco de dados
        $activeTournaments = \App\Models\Tournament::getActive();

        // Adicionar informações adicionais a cada torneio
        foreach ($activeTournaments as $tournament) {
            // Adicionar contagem de jogadores
            $tournament->playerCount = $tournament->getPlayerCount();

            // Calcular tempo restante
            $tournament->remainingTime = $tournament->getRemainingTimeFormatted();
            
            // Buscar informações sobre jogos qualificados se disponíveis
            if (!empty($tournament->qualified_games)) {
                try {
                    // Tentar obter os nomes dos jogos a partir dos IDs usando a nova estrutura consolidada
                    $gameIds = explode(',', $tournament->qualified_games);
                    $qualifiedGames = \DB::table('games_api')
                        ->whereIn('games_api.id', $gameIds)
                        ->where('games_api.status', 1)
                        ->get(['games_api.id', 'games_api.name', 'games_api.slug']);
                    
                    $gameNames = [];
                    $gameIdentifiers = [];
                    
                    foreach ($qualifiedGames as $game) {
                        $gameNames[] = $game->name;
                        
                        // Usar o slug diretamente da tabela consolidada games_api
                        $gameIdentifiers[] = $game->slug;
                    }
                    
                    $tournament->qualifiedGamesList = $gameNames;
                    $tournament->qualifiedGamesIdentifiers = $gameIdentifiers;
                } catch (\Exception $e) {
                    // Em caso de erro, apenas use os IDs
                    $tournament->qualifiedGamesList = $gameIds;
                    $tournament->qualifiedGamesIdentifiers = $gameIds;
                }
            }
            
            // Verificar se o usuário atual tem apostas nos jogos deste torneio
            $hasUserBets = false;
            if ($user && !empty($tournament->qualifiedGamesIdentifiers)) {
                $betsQuery = \App\Models\GameHistory::where('user_id', $user->id)
                    ->where('action', 'loss')
                    ->where('created_at', '>=', $tournament->start_date)
                    ->where('created_at', '<=', now())
                    ->whereIn('game', $tournament->qualifiedGamesIdentifiers);
                
                $hasUserBets = $betsQuery->exists();
                $userBetsAmount = $betsQuery->sum('amount');
                
                $tournament->userHasBets = $hasUserBets;
                $tournament->userBetsAmount = $userBetsAmount;
            }

            // Buscar top 5 jogadores
            $tournament->topPlayersList = $tournament->topPlayers(5);
            
            // Verificar se há jogadores reais participando
            $hasRealPlayers = $tournament->players()
                ->where('is_random_player', false)
                ->where('points', '>', 0)
                ->exists();
                
            $tournament->hasRealPlayers = $hasRealPlayers;
        }

        return view('vip.torneios', compact('ranking', 'activeTournaments'));
    }

    /**
     * Mostrar detalhes de um torneio específico
     *
     * @param int $id ID do torneio
     * @return \Illuminate\View\View
     */
    public function tournamentDetails($id)
    {
        $user = Auth::user();

        // Definir um ranking padrão caso a função getRanking() retorne null
        $ranking = $user ? ($user->getRanking() ?? [
            'level' => 1,
            'name' => 'Bronze',
            'image' => 'img/ranking/1.png',
            'current_deposit' => 0,
            'next_level' => 2,
            'next_level_deposit' => 200,
            'progress' => 0,
            'has_reward' => false,
            'reward_id' => null
        ]) : [
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

        // Buscar o torneio pelo ID
        $tournament = \App\Models\Tournament::find($id);

        if (!$tournament) {
            return redirect()->route('vip.tournaments')
                ->with('error', 'Torneio não encontrado.');
        }

        // Adicionar contagem de jogadores
        $tournament->playerCount = $tournament->getPlayerCount();

        // Calcular tempo restante
        $tournament->remainingTime = $tournament->getRemainingTimeFormatted();
        
        // Formatar tempo restante como texto para o template
        $remainingTimeObj = $tournament->remainingTime;
        $tournament->remaining_text = sprintf(
            "%02dh : %02dm : %02ds",
            $remainingTimeObj['hours'],
            $remainingTimeObj['minutes'],
            $remainingTimeObj['seconds']
        );
        
        // Buscar informações sobre jogos qualificados se disponíveis
        if (!empty($tournament->qualified_games)) {
            try {
                // Tentar obter as informações completas dos jogos a partir dos IDs usando a nova estrutura consolidada
                $gameIds = explode(',', $tournament->qualified_games);
                $qualifiedGames = \DB::table('games_api')
                    ->whereIn('games_api.id', $gameIds)
                    ->where('games_api.status', 1)
                    ->get(['games_api.id', 'games_api.name', 'games_api.slug']);
                
                $gameNames = [];
                $gameIdentifiers = [];
                
                foreach ($qualifiedGames as $game) {
                    $gameNames[] = $game->name;
                    
                    // Usar o slug diretamente da tabela consolidada games_api
                    $gameIdentifiers[] = $game->slug;
                }
                
                $tournament->qualifiedGamesList = $gameNames;
                $tournament->qualifiedGamesIdentifiers = $gameIdentifiers;
            } catch (\Exception $e) {
                // Em caso de erro, apenas use os IDs
                $tournament->qualifiedGamesList = $gameIds;
                $tournament->qualifiedGamesIdentifiers = $gameIds;
            }
        }
        
        // Verificar se o usuário atual tem apostas nos jogos deste torneio
        $hasUserBets = false;
        $userBetsAmount = 0;
        $userPointsEstimate = 0;
        
        if ($user && !empty($tournament->qualifiedGamesIdentifiers)) {
            $betsQuery = \App\Models\GameHistory::where('user_id', $user->id)
                ->where('action', 'loss')
                ->where('created_at', '>=', $tournament->start_date)
                ->where('created_at', '<=', now())
                ->whereIn('game', $tournament->qualifiedGamesIdentifiers);
            
            $hasUserBets = $betsQuery->exists();
            $userBetsAmount = $betsQuery->sum('amount');
            
            // Calcular estimativa de pontos baseado no método de cálculo do torneio
            $pointsMultiplier = $tournament->points_multiplier ?? 100;
            
            switch ($tournament->points_calculation_type ?? 'bet_amount') {
                case 'bet_amount':
                    $userPointsEstimate = $userBetsAmount * $pointsMultiplier;
                    break;
                    
                case 'win_amount':
                    $winQuery = clone $betsQuery;
                    $winQuery->where('action', 'win');
                    $totalWinAmount = $winQuery->sum('amount');
                    $userPointsEstimate = $totalWinAmount * $pointsMultiplier;
                    break;
                    
                case 'bet_count':
                    $betCount = $betsQuery->count();
                    $userPointsEstimate = $betCount * $pointsMultiplier;
                    break;
                    
                default:
                    $userPointsEstimate = $userBetsAmount * 100; // Padrão: R$0,01 = 1 ponto
            }
            
            $tournament->userHasBets = $hasUserBets;
            $tournament->userBetsAmount = $userBetsAmount;
            $tournament->userPointsEstimate = $userPointsEstimate;
            
            // Se o usuário tem apostas mas não está participando do torneio,
            // registrar automaticamente o usuário no torneio
            if ($hasUserBets && $userBetsAmount > 0) {
                $isUserRegistered = $tournament->isUserParticipating($user->id);
                
                if (!$isUserRegistered) {
                    // Adicionar o usuário ao torneio
                    $tournament->players()->updateOrCreate(
                        ['tournament_id' => $tournament->id, 'user_id' => $user->id],
                        [
                            'joined_at' => now(),
                            'last_active_at' => now(),
                            'is_random_player' => false
                        ]
                    );
                    
                    // Calcular os pontos imediatamente
                    $minBetAmount = $tournament->min_bet_amount ?? 0.40;
                    
                    // Criar uma consulta para buscar apostas do jogador
                    $pointsQuery = clone $betsQuery;
                    $pointsQuery->where('amount', '>=', $minBetAmount);
                    
                    // Calcular pontos conforme o tipo de cálculo
                    $points = 0;
                    switch ($tournament->points_calculation_type ?? 'bet_amount') {
                        case 'bet_amount':
                            $totalAmount = $pointsQuery->sum('amount');
                            $points = $totalAmount * $pointsMultiplier;
                            break;
                            
                        case 'win_amount':
                            $winQuery = clone $pointsQuery;
                            $winQuery->where('action', 'win');
                            $totalWinAmount = $winQuery->sum('amount');
                            $points = $totalWinAmount * $pointsMultiplier;
                            break;
                            
                        case 'bet_count':
                            $betCount = $pointsQuery->count();
                            $points = $betCount * $pointsMultiplier;
                            break;
                            
                        default:
                            $totalAmount = $pointsQuery->sum('amount');
                            $points = $totalAmount * 100;
                    }
                    
                    // Atualizar os pontos do jogador
                    $player = $tournament->players()->where('user_id', $user->id)->first();
                    if ($player) {
                        $player->points = $points;
                        $player->points_calculation_method = $tournament->points_calculation_type ?? 'bet_amount';
                        $player->last_points_update = now();
                        $player->save();
                    }
                }
            }
        }

        // Verificar se o usuário está participando
        $isParticipating = false;
        if ($user) {
            $isParticipating = $tournament->isUserParticipating($user->id);
        }

        // Buscar todos os jogadores para exibir o ranking completo
        // Recarregar após possível registro do usuário atual
        $tournament->topPlayersList = $tournament->topPlayers(20);
        
        // Também atribuir a playersList para compatibilidade com o template
        $tournament->playersList = $tournament->players()
            ->with('user')
            ->orderBy('points', 'desc')
            ->get();

        // Exibir a página de detalhes do torneio
        return view('vip.viewtorneios', compact('ranking', 'tournament', 'isParticipating'));
    }

    /**
     * Mostrar a página de missões
     *
     * @return \Illuminate\View\View
     */
    public function missions()
    {
        $user = Auth::user();

        // Definir um ranking padrão caso a função getRanking() retorne null
        $ranking = $user ? ($user->getRanking() ?? [
            'level' => 1,
            'name' => 'Bronze',
            'image' => 'img/ranking/1.png',
            'current_deposit' => 0,
            'next_level' => 2,
            'next_level_deposit' => 200,
            'progress' => 0,
            'has_reward' => false,
            'reward_id' => null
        ]) : [
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

        // Buscar missões ativas com filtro de ranking
        $missionsQuery = \App\Models\Mission::where('status', 1);
        
        // Aplicar filtro de ranking apenas se o usuário estiver autenticado
        if ($user) {
            $userRankingLevel = $ranking['level'];
            
            $missionsQuery->where(function ($query) use ($userRankingLevel) {
                // Missões para todos os rankings
                $query->where('ranking_type', 'all')
                      // Missões para ranking mínimo (usuário igual ou superior)
                      ->orWhere(function ($q) use ($userRankingLevel) {
                          $q->where('ranking_type', 'minimum')
                            ->where('target_ranking_level', '<=', $userRankingLevel);
                      })
                      // Missões para ranking específico (usuário exatamente no nível)
                      ->orWhere(function ($q) use ($userRankingLevel) {
                          $q->where('ranking_type', 'specific')
                            ->where('target_ranking_level', $userRankingLevel);
                      });
            });
        } else {
            // Para usuários não autenticados, mostrar apenas missões para todos
            $missionsQuery->where('ranking_type', 'all');
        }
        
        $missions = $missionsQuery->get();

        // Para cada missão, adicionar informações de progresso do usuário
        if ($user) {
            foreach ($missions as &$mission) {
                // Buscar o progresso do usuário para esta missão (se existir)
                $completion = \App\Models\MissionCompletion::where('mission_id', $mission->id)
                    ->where('user_id', $user->id)
                    ->first();

                // Definir valores padrão
                // Consideramos completed = true se completed_at não for nulo
                $completed = $completion && $completion->completed_at ? true : false;
                $reward_claimed = $completion && $completion->reward_claimed ? true : false;
                $accepted = $completion && $completion->accepted_at ? true : false;

                // Buscar o valor total apostado pelo usuário neste jogo específico APÓS ter aceitado a missão
                $gameInfo = $mission->game()->first();

                // Buscar o slug diretamente da tabela consolidada games_api
                $gameSlug = $gameInfo ? $gameInfo->slug : null;
                $gameSlugs = $gameSlug ? [$gameSlug] : [];

                // Construir query considerando todos os slugs possíveis
                $betsQuery = GameHistory::where('user_id', $user->id);
                
                if (!empty($gameSlugs)) {
                    $betsQuery->whereIn('game', $gameSlugs);
                } else {
                    // Fallback para ID do jogo se não houver slugs
                    $betsQuery->where('game', $gameInfo->id);
                }

                // Se a missão foi aceita, filtrar apostas apenas após a data de aceitação
                if ($completion && $completion->accepted_at) {
                    $betsQuery->where('created_at', '>=', $completion->accepted_at);
                }

                $betsAmount = $betsQuery->where('action', 'loss')->sum('amount');

                // Calcular o progresso baseado no valor real apostado
                $current_value = $betsAmount;
                $progress = $accepted ? min(($current_value / $mission->target_amount) * 100, 100) : 0;

                // Atualizar o status de completude baseado no progresso atual
                if ($accepted && !$completed && $progress >= 100) {
                    // Se o usuário já atingiu o valor alvo mas a missão não está marcada como completa
                    $completed = true;

                    // Atualizar registro de conclusão
                    if ($completion) {
                        $completion->completed_at = now();
                        $completion->save();
                    }
                }

                $mission->current_value = $current_value;
                $mission->progress = $progress;
                $mission->completed = $completed;
                $mission->reward_claimed = $reward_claimed;
                $mission->accepted = $accepted;

                // Se a missão estiver associada a um jogo, obter o slug do jogo
                if ($mission->game_id) {
                    $game = $mission->game()->first();
                    $mission->game_slug = $game ? $game->slug : '';
                } else {
                    $mission->game_slug = '';
                }
            }
        } else {
            // Para usuários não autenticados, definir valores vazios
            foreach ($missions as &$mission) {
                $mission->current_value = 0;
                $mission->progress = 0;
                $mission->completed = false;
                $mission->reward_claimed = false;
                $mission->accepted = false;
                $mission->game_slug = '';
            }
        }

        return view('vip.missoes', compact('ranking', 'missions'));
    }

    /**
     * Mostrar a loja VIP
     *
     * @return \Illuminate\View\View
     */
    public function store()
    {
        $user = Auth::user();

        // Definir um ranking padrão caso a função getRanking() retorne null
        $ranking = $user ? ($user->getRanking() ?? [
            'level' => 1,
            'name' => 'Bronze',
            'image' => 'img/ranking/1.png',
            'current_deposit' => 0,
            'next_level' => 2,
            'next_level_deposit' => 200,
            'progress' => 0,
            'has_reward' => false,
            'reward_id' => null
        ]) : [
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

        // Aqui você pode adicionar lógica para buscar produtos disponíveis na loja
        $products = [];

        return view('vip.store', compact('ranking', 'products'));
    }

    /**
     * Mostrar a página de mini-games
     *
     * @return \Illuminate\View\View
     */
    public function miniGames()
    {
        $user = Auth::user();

        // Definir um ranking padrão caso a função getRanking() retorne null
        $ranking = $user ? ($user->getRanking() ?? [
            'level' => 1,
            'name' => 'Bronze',
            'image' => 'img/ranking/1.png',
            'current_deposit' => 0,
            'next_level' => 2,
            'next_level_deposit' => 200,
            'progress' => 0,
            'has_reward' => false,
            'reward_id' => null
        ]) : [
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

        // Aqui você pode adicionar lógica para buscar mini-games disponíveis
        $games = [];

        return view('vip.mini-games', compact('ranking', 'games'));
    }

    /**
     * Obter o progresso atualizado das missões do usuário
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMissionsProgress()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não autenticado'
            ]);
        }

        try {
            // Obter ranking do usuário
            $ranking = $user->getRanking() ?? [
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

            // Buscar missões ativas com filtro de ranking
            $missionsQuery = \App\Models\Mission::where('status', 1);
            
            $userRankingLevel = $ranking['level'];
            
            $missionsQuery->where(function ($query) use ($userRankingLevel) {
                // Missões para todos os rankings
                $query->where('ranking_type', 'all')
                      // Missões para ranking mínimo (usuário igual ou superior)
                      ->orWhere(function ($q) use ($userRankingLevel) {
                          $q->where('ranking_type', 'minimum')
                            ->where('target_ranking_level', '<=', $userRankingLevel);
                      })
                      // Missões para ranking específico (usuário exatamente no nível)
                      ->orWhere(function ($q) use ($userRankingLevel) {
                          $q->where('ranking_type', 'specific')
                            ->where('target_ranking_level', $userRankingLevel);
                      });
            });

            // Executar a consulta
            $missionsData = $missionsQuery->get();

            // Formatar as missões para a resposta JSON
            $missions = $missionsData->map(function ($mission) use ($user) {
                // Buscar o progresso do usuário para esta missão
                $completion = \App\Models\MissionCompletion::where('mission_id', $mission->id)
                    ->where('user_id', $user->id)
                    ->first();

                // Valores padrão se não houver registro de conclusão
                // Consideramos completed = true se completed_at não for nulo
                $completed = $completion && $completion->completed_at ? true : false;
                $rewardClaimed = $completion && $completion->reward_claimed ? true : false;
                $accepted = $completion && $completion->accepted_at ? true : false;

                // Buscar o valor total apostado pelo usuário neste jogo específico
                $gameInfo = $mission->game()->first();

                // Buscar o slug diretamente da tabela consolidada games_api
                $gameSlug = $gameInfo ? $gameInfo->slug : null;
                $gameSlugs = $gameSlug ? [$gameSlug] : [];

                // Construir query considerando todos os slugs possíveis
                $query = GameHistory::where('user_id', $user->id);
                
                if (!empty($gameSlugs)) {
                    $query->whereIn('game', $gameSlugs);
                } else {
                    // Fallback para ID do jogo se não houver slugs
                    $query->where('game', $gameInfo->id);
                }

                // Se a missão foi aceita, filtrar apostas apenas após a data de aceitação
                if ($completion && $completion->accepted_at) {
                    $query->where('created_at', '>=', $completion->accepted_at);
                }

                // Executar a consulta e obter o valor total apostado
                $betsAmount = $query->where('action', 'loss')->sum('amount');

                // Calcular o progresso baseado no valor real apostado
                $currentValue = $betsAmount;
                $progress = $accepted ? min(($currentValue / $mission->target_amount) * 100, 100) : 0;

                // Atualizar o status de completude baseado no progresso atual
                if ($accepted && !$completed && $progress >= 100) {
                    // Se o usuário já atingiu o valor alvo mas a missão não está marcada como completa
                    $completed = true;

                    // Atualizar ou criar registro de conclusão
                    if (!$completion) {
                        $completion = new \App\Models\MissionCompletion();
                        $completion->user_id = $user->id;
                        $completion->mission_id = $mission->id;
                        $completion->accepted_at = now();
                        $completion->completed_at = now();
                        $completion->save();
                    } elseif (!$completion->completed_at) {
                        $completion->completed_at = now();
                        $completion->save();
                    }
                }

                // Se a missão estiver associada a um jogo, obter o slug do jogo
                $gameSlug = '';
                if ($mission->game_id) {
                    $game = $mission->game()->first();
                    $gameSlug = $game ? $game->slug : '';
                }

                // Retornar os dados formatados
                return [
                    'id' => $mission->id,
                    'title' => $mission->title,
                    'description' => $mission->description,
                    'image' => $mission->image,
                    'current_value' => ($accepted) ? $currentValue : 0,
                    'target_value' => $mission->target_amount,
                    'reward_koins' => $mission->reward_koins,
                    'reward_balance' => $mission->reward_balance,
                    'reward_balance_bonus' => $mission->reward_balance_bonus,
                    'reward_free_spins' => $mission->reward_free_spins,
                    'progress' => $progress,
                    'completed' => $completed,
                    'reward_claimed' => $rewardClaimed,
                    'accepted' => $accepted,
                    'game_slug' => $gameSlug,
                    'game_id' => $mission->game_id
                ];
            });

            return response()->json([
                'success' => true,
                'missions' => $missions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar progresso das missões: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Aceitar uma missão
     *
     * @param int $missionId ID da missão a ser aceita
     * @return \Illuminate\Http\JsonResponse
     */
    public function acceptMission($missionId)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não autenticado'
            ], 401);
        }

        // Verificar se o ID da missão é válido
        if (!$missionId || !is_numeric($missionId)) {
            return response()->json([
                'success' => false,
                'message' => 'ID de missão inválido'
            ], 400);
        }

        try {
            // Verificar se a missão existe
            $mission = \App\Models\Mission::find($missionId);

            if (!$mission) {
                return response()->json([
                    'success' => false,
                    'message' => 'Missão não encontrada'
                ], 404);
            }

            // Verificar se o usuário tem acesso à missão baseado no ranking
            $ranking = $user->getRanking() ?? ['level' => 1];
            $userRankingLevel = $ranking['level'];

            $hasAccess = false;
            switch ($mission->ranking_type) {
                case 'all':
                    $hasAccess = true;
                    break;
                case 'minimum':
                    $hasAccess = $userRankingLevel >= $mission->target_ranking_level;
                    break;
                case 'specific':
                    $hasAccess = $userRankingLevel == $mission->target_ranking_level;
                    break;
            }

            if (!$hasAccess) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não tem o ranking necessário para acessar esta missão'
                ], 403);
            }

            // Verificar se o usuário já aceitou esta missão
            $existingCompletion = \App\Models\MissionCompletion::where('mission_id', $missionId)
                ->where('user_id', $user->id)
                ->first();

            if ($existingCompletion) {
                // Se já aceitou e completou a missão, retornar erro
                if ($existingCompletion->completed_at) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Você já completou esta missão anteriormente'
                    ], 400);
                }

                // Se já aceitou a missão, atualizar a data de aceitação para reiniciar a contagem
                $existingCompletion->accepted_at = now();
                $existingCompletion->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Missão reiniciada com sucesso! Agora suas apostas serão contabilizadas para esta missão.',
                    'mission' => [
                        'id' => $mission->id,
                        'title' => $mission->title,
                        'target_amount' => $mission->target_amount
                    ]
                ]);
            }

            // Criar um novo registro de aceitação de missão
            $completion = new \App\Models\MissionCompletion();
            $completion->user_id = $user->id;
            $completion->mission_id = $missionId;
            $completion->accepted_at = now();
            $completion->reward_claimed = 0; // Inicializa com recompensa não resgatada
            $completion->save();

            // Retornar sucesso
            return response()->json([
                'success' => true,
                'message' => 'Missão aceita com sucesso! Agora suas apostas serão contabilizadas para esta missão.',
                'mission' => [
                    'id' => $mission->id,
                    'title' => $mission->title,
                    'target_amount' => $mission->target_amount
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao aceitar missão: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Resgatar recompensa de uma missão
     *
     * @param int $missionId ID da missão a ser resgatada
     * @return \Illuminate\Http\JsonResponse
     */
    public function claimMissionReward($missionId)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não autenticado'
            ], 401);
        }

        // Verificar se o ID da missão é válido
        if (!$missionId || !is_numeric($missionId)) {
            return response()->json([
                'success' => false,
                'message' => 'ID de missão inválido'
            ], 400);
        }

        try {
            // Verificar se a missão existe
            $mission = \App\Models\Mission::find($missionId);

            if (!$mission) {
                return response()->json([
                    'success' => false,
                    'message' => 'Missão não encontrada'
                ], 404);
            }

            // Verificar se o usuário tem acesso à missão baseado no ranking
            $ranking = $user->getRanking() ?? ['level' => 1];
            $userRankingLevel = $ranking['level'];

            $hasAccess = false;
            switch ($mission->ranking_type) {
                case 'all':
                    $hasAccess = true;
                    break;
                case 'minimum':
                    $hasAccess = $userRankingLevel >= $mission->target_ranking_level;
                    break;
                case 'specific':
                    $hasAccess = $userRankingLevel == $mission->target_ranking_level;
                    break;
            }

            if (!$hasAccess) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não tem o ranking necessário para acessar esta missão'
                ], 403);
            }

            // Verificar se o usuário já tem um registro para esta missão
            $completion = \App\Models\MissionCompletion::where('mission_id', $missionId)
                ->where('user_id', $user->id)
                ->first();

            // Se não existir, criar um novo registro
            if (!$completion) {
                $completion = new \App\Models\MissionCompletion();
                $completion->user_id = $user->id;
                $completion->mission_id = $missionId;
                $completion->completed_at = now();
                $completion->reward_claimed = true;
                $completion->claimed_at = now();
                $completion->save();
            } else {
                // Se já existir, verificar se a recompensa já foi resgatada
                if ($completion->reward_claimed) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Você já resgatou a recompensa desta missão'
                    ], 400);
                }

                // Atualizar o registro existente
                $completion->reward_claimed = true;
                $completion->claimed_at = now();
                $completion->save();
            }

            $wallet = $user->wallet;

            if (!$wallet) {
                return response()->json([
                    'success' => false,
                    'message' => 'Carteira do usuário não encontrada'
                ], 500);
            }

            // Inicializa mensagem de recompensa
            $rewardMessage = 'Parabéns! Você recebeu ';
            $rewardItems = [];

            // Adicionar recompensa de koins ao usuário
            if ($mission->reward_koins > 0) {
                $wallet->coin = ($wallet->coin ?? 0) + $mission->reward_koins;
                $rewardItems[] = $mission->reward_koins . ' moedas';
            }

            // Adicionar recompensa de saldo real ao usuário
            if ($mission->reward_balance > 0) {
                $wallet->balance = ($wallet->balance ?? 0) + $mission->reward_balance;
                $rewardItems[] = 'R$ ' . number_format($mission->reward_balance, 2, ',', '.') . ' de saldo real';
            }

            // Adicionar recompensa de saldo bônus ao usuário
            if ($mission->reward_balance_bonus > 0) {
                $wallet->balance_bonus = ($wallet->balance_bonus ?? 0) + $mission->reward_balance_bonus;
                $rewardItems[] = 'R$ ' . number_format($mission->reward_balance_bonus, 2, ',', '.') . ' de saldo bônus';
            }

            // Adicionar recompensa de rodadas grátis ao usuário
            if ($mission->reward_free_spins > 0) {
                $wallet->free_spins = ($wallet->free_spins ?? 0) + $mission->reward_free_spins;
                $rewardItems[] = $mission->reward_free_spins . ' rodadas grátis';
            }

            // Salvar a carteira atualizada
            $wallet->save();

            // Finalizar mensagem
            $successMessage = $rewardMessage . implode(', ', $rewardItems) . ' pela conclusão da missão!';

            // Retornar sucesso
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'new_balance' => $wallet->coin ?? 0,
                'new_real_balance' => $wallet->balance ?? 0,
                'new_bonus_balance' => $wallet->balance_bonus ?? 0,
                'new_free_spins' => $wallet->free_spins ?? 0,
                'completed' => true,
                'reward_claimed' => true,
                'progress' => 100,
                'current_value' => $mission->target_amount,
                'target_value' => $mission->target_amount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao resgatar recompensa: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar apostas do usuário em um jogo específico
     */
    public function checkGameBets(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não autenticado'
            ], 401);
        }

        // Validar parâmetros
        $request->validate([
            'game_id' => 'required|numeric',
            'date' => 'nullable|date'
        ]);

        $gameId = $request->input('game_id');
        $fromDate = $request->input('date') ? date('Y-m-d H:i:s', strtotime($request->input('date'))) : null;

        try {
            // Buscar o jogo na tabela games_api
            $gameInfo = \DB::table('games_api')->where('id', $gameId)->first();

            if (!$gameInfo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jogo não encontrado'
                ], 404);
            }

            // Buscar o slug diretamente da tabela consolidada games_api
            $gameSlug = $gameInfo->slug ?? null;
            $gameSlugs = $gameSlug ? [$gameSlug] : [];

            // Construir a consulta para somar apostas
            $query = \DB::table('games_history')
                ->where('user_id', $user->id)
                ->where(function($q) use ($gameInfo, $gameSlugs) {
                    // Verificar pelos slugs da nova tabela
                    if (!empty($gameSlugs)) {
                        $q->whereIn('game', $gameSlugs);
                    }

                    // Verificar pelo nome do jogo
                    if (!empty($gameInfo->name)) {
                        $q->orWhere('game', $gameInfo->name);
                    }

                    // Verificar pelo ID do jogo
                    $q->orWhere('game', $gameInfo->id)
                        ->orWhere('game', (string)$gameInfo->id);
                });

            // Filtrar por data, se fornecida
            if ($fromDate) {
                $query->where('created_at', '>=', $fromDate);
            }

            // Somar os valores
            $totalBet = $query->where('action', 'loss')->sum('amount');

            return response()->json([
                'success' => true,
                'game_info' => [
                    'id' => $gameInfo->id,
                    'name' => $gameInfo->name
                ],
                'from_date' => $fromDate,
                'total_bet' => $totalBet,
                'game_slugs_found' => $gameSlugs
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao verificar apostas: ' . $e->getMessage()
            ], 500);
        }
    }
}
