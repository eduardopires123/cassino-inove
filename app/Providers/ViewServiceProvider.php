<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // A view partials.banner já obtém os banners diretamente através do PartialsController
        // Não é necessário um view composer adicional
        
        // Se precisar de um view composer no futuro, descomente e ajuste conforme necessário:
        /*
        View::composer('partials.banner', function ($view) {
            $banners = \App\Http\Controllers\PartialsController::getBannersData();
            $view->with('banners', $banners);
        });
        */
    }
} 