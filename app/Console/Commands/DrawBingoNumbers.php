<?php

namespace App\Console\Commands;

use App\Models\BingoGame;
use App\Models\BingoDrawnNumber;
use Illuminate\Console\Command;
use Carbon\Carbon;

class DrawBingoNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bingo:draw-numbers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sorteia números para os jogos de bingo ativos';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Iniciando sorteio de números de bingo...');
        
        // Buscar jogos ativos
        $activeGames = BingoGame::where('active', true)
            ->where('completed', false)
            ->where('start_time', '<=', now())
            ->get();
            
        $this->info('Jogos ativos encontrados: ' . $activeGames->count());
        
        foreach ($activeGames as $game) {
            $this->info('Processando jogo: ' . $game->name . ' (ID: ' . $game->id . ')');
            
            // Buscar último número sorteado
            $lastDrawn = $game->drawnNumbers()->latest('drawn_at')->first();
            
            // Se nunca houve sorteio ou se já passou tempo suficiente desde o último sorteio
            if (!$lastDrawn || Carbon::now()->diffInSeconds($lastDrawn->drawn_at) >= $game->draw_interval) {
                $this->info('Intervalo de sorteio atingido, sorteando novo número...');
                
                // Obter todos os números já sorteados
                $drawnNumbers = $game->drawnNumbers()->pluck('number')->toArray();
                
                // Verificar se já foram sorteados todos os 90 números
                if (count($drawnNumbers) >= 90) {
                    $this->info('Todos os números já foram sorteados para este jogo. Marcando como concluído.');
                    
                    // Marcar jogo como concluído
                    $game->active = false;
                    $game->completed = true;
                    $game->end_time = now();
                    $game->save();
                    
                    continue;
                }
                
                // Gerar um número que ainda não foi sorteado
                $availableNumbers = array_diff(range(1, 90), $drawnNumbers);
                $randomKey = array_rand($availableNumbers);
                $newNumber = $availableNumbers[$randomKey];
                
                // Salvar o novo número sorteado
                $drawnNumber = BingoDrawnNumber::create([
                    'bingo_game_id' => $game->id,
                    'number' => $newNumber,
                    'order' => count($drawnNumbers) + 1,
                    'drawn_at' => now(),
                ]);
                
                // Atualizar contador de números sorteados
                $game->numbers_drawn = count($drawnNumbers) + 1;
                $game->save();
                
                $this->info('Número sorteado: ' . $newNumber . ' (Ordem: ' . $drawnNumber->order . ')');
            } else {
                $timeLeft = $game->draw_interval - Carbon::now()->diffInSeconds($lastDrawn->drawn_at);
                $this->info('Aguardando intervalo de sorteio. Tempo restante: ' . $timeLeft . ' segundos.');
            }
        }
        
        $this->info('Sorteio de números concluído.');
        
        return 0;
    }
} 