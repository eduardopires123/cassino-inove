<?php

namespace App\Console\Commands;

use App\Services\CashbackService;
use App\Models\CashbackSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ProcessCashbacks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cashback:process 
                        {type=all : Tipo de cashback a processar (sports, virtual, all)}
                        {--scheduled : Processa apenas cashbacks agendados prontos para execução}
                        {--setting= : ID da configuração específica para processar}
                        {--user= : ID do usuário específico para processar cashback}
                        {--force : Força a execução imediata de uma configuração específica}
                        {--debug : Mostra informações detalhadas durante o processamento}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processa os cashbacks dos usuários com base nas perdas no período configurado';

    /**
     * Execute the console command.
     */
    public function handle(CashbackService $cashbackService)
    {
        $type = $this->argument('type');
        $useScheduled = $this->option('scheduled');
        $settingId = $this->option('setting');
        $debug = $this->option('debug');
        
        if ($settingId) {
            return $this->processSpecificSetting($cashbackService, $settingId, $debug);
        }
        
        if ($useScheduled) {
            return $this->processScheduledCashbacks($cashbackService, $debug);
        } else {
            return $this->processAutomaticCashbacks($cashbackService, $type, $debug);
        }
    }
    
    /**
     * Processa uma configuração específica de cashback
     */
    private function processSpecificSetting(CashbackService $cashbackService, $settingId, $debug = false)
    {
        $this->info("Processando configuração de cashback específica (ID: {$settingId})");
        
        try {
            // Buscar a configuração
            $setting = CashbackSetting::find($settingId);
            
            if (!$setting) {
                $this->error("Configuração de ID {$settingId} não encontrada!");
                return Command::FAILURE;
            }
            
            $this->info("Configuração encontrada: {$setting->name}");
            
            if ($debug) {
                $this->line("Tipo: " . ucfirst($setting->type));
                $this->line("Percentual: {$setting->percentage}%");
                $this->line("Perda Mínima: R$ " . number_format($setting->min_loss, 2, ',', '.'));
                $this->line("Agendamento: " . ($setting->schedule_active ? 'Ativo' : 'Inativo'));
            }
            
            // Forçar o próximo agendamento para agora se necessário
            if ($this->option('force') && $setting->next_run_at && $setting->next_run_at > now()) {
                $this->info("Forçando execução imediata (próximo agendamento era para: " . 
                    $setting->next_run_at->format('d/m/Y H:i:s') . ")");
                $setting->next_run_at = now();
                $setting->save();
            }
            
            // Verificar se está pronto para execução
            if ($setting->next_run_at && $setting->next_run_at > now() && !$this->option('force')) {
                $this->warn("Esta configuração ainda não está agendada para execução. " .
                    "Próxima execução: " . $setting->next_run_at->format('d/m/Y H:i:s'));
                $this->warn("Use a opção --force para executar imediatamente.");
                return Command::FAILURE;
            }
            
            // Verificar se é para um usuário específico
            $userId = $this->option('user');
            if ($userId) {
                $user = \App\Models\User::find($userId);
                if (!$user) {
                    $this->error("Usuário de ID {$userId} não encontrado!");
                    return Command::FAILURE;
                }
                $this->info("Processando cashback específico para usuário: {$user->name} (ID: {$userId})");
            }
            
            // Processar
            $results = $cashbackService->processForSetting($setting, $userId);
            
            if ($setting->schedule_active) {
                $setting->markAsRun();
            }
            
            $this->info("Processamento concluído!");
            $this->table(
                ['Processados', 'Valor Total', 'Globais', 'VIP', 'Erros'],
                [[$results['processed'], 'R$ ' . number_format($results['amount'], 2, ',', '.'), $results['global'], $results['vip'], $results['errors']]]
            );
            
            // Mostrar próximo agendamento se ainda estiver ativo
            if ($setting->schedule_active) {
                $this->info("Próxima execução agendada para: " . 
                    ($setting->next_run_at ? $setting->next_run_at->format('d/m/Y H:i:s') : 'N/A'));
            } else {
                $this->info("Agendamento desativado após execução (frequência: {$setting->scheduled_frequency})");
            }
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Erro ao processar configuração específica: " . $e->getMessage());
            Log::error("Erro ao processar configuração específica (ID: {$settingId}): " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return Command::FAILURE;
        }
    }
    
    /**
     * Processa todos os cashbacks agendados prontos para execução
     */
    private function processScheduledCashbacks(CashbackService $cashbackService, $debug = false)
    {
        $this->info("Iniciando processamento de cashbacks agendados");
        
        try {
            // Verificar configurações com agendamento pendente
            $pendingSettings = CashbackSetting::where('active', true)
                ->where('schedule_active', true)
                ->where(function($query) {
                    $query->whereNotNull('next_run_at')
                          ->where('next_run_at', '<=', now());
                })
                ->get();
                
            if ($pendingSettings->isEmpty()) {
                $this->warn("Não há configurações de cashback agendadas para execução neste momento.");
                
                if ($debug) {
                    $nextScheduled = CashbackSetting::where('active', true)
                        ->where('schedule_active', true)
                        ->whereNotNull('next_run_at')
                        ->orderBy('next_run_at')
                        ->first();
                        
                    if ($nextScheduled) {
                        $this->line("Próximo cashback agendado: {$nextScheduled->name} - " . 
                            $nextScheduled->next_run_at->format('d/m/Y H:i:s'));
                    }
                }
                
                return Command::SUCCESS;
            }
            
            if ($debug) {
                $this->info("Encontradas {$pendingSettings->count()} configurações para processar:");
                foreach ($pendingSettings as $setting) {
                    $this->line("- {$setting->name} (ID: {$setting->id}, Tipo: {$setting->type}, Agendado para: " . 
                        ($setting->next_run_at ? $setting->next_run_at->format('d/m/Y H:i:s') : 'N/A') . ")");
                }
            }
            
            $results = $cashbackService->processScheduledCashbacks();
            
            $this->info("Processamento concluído!");
            $this->table(
                ['Processados', 'Valor Total', 'Globais', 'VIP', 'Erros'],
                [[$results['processed'], 'R$ ' . number_format($results['amount'], 2, ',', '.'), $results['global'], $results['vip'], $results['errors']]]
            );
            
            if (!empty($results['settings_processed'])) {
                $this->info("Configurações processadas:");
                $processedTableData = [];
                
                foreach ($results['settings_processed'] as $setting) {
                    $processedTableData[] = [
                        $setting['id'],
                        $setting['name'],
                        $setting['processed'],
                        'R$ ' . number_format($setting['amount'], 2, ',', '.'),
                        $setting['next_run'] ? $setting['next_run']->format('d/m/Y H:i') : 'N/A'
                    ];
                }
                
                $this->table(
                    ['ID', 'Nome', 'Processados', 'Valor', 'Próxima Execução'],
                    $processedTableData
                );
            }
            
            Log::info("Processamento de cashbacks agendados concluído", $results);
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Erro ao processar cashbacks agendados: " . $e->getMessage());
            Log::error("Erro ao processar cashbacks agendados: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return Command::FAILURE;
        }
    }
    
    /**
     * Processa cashbacks automáticos
     */
    private function processAutomaticCashbacks(CashbackService $cashbackService, $type, $debug = false)
    {
        $this->info("Iniciando processamento manual de cashbacks do tipo: {$type}");
        
        try {
            if ($debug) {
                $this->line("Buscando usuários com perdas recentes...");
            }
            
            $results = $cashbackService->processAutomaticCashbacks($type);
            
            $this->info("Processamento concluído!");
            $this->table(
                ['Processados', 'Valor Total', 'Globais', 'VIP', 'Erros'],
                [[$results['processed'], 'R$ ' . number_format($results['amount'], 2, ',', '.'), $results['global'], $results['vip'], $results['errors']]]
            );
            
            Log::info("Processamento de cashbacks automáticos concluído", $results);
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Erro ao processar cashbacks: " . $e->getMessage());
            Log::error("Erro ao processar cashbacks automáticos: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return Command::FAILURE;
        }
    }
} 