<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define o schedule de comandos da aplicação.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        
        // Executar a cada minuto para verificar os jogos de bingo
        $schedule->command('bingo:draw-numbers')->everyMinute();
        
        // Verificar sincronização de versão a cada 10 minutos
        $schedule->command('version:sync')->everyTenMinutes()->runInBackground();
        
        // Verificar atualizações de versão a cada hora
        $schedule->command('platform:sync-version')
                 ->hourly()
                 ->appendOutputTo(storage_path('logs/version-sync.log'));
                 
        // Processar cashbacks agendados automaticamente a cada hora
        $schedule->command('cashback:process --scheduled')
                 ->hourly()
                 ->appendOutputTo(storage_path('logs/cashback-process.log'));
                 
        // Verificar cashbacks agendados a cada minuto
        $schedule->command('cashback:process --scheduled')
            ->everyMinute()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/cashback-schedule.log'));
                 
        // Atualizar pontos dos torneios ativos a cada hora
        $schedule->call(function () {
            $activeTournaments = \App\Models\Tournament::where('status', 'active')
                ->where('start_date', '<=', now())
                ->where('end_date', '>', now())
                ->get();
                
            foreach ($activeTournaments as $tournament) {
                $tournament->calculateBetPoints();
            }
        })->hourly();
        
        // Finalizar torneios expirados a cada hora
        $schedule->command('tournaments:finish-expired')
                 ->hourly()
                 ->appendOutputTo(storage_path('logs/tournaments-finish.log'));
    }

    /**
     * Registra os comandos para a aplicação.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\GenerateUpdatePackage::class,
        Commands\SetGithubToken::class,
        \App\Console\Commands\CheckVersionSync::class,
        \App\Console\Commands\CheckEmailConfiguration::class,
        \App\Console\Commands\ProcessCashbacks::class,
        \App\Console\Commands\UpdateTournamentPoints::class,
        \App\Console\Commands\FinishExpiredTournaments::class,
    ];
} 