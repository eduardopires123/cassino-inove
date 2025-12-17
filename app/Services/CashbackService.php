<?php

namespace App\Services;

use App\Models\CashbackSetting;
use App\Models\GameHistory;
use App\Models\SportBetSummary;
use App\Models\User;
use App\Models\UserCashback;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CashbackService
{
    /**
     * Processar as perdas do usuário e calcular cashback
     * 
     * @param User $user
     * @param string $type sports|virtual|all
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return UserCashback|null
     */
    public function processCashback(User $user, $type = 'all', Carbon $startDate = null, Carbon $endDate = null)
    {
        // Define período padrão se não for especificado (semana atual)
        if (!$startDate) {
            $startDate = Carbon::now()->startOfWeek();
        }
        
        if (!$endDate) {
            $endDate = Carbon::now()->endOfWeek();
        }

        // Primeiro verificamos se há um cashback específico para o nível VIP do usuário
        $userRanking = $user->getRanking();
        $userVipLevel = $userRanking ? $userRanking['level'] : 1;

        // Busca configuração de cashback específica para o nível VIP
        $cashbackSetting = CashbackSetting::where('active', true)
            ->where('vip_level', $userVipLevel)
            ->where(function($query) use ($type) {
                $query->where('type', $type)
                    ->orWhere('type', 'all');
            })
            ->first();

        // Se não encontrar configuração específica para o nível VIP, busca uma configuração global
        if (!$cashbackSetting) {
            $cashbackSetting = CashbackSetting::where('active', true)
                ->where('is_global', true)
                ->where(function($query) use ($type) {
                    $query->where('type', $type)
                        ->orWhere('type', 'all');
                })
                ->first();
        }

        if (!$cashbackSetting) {
            return null;
        }

        // Calcula total de perdas do período
        $totalLoss = $this->calculateTotalLoss($user->id, $type, $startDate, $endDate);
        
        // Se não houver perdas acima do mínimo, retorna null
        if ($totalLoss < $cashbackSetting->min_loss) {
            return null;
        }

        // Calcula valor do cashback
        $cashbackAmount = $cashbackSetting->calculateCashback($totalLoss);
        
        if ($cashbackAmount <= 0) {
            return null;
        }

        // Criar descrição identificando se é um cashback VIP ou global
        $description = '';
        if ($cashbackSetting->vip_level) {
            $description = "Cashback VIP Nível {$cashbackSetting->vip_level} - {$cashbackSetting->percentage}%";
        } else {
            $description = "Cashback global - {$cashbackSetting->percentage}%";
        }

        // Cria registro de cashback
        $userCashback = UserCashback::create([
            'user_id' => $user->id,
            'cashback_setting_id' => $cashbackSetting->id,
            'total_loss' => $totalLoss,
            'cashback_amount' => $cashbackAmount,
            'percentage_applied' => $cashbackSetting->percentage,
            'type' => $type,
            'status' => 'pending',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'expires_at' => Carbon::now()->addDays($cashbackSetting->expiry_days),
            'notes' => $description
        ]);

        // Sempre aplicar o cashback automaticamente
        $applied = $userCashback->apply();
        
        if ($applied) {
            \Illuminate\Support\Facades\Log::info("Cashback para usuário {$user->id} aplicado automaticamente: R$ " . number_format($cashbackAmount, 2, ',', '.'));
        } else {
            \Illuminate\Support\Facades\Log::warning("Falha ao aplicar cashback automaticamente para usuário {$user->id}");
        }

        return $userCashback;
    }

    /**
     * Calcula o total de perdas do usuário em um período
     * 
     * @param int $userId
     * @param string $type sports|virtual|all
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return float
     */
    public function calculateTotalLoss($userId, $type = 'all', Carbon $startDate, Carbon $endDate)
    {
        $totalLoss = 0;
        
        // Se buscar todos os tipos ou apenas jogos virtuais
        if ($type === 'all' || $type === 'virtual') {
            // Calcula perdas em jogos virtuais
            $virtualLoss = GameHistory::where('user_id', $userId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('action', 'loss')
                ->where('provider', '!=', 'sports')
                ->sum('amount');
                
            $totalLoss += $virtualLoss;
        }
        
        // Se buscar todos os tipos ou apenas esportes
        if ($type === 'all' || $type === 'sports') {
            // Calcula perdas em apostas esportivas - usando a tabela sportbetsummary
            // Primeiro identificamos as apostas perdidas (contempla ambos provedores)
            $lostBets = SportBetSummary::where('user_id', $userId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where(function($query) {
                    $query->where('operation', 'lose')         // Digitain
                          ->orWhere('status', 'lost');         // BetBy
                })
                ->select('transactionId')
                ->distinct()
                ->get()
                ->pluck('transactionId');
                
            $sportsLoss = 0;
            
            if (!$lostBets->isEmpty()) {
                // Agora buscamos os valores das apostas originais (debit) para essas apostas perdidas
                $sportsLoss = DB::table('sportbetsummary as bet')
                    ->join('sportbetsummary as lose', function($join) {
                        $join->on('lose.transactionId', '=', 'bet.transactionId')
                            ->where(function($query) {
                                $query->where('lose.operation', '=', 'lose')       // Digitain
                                      ->orWhere('lose.status', '=', 'lost');       // BetBy
                            });
                    })
                    ->where('bet.user_id', $userId)
                    ->whereIn('bet.transactionId', $lostBets)
                    ->where('bet.operation', 'debit')
                    ->sum('bet.amount');
            }
                
            $totalLoss += $sportsLoss;
        }
        
        return $totalLoss;
    }

    /**
     * Obter histórico de apostas esportivas perdidas para um usuário
     * 
     * @param int $userId
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return \Illuminate\Support\Collection
     */
    public function getSportsLostBets($userId, Carbon $startDate, Carbon $endDate)
    {
        // Primeiro identificamos as apostas perdidas (contempla ambos provedores)
        $lostBets = SportBetSummary::where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where(function($query) {
                $query->where('operation', 'lose')         // Digitain
                      ->orWhere('status', 'lost');         // BetBy
            })
            ->select('transactionId')
            ->distinct()
            ->get()
            ->pluck('transactionId');
        
        if ($lostBets->isEmpty()) {
            return collect([]);
        }
        
        // Agora buscamos os dados das apostas originais (debit) para essas apostas perdidas
        return DB::table('sportbetsummary as lose')
            ->join('sportbetsummary as bet', function($join) {
                $join->on('bet.transactionId', '=', 'lose.transactionId')
                    ->where('bet.operation', '=', 'debit');
            })
            ->where('lose.user_id', $userId)
            ->whereIn('lose.transactionId', $lostBets)
            ->where(function($query) {
                $query->where('lose.operation', 'lose')        // Digitain
                      ->orWhere('lose.status', 'lost');        // BetBy
            })
            ->select(
                'bet.transactionId',
                'bet.created_at',
                'bet.amount as bet_amount',  // Valor apostado
                'lose.amount as lose_amount',  // Valor da perda
                'bet.betslip'
            )
            ->orderBy('bet.created_at', 'desc')
            ->get();
    }

    /**
     * Obter histórico detalhado de perdas por tipo
     * 
     * @param int $userId
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    public function getDetailedLossBreakdown($userId, Carbon $startDate, Carbon $endDate)
    {
        // Perdas em jogos virtuais por provedor
        $virtualLossesByProvider = GameHistory::where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('action', 'loss')
            ->where('provider', '!=', 'sports')
            ->select('provider', DB::raw('SUM(amount) as total_loss'))
            ->groupBy('provider')
            ->get()
            ->pluck('total_loss', 'provider')
            ->toArray();
            
        // Total de perdas em jogos virtuais
        $totalVirtualLoss = array_sum($virtualLossesByProvider);
        
        // Perdas em esportes - Buscamos as apostas perdidas e usamos o valor da aposta original (contempla ambos provedores)
        $lostBets = SportBetSummary::where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where(function($query) {
                $query->where('operation', 'lose')         // Digitain
                      ->orWhere('status', 'lost');         // BetBy
            })
            ->select('transactionId')
            ->distinct()
            ->get()
            ->pluck('transactionId');
            
        $sportsLoss = 0;
        
        if (!$lostBets->isEmpty()) {
            $sportsLoss = DB::table('sportbetsummary as bet')
                ->join('sportbetsummary as lose', function($join) {
                    $join->on('lose.transactionId', '=', 'bet.transactionId')
                        ->where(function($query) {
                            $query->where('lose.operation', '=', 'lose')       // Digitain
                                  ->orWhere('lose.status', '=', 'lost');       // BetBy
                        });
                })
                ->where('bet.user_id', $userId)
                ->whereIn('bet.transactionId', $lostBets)
                ->where('bet.operation', 'debit')
                ->sum('bet.amount');
        }
        
        return [
            'virtual' => [
                'total' => $totalVirtualLoss,
                'by_provider' => $virtualLossesByProvider
            ],
            'sports' => [
                'total' => $sportsLoss
            ],
            'grand_total' => $totalVirtualLoss + $sportsLoss
        ];
    }

    /**
     * Aplicar cashback pendente
     * 
     * @param int $cashbackId
     * @return bool
     */
    public function applyCashback($cashbackId)
    {
        $cashback = UserCashback::find($cashbackId);
        
        if (!$cashback || $cashback->status !== 'pending') {
            return false;
        }
        
        return $cashback->apply();
    }

    /**
     * Gerar relatório de cashbacks por período
     * 
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    public function generateCashbackReport(Carbon $startDate, Carbon $endDate)
    {
        $report = [
            'total_cashbacks' => 0,
            'total_amount' => 0,
            'by_type' => [
                'sports' => ['count' => 0, 'amount' => 0],
                'virtual' => ['count' => 0, 'amount' => 0],
                'all' => ['count' => 0, 'amount' => 0]
            ],
            'by_status' => [
                'pending' => ['count' => 0, 'amount' => 0],
                'credited' => ['count' => 0, 'amount' => 0],
                'expired' => ['count' => 0, 'amount' => 0]
            ]
        ];
        
        $cashbacks = UserCashback::whereBetween('created_at', [$startDate, $endDate])->get();
        
        foreach ($cashbacks as $cashback) {
            $report['total_cashbacks']++;
            $report['total_amount'] += $cashback->cashback_amount;
            
            // Por tipo
            $report['by_type'][$cashback->type]['count']++;
            $report['by_type'][$cashback->type]['amount'] += $cashback->cashback_amount;
            
            // Por status
            $report['by_status'][$cashback->status]['count']++;
            $report['by_status'][$cashback->status]['amount'] += $cashback->cashback_amount;
        }
        
        return $report;
    }

    /**
     * Gerar relatório de cashbacks por período com detalhamento
     * 
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    public function generateDetailedCashbackReport(Carbon $startDate, Carbon $endDate)
    {
        $report = $this->generateCashbackReport($startDate, $endDate);
        
        // Adicionar informações sobre origem das perdas
        $lossOrigins = [
            'sports' => 0,
            'virtual' => 0
        ];
        
        // Calcular totais de perdas por tipo
        $cashbacks = UserCashback::whereBetween('created_at', [$startDate, $endDate])->get();
        
        foreach ($cashbacks as $cashback) {
            if ($cashback->type === 'sports') {
                $lossOrigins['sports'] += $cashback->total_loss;
            } elseif ($cashback->type === 'virtual') {
                $lossOrigins['virtual'] += $cashback->total_loss;
            } elseif ($cashback->type === 'all') {
                // Para cashbacks do tipo 'all', precisamos consultar os detalhes
                $details = $this->getDetailedLossBreakdown(
                    $cashback->user_id, 
                    $cashback->start_date, 
                    $cashback->end_date
                );
                
                $lossOrigins['sports'] += $details['sports']['total'];
                $lossOrigins['virtual'] += $details['virtual']['total'];
            }
        }
        
        $report['loss_origins'] = $lossOrigins;
        
        return $report;
    }

    /**
     * Verificar e processar cashbacks automáticos para todos os usuários
     * 
     * @param string $type sports|virtual|all
     * @return array
     */
    public function processAutomaticCashbacks($type = 'all')
    {
        $results = [
            'processed' => 0,
            'amount' => 0,
            'errors' => 0,
            'global' => 0,
            'vip' => 0
        ];
        
        // Verifica se existem configurações globais ou por nível VIP ativas
        $hasSettings = CashbackSetting::where('active', true)
            ->where('auto_apply', true)
            ->where(function($query) use ($type) {
                $query->where('type', $type)
                    ->orWhere('type', 'all');
            })
            ->where(function($query) {
                $query->where('is_global', true)
                    ->orWhereNotNull('vip_level');
            })
            ->exists();
            
        if (!$hasSettings) {
            return $results;
        }
        
        $startDate = Carbon::now()->subWeek()->startOfWeek();
        $endDate = Carbon::now()->subWeek()->endOfWeek();
        
        // Busca usuários ativos que tiveram perdas no período
        $activeUserIds = collect();
        
        // Usuários com perdas em jogos virtuais
        if ($type === 'all' || $type === 'virtual') {
            $virtualLossUserIds = GameHistory::whereBetween('created_at', [$startDate, $endDate])
                ->where('action', 'loss')
                ->pluck('user_id')
                ->unique();
                
            $activeUserIds = $activeUserIds->merge($virtualLossUserIds);
        }
        
        // Usuários com perdas em apostas esportivas (contempla ambos provedores)
        if ($type === 'all' || $type === 'sports') {
            $sportsLossUserIds = SportBetSummary::whereBetween('created_at', [$startDate, $endDate])
                ->where(function($query) {
                    $query->where('operation', 'lose')         // Digitain
                          ->orWhere('status', 'lost');         // BetBy
                })
                ->pluck('user_id')
                ->unique();
                
            $activeUserIds = $activeUserIds->merge($sportsLossUserIds);
        }
        
        // Remover duplicatas
        $activeUserIds = $activeUserIds->unique();
            
        foreach ($activeUserIds as $userId) {
            try {
                $user = User::find($userId);
                
                if (!$user) {
                    continue;
                }
                
                $cashback = $this->processCashback($user, $type, $startDate, $endDate);
                
                if ($cashback) {
                    $results['processed']++;
                    $results['amount'] += $cashback->cashback_amount;
                    
                    // Incrementa contadores específicos por tipo de cashback
                    $cashbackSetting = CashbackSetting::find($cashback->cashback_setting_id);
                    if ($cashbackSetting) {
                        if ($cashbackSetting->is_global) {
                            $results['global']++;
                        } elseif ($cashbackSetting->vip_level) {
                            $results['vip']++;
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error('Erro ao processar cashback automático: ' . $e->getMessage());
                $results['errors']++;
            }
        }
        
        return $results;
    }

    /**
     * Verifica e processa cashbacks agendados que estão prontos para execução
     * 
     * @return array
     */
    public function processScheduledCashbacks()
    {
        $results = [
            'processed' => 0,
            'amount' => 0,
            'errors' => 0,
            'global' => 0,
            'vip' => 0,
            'settings_processed' => []
        ];
        
        // Verificar o momento atual para comparação precisa com agendamentos
        $now = now();
        
        // Buscar configurações ativas com agendamento ativo e data de próxima execução <= agora
        $settings = CashbackSetting::where('active', true)
            ->where('schedule_active', true)
            ->where(function($query) use ($now) {
                $query->whereNotNull('next_run_at')
                      ->where('next_run_at', '<=', $now);
            })
            ->get();
            
        if ($settings->isEmpty()) {
            Log::info("Nenhuma configuração de cashback agendada para execução neste momento: " . $now->format('Y-m-d H:i:s'));
            
            // Verificar próximos agendamentos para diagnóstico
            $nextScheduled = CashbackSetting::where('active', true)
                ->where('schedule_active', true)
                ->whereNotNull('next_run_at')
                ->orderBy('next_run_at')
                ->limit(3)
                ->get();
                
            if ($nextScheduled->isNotEmpty()) {
                foreach ($nextScheduled as $next) {
                    Log::info("Próximo agendamento: {$next->name} (ID: {$next->id}) - " . 
                        $next->next_run_at->format('Y-m-d H:i:s') . " ({$next->scheduled_frequency})");
                }
            }
            
            return $results;
        }
        
        Log::info("Encontradas " . $settings->count() . " configurações de cashback para processar: " . $now->format('Y-m-d H:i:s'));
        
        foreach ($settings as $setting) {
            try {
                Log::info("Processando configuração: {$setting->name} (ID: {$setting->id}, Frequência: {$setting->scheduled_frequency})");
                
                if ($setting->scheduled_frequency === 'once') {
                    Log::info("Processando agendamento único para a data: " . 
                        ($setting->next_run_at ? $setting->next_run_at->format('Y-m-d H:i:s') : 'Não definido'));
                }
                
                // Processar cashback para esta configuração específica
                $settingResults = $this->processForSetting($setting);
                
                // Marcar como executado e atualizar próximo agendamento
                $setting->markAsRun();
                
                // Adicionar resultados ao total
                $results['processed'] += $settingResults['processed'];
                $results['amount'] += $settingResults['amount'];
                $results['errors'] += $settingResults['errors'];
                $results['global'] += $settingResults['global'];
                $results['vip'] += $settingResults['vip'];
                
                // Adicionar detalhes desta configuração
                $results['settings_processed'][] = [
                    'id' => $setting->id,
                    'name' => $setting->name,
                    'processed' => $settingResults['processed'],
                    'amount' => $settingResults['amount'],
                    'next_run' => $setting->next_run_at
                ];
                
                Log::info("Concluído processamento de {$setting->name}: {$settingResults['processed']} cashbacks, R$ " . 
                    number_format($settingResults['amount'], 2, ',', '.'));
                    
                if ($setting->scheduled_frequency === 'once') {
                    Log::info("Agendamento único processado e desativado");
                } else if ($setting->next_run_at) {
                    Log::info("Próxima execução agendada para: " . $setting->next_run_at->format('Y-m-d H:i:s'));
                }
            } catch (\Exception $e) {
                Log::error('Erro ao processar cashback agendado (config ID: ' . $setting->id . '): ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString()
                ]);
                $results['errors']++;
            }
        }
        
        return $results;
    }

    /**
     * Processar cashbacks para uma configuração específica
     * 
     * @param CashbackSetting $setting
     * @param int|null $specificUserId ID específico de usuário, se o cashback for para um usuário específico
     * @return array
     */
    public function processForSetting(CashbackSetting $setting, $specificUserId = null)
    {
        $results = [
            'processed' => 0,
            'amount' => 0,
            'errors' => 0,
            'global' => 0,
            'vip' => 0
        ];
        
        if (!$setting->active) {
            Log::warning("Tentativa de processar configuração inativa: {$setting->name} (ID: {$setting->id})");
            return $results;
        }
        
        // Determinar período do cashback
        $endDate = $setting->next_run_at ?: now();
        
        // Definir data de início com base no último pagamento ou data de criação
        if ($setting->last_run_at) {
            // Se já houve pagamento anterior, começar a partir dele
            $startDate = $setting->last_run_at;
            Log::info("Usando data do último pagamento como início: " . $startDate->format('d/m/Y H:i:s'));
        } else {
            // Para o primeiro pagamento, verificar se há data de criação ou usar período padrão
            $startDate = $setting->created_at ?: $endDate->copy()->subDays(7);
            Log::info("Primeiro pagamento, usando data de criação: " . $startDate->format('d/m/Y H:i:s'));
        }
        
        Log::info("Processando perdas no período: " . $startDate->format('d/m/Y H:i') . 
            " até " . $endDate->format('d/m/Y H:i') . " para {$setting->name}");
        
        // Se for para um usuário específico, processar apenas para ele
        if ($specificUserId) {
            Log::info("Processando cashback específico para o usuário ID: {$specificUserId}");
            $activeUserIds = collect([$specificUserId]);
        } else {
            // Buscar usuários ativos que tiveram perdas no período
            $activeUserIds = collect();
            $type = $setting->type;
            
            // Usuários com perdas em jogos virtuais
            if ($type === 'all' || $type === 'virtual') {
                $virtualLossUserIds = GameHistory::whereBetween('created_at', [$startDate, $endDate])
                    ->where('action', 'loss')
                    ->where('provider', '!=', 'sports')
                    ->pluck('user_id')
                    ->unique();
                    
                $activeUserIds = $activeUserIds->merge($virtualLossUserIds);
                Log::info("Encontrados {$virtualLossUserIds->count()} usuários com perdas em jogos virtuais");
            }
            
                    // Usuários com perdas em apostas esportivas (contempla ambos provedores)
        if ($type === 'all' || $type === 'sports') {
            $sportsLossUserIds = SportBetSummary::whereBetween('created_at', [$startDate, $endDate])
                ->where(function($query) {
                    $query->where('operation', 'lose')         // Digitain
                          ->orWhere('status', 'lost');         // BetBy
                })
                ->pluck('user_id')
                ->unique();
                    
                $activeUserIds = $activeUserIds->merge($sportsLossUserIds);
                Log::info("Encontrados {$sportsLossUserIds->count()} usuários com perdas em apostas esportivas");
            }
            
            // Remover duplicatas
            $activeUserIds = $activeUserIds->unique();
            Log::info("Total de {$activeUserIds->count()} usuários únicos com perdas no período");
            
            // Se for configuração específica de nível VIP, filtrar usuários
            if (!$setting->is_global && $setting->vip_level) {
                // Executar este bloqueio em pequenos lotes para evitar sobrecarga de memória
                $filteredUserIds = collect();
                foreach ($activeUserIds->chunk(100) as $idsChunk) {
                    $users = User::whereIn('id', $idsChunk)->get();
                    foreach ($users as $user) {
                        $userRanking = $user->getRanking();
                        $userVipLevel = $userRanking ? $userRanking['level'] : 1;
                        
                        if ($userVipLevel == $setting->vip_level) {
                            $filteredUserIds->push($user->id);
                        }
                    }
                }
                Log::info("Filtrados {$filteredUserIds->count()} usuários do nível VIP {$setting->vip_level}");
                $activeUserIds = $filteredUserIds;
            }
        }
        
        foreach ($activeUserIds as $userId) {
            try {
                $user = User::find($userId);
                
                if (!$user) {
                    continue;
                }
                
                // Para configurações globais, verificamos se o usuário não tem uma configuração específica de VIP
                // Mas apenas se não for um cashback específico para este usuário
                if (!$specificUserId && $setting->is_global) {
                    $userRanking = $user->getRanking();
                    $userVipLevel = $userRanking ? $userRanking['level'] : 1;
                    
                    // Verificar se existe uma configuração específica para o nível VIP do usuário
                    $vipSetting = CashbackSetting::where('active', true)
                        ->where('vip_level', $userVipLevel)
                        ->where('type', $setting->type)
                        ->first();
                    
                    // Se existe uma configuração específica para o nível VIP, pular este usuário
                    if ($vipSetting) {
                        continue;
                    }
                }
                
                // Calcular total de perdas do período
                $totalLoss = $this->calculateTotalLoss($user->id, $setting->type, $startDate, $endDate);
                
                // Se não houver perdas acima do mínimo, pular este usuário
                if ($totalLoss < $setting->min_loss) {
                    continue;
                }

                // Calcular valor do cashback
                $cashbackAmount = $setting->calculateCashback($totalLoss);
                
                if ($cashbackAmount <= 0) {
                    continue;
                }

                // Criar descrição identificando o tipo de cashback
                $description = '';
                if ($specificUserId) {
                    $description = "Cashback Específico - {$setting->percentage}%";
                } else if ($setting->vip_level) {
                    $description = "Cashback VIP Nível {$setting->vip_level} - {$setting->percentage}%";
                } else {
                    $description = "Cashback global - {$setting->percentage}%";
                }
                
                // Verificar se já existe um cashback pendente para este usuário com esta configuração
                $existingCashback = UserCashback::where('user_id', $user->id)
                    ->where('cashback_setting_id', $setting->id)
                    ->where('status', 'pending')
                    ->first();
                    
                if ($existingCashback) {
                    Log::info("Usuário {$user->id} já possui cashback pendente para esta configuração");
                    continue;
                }

                // Criar registro de cashback
                $userCashback = UserCashback::create([
                    'user_id' => $user->id,
                    'cashback_setting_id' => $setting->id,
                    'total_loss' => $totalLoss,
                    'cashback_amount' => $cashbackAmount,
                    'percentage_applied' => $setting->percentage,
                    'type' => $setting->type,
                    'status' => 'pending',
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'expires_at' => now()->addDays($setting->expiry_days),
                    'notes' => $description
                ]);
                
                Log::info("Criado cashback para usuário {$user->id}: R$ " . 
                    number_format($cashbackAmount, 2, ',', '.') . " (perda: R$ " . 
                    number_format($totalLoss, 2, ',', '.') . ")");

                // Sempre aplicar o cashback automaticamente
                $applied = $userCashback->apply();
                Log::info("Cashback " . ($applied ? "aplicado" : "não aplicado") . " automaticamente para usuário {$user->id}");
                
                $results['processed']++;
                $results['amount'] += $cashbackAmount;
                
                // Incrementar contadores específicos
                if ($setting->is_global) {
                    $results['global']++;
                } elseif ($setting->vip_level) {
                    $results['vip']++;
                }
            } catch (\Exception $e) {
                Log::error('Erro ao processar cashback para usuário ' . $userId . ': ' . $e->getMessage(), [
                    'exception' => $e
                ]);
                $results['errors']++;
            }
        }
        
        return $results;
    }

    /**
     * Enviar notificação de cashback para usuários por configuração ou nível VIP
     * 
     * @param int|null $settingId ID da configuração de cashback específica (null para todas as configurações)
     * @param int|null $vipLevel Nível VIP específico (null para todos os níveis)
     * @param string|null $type Tipo de cashback (sports, virtual, all)
     * @param string|null $status Status dos cashbacks (pending, credited, expired)
     * @param Carbon|null $startDate Data inicial do período
     * @param Carbon|null $endDate Data final do período
     * @return array Estatísticas do envio
     */
    public function sendCashbackNotifications($settingId = null, $vipLevel = null, $type = null, $status = 'credited', Carbon $startDate = null, Carbon $endDate = null)
    {
        $stats = [
            'total_notifications' => 0,
            'success' => 0,
            'error' => 0
        ];
        
        try {
            // Definir período padrão se não for especificado (últimos 30 dias)
            if (!$startDate) {
                $startDate = Carbon::now()->subDays(30);
            }
            
            if (!$endDate) {
                $endDate = Carbon::now();
            }
            
            // Construir a consulta de cashbacks
            $query = UserCashback::with('user', 'setting')
                ->whereBetween('created_at', [$startDate, $endDate]);
            
            // Filtrar por configuração específica, se fornecida
            if ($settingId) {
                $query->where('cashback_setting_id', $settingId);
            }
            
            // Filtrar por tipo, se fornecido
            if ($type) {
                $query->where('type', $type);
            }
            
            // Filtrar por status, se fornecido
            if ($status) {
                $query->where('status', $status);
            }
            
            // Filtrar por nível VIP, se fornecido
            if ($vipLevel) {
                // Buscar configurações relacionadas ao nível VIP específico
                $vipSettingIds = CashbackSetting::where('vip_level', $vipLevel)->pluck('id')->toArray();
                if (!empty($vipSettingIds)) {
                    $query->whereIn('cashback_setting_id', $vipSettingIds);
                }
            }
            
            // Agrupar por usuário para contabilizar estatísticas
            $userCashbacks = $query->get()->groupBy('user_id');
            
            // Contabilizar estatísticas
            foreach ($userCashbacks as $userId => $cashbacks) {
                    $stats['success']++;
            }
            
            $stats['total_notifications'] = $stats['success'];
            
        } catch (\Exception $e) {
            Log::error("Erro ao processar cashback: " . $e->getMessage());
            $stats['error']++;
        }
        
        return $stats;
    }
    
    /**
     * Obter descrição do tipo de cashback em diferentes idiomas
     * 
     * @param string $type
     * @param string $lang
     * @return string
     */
    private function getTypeDescription($type, $lang = 'pt_br')
    {
        if ($lang === 'pt_br') {
            switch ($type) {
                case 'sports': return 'Apostas Esportivas';
                case 'virtual': return 'Cassino';
                case 'all':
                default: return 'Todos os Jogos';
            }
        } elseif ($lang === 'en') {
            switch ($type) {
                case 'sports': return 'Sports Betting';
                case 'virtual': return 'Casino';
                case 'all':
                default: return 'All Games';
            }
        } elseif ($lang === 'es') {
            switch ($type) {
                case 'sports': return 'Apuestas Deportivas';
                case 'virtual': return 'Casino';
                case 'all':
                default: return 'Todos los Juegos';
            }
        }
        
        return 'All Games';
    }
} 