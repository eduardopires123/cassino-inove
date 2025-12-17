<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RouletteItem;
use App\Models\RouletteSpin;

class RouletteStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roulette:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mostra o status da roleta e estatísticas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== STATUS DA ROLETA ===');
        
        // Verificar itens ativos
        $activeItems = RouletteItem::active()->count();
        $totalItems = RouletteItem::count();
        
        $this->info("Itens da roleta: {$activeItems} ativos de {$totalItems} totais");
        
        // Mostrar itens
        $this->table(
            ['ID', 'Nome', 'Giros Grátis', 'Probabilidade (%)', 'Status'],
            RouletteItem::all()->map(function ($item) {
                return [
                    $item->id,
                    $item->name,
                    $item->free_spins,
                    number_format($item->probability * 100, 2) . '%',
                    $item->is_active ? 'Ativo' : 'Inativo'
                ];
            })->toArray()
        );
        
        // Estatísticas de giros
        $totalSpins = RouletteSpin::count();
        $todaySpins = RouletteSpin::today()->count();
        
        $this->info("\n=== ESTATÍSTICAS DE GIROS ===");
        $this->info("Total de giros: {$totalSpins}");
        $this->info("Giros hoje: {$todaySpins}");
        
        // Verificar integridade das probabilidades
        $totalProbability = RouletteItem::active()->sum('probability');
        $this->info("\n=== VERIFICAÇÃO DE INTEGRIDADE ===");
        
        if (abs($totalProbability - 1.0) < 0.01) {
            $this->info("✅ Probabilidades estão corretas (soma: " . number_format($totalProbability * 100, 2) . "%)");
        } else {
            $this->error("❌ Problema nas probabilidades! Soma: " . number_format($totalProbability * 100, 2) . "% (deve ser 100%)");
        }
        
        return 0;
    }
} 