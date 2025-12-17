<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tournament;
use Illuminate\Support\Facades\Log;

class FinishExpiredTournaments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tournaments:finish-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Altera o status dos torneios expirados para "finalizado"';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando verificação de torneios expirados...');
        
        try {
            // Buscar todos os torneios ativos cujo prazo já expirou
            $expiredTournaments = Tournament::where('status', 'active')
                ->where('end_date', '<', now())
                ->get();
                
            if ($expiredTournaments->isEmpty()) {
                $this->info('Nenhum torneio expirado encontrado.');
                return 0;
            }
            
            $count = 0;
            foreach ($expiredTournaments as $tournament) {
                // Atualizar o status para 'finished'
                $tournament->status = 'finished';
                $tournament->save();
                
                $this->info("Torneio #{$tournament->id} - {$tournament->name} finalizado com sucesso.");
                $count++;
            }
            
            $this->info("Total de {$count} torneios finalizados.");
            Log::info("FinishExpiredTournaments: {$count} torneios foram finalizados automaticamente.");
            
            return 0;
        } catch (\Exception $e) {
            $this->error('Erro ao finalizar torneios: ' . $e->getMessage());
            Log::error('FinishExpiredTournaments: ' . $e->getMessage());
            return 1;
        }
    }
}
