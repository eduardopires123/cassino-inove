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
        
        // Processar cashbacks agendados automaticamente a cada hora
        $schedule->command('cashback:process --scheduled')
                 ->hourly()
                 ->appendOutputTo(storage_path('logs/cashback-process.log'));
                 
        // Verificar cashbacks agendados a cada minuto
        $schedule->command('cashback:process --scheduled')
            ->everyMinute()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/cashback-schedule.log'));
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
        \App\Console\Commands\CheckEmailConfiguration::class,
        \App\Console\Commands\ProcessCashbacks::class,
    ];
} 