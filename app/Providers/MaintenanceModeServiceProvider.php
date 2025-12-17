<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\MaintenanceMode;
use Illuminate\Foundation\FileBasedMaintenanceMode;

class MaintenanceModeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(MaintenanceMode::class, function ($app) {
            return new FileBasedMaintenanceMode(storage_path('framework/down'));
        });
    }
} 