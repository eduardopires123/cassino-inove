<?php

namespace App\Console\Commands;

use App\Models\Tournament;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateTournamentPoints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tournaments:update-points';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza os pontos dos jogadores em torneios ativos com base nas apostas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando atualização de pontos dos torneios...');
        
        // Obter todos os torneios ativos
        $activeTournaments = Tournament::where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>', now())
            ->get();
            
        $this->info('Encontrados ' . count($activeTournaments) . ' torneios ativos.');
        
        foreach ($activeTournaments as $tournament) {
            $this->info('Atualizando pontos para o torneio: ' . $tournament->name);
            
            try {
                // Verificar os jogos qualificados do torneio
                $qualifiedGames = [];
                if (!empty($tournament->qualified_games)) {
                    $qualifiedGameIds = explode(',', $tournament->qualified_games);
                    $gamesInfo = \DB::table('games_api')
                        ->whereIn('id', $qualifiedGameIds)
                        ->get(['id', 'name', 'slug', 'slug_playfiver', 'use_playfiver']);
                    
                    foreach ($gamesInfo as $game) {
                        $gameIdentifier = $game->use_playfiver ? $game->slug_playfiver : 
                            (strpos($game->slug, '/') !== false ? explode('/', $game->slug)[2] : $game->slug);
                        
                        $qualifiedGames[] = [
                            'id' => $game->id,
                            'name' => $game->name,
                            'identifier' => $gameIdentifier
                        ];
                    }
                    
                    $this->info('Jogos qualificados para este torneio: ' . implode(', ', array_column($qualifiedGames, 'name')));
                }
                
                // Primeiro, chamar calculateBetPoints para processar todos os usuários 
                // e criar registros para jogadores com apostas
                $this->info('Processando todos os usuários com apostas...');
                $tournament->calculateBetPoints();
                
                // Obter contagem atualizada de jogadores
                $realPlayersCount = $tournament->players()
                    ->where('is_random_player', false)
                    ->count();
                
                $this->info('Total de jogadores reais registrados: ' . $realPlayersCount);
                
                // Antes de calcular os pontos, listar alguns jogadores reais para verificação
                $realPlayers = $tournament->players()
                    ->where('is_random_player', false)
                    ->limit(5)
                    ->get();
                
                if ($realPlayers->count() > 0) {
                    $this->info('Verificando apostas de ' . $realPlayers->count() . ' jogadores reais para amostragem:');
                    
                    foreach ($realPlayers as $player) {
                        $userId = $player->user_id;
                        $userName = $player->user->name ?? 'Usuário #' . $userId;
                        
                        // Verificar apostas realizadas pelo jogador nos jogos qualificados após o início do torneio
                        $betsQuery = \App\Models\GameHistory::where('user_id', $userId)
                            ->where('action', 'loss')
                            ->where('created_at', '>=', $tournament->start_date)
                            ->where('created_at', '<=', now());
                        
                        // Adicionar filtro por jogos qualificados, se houver
                        if (!empty($qualifiedGames)) {
                            $gameIdentifiers = array_column($qualifiedGames, 'identifier');
                            $betsQuery->whereIn('game', $gameIdentifiers);
                        }
                        
                        $totalBets = $betsQuery->sum('amount');
                        $betsCount = $betsQuery->count();
                        
                        // Obter algumas apostas recentes para verificação
                        $recentBets = $betsQuery->orderBy('created_at', 'desc')
                            ->limit(3)
                            ->get(['id', 'game', 'amount', 'created_at']);
                        
                        $this->info("  - Jogador: {$userName} (ID: {$userId})");
                        $this->info("    Total apostado: R$ " . number_format($totalBets, 2, ',', '.') . " em {$betsCount} apostas");
                        $this->info("    Pontos atuais: " . number_format($player->points, 2, ',', '.'));
                        
                        if ($recentBets->count() > 0) {
                            $this->info("    Apostas recentes:");
                            foreach ($recentBets as $bet) {
                                $this->info("      * R$ " . number_format($bet->amount, 2, ',', '.') . 
                                           " no jogo '{$bet->game}' em " . $bet->created_at);
                            }
                        } else {
                            $this->info("    Nenhuma aposta encontrada para este jogador nos jogos qualificados.");
                        }
                    }
                } else {
                    $this->info('Nenhum jogador real encontrado neste torneio para verificação.');
                }
                
                // Garantir que existem jogadores aleatórios se configurado
                if ($tournament->use_random_players) {
                    $tournament->ensureRandomPlayers();
                }
                
                $this->info('Pontos atualizados com sucesso para o torneio: ' . $tournament->name);
            } catch (\Exception $e) {
                $this->error('Erro ao atualizar pontos para o torneio ' . $tournament->name . ': ' . $e->getMessage());
                Log::error('Erro ao atualizar pontos do torneio: ' . $e->getMessage(), [
                    'tournament_id' => $tournament->id,
                    'tournament_name' => $tournament->name
                ]);
            }
        }
        
        $this->info('Atualização de pontos concluída!');
        
        return Command::SUCCESS;
    }
} 