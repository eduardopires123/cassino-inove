<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\ViewComposers\AdminLicenseComposer;
use App\ViewComposers\AppLayoutComposer;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
  
        // Alternativa: fornecer dados estáticos para banners
        View::composer('partials.banner', function ($view) {
            $view->with('banners', [
                // Dados fictícios para banners, se necessário
            ]);
        });

        // Compartilhar dados de licença com views admin
        View::composer('admin.partials.navbar', AdminLicenseComposer::class);
        View::composer('admin.layouts.app', AdminLicenseComposer::class);

        // Compartilhar dados com o layout principal app.blade.php
        // Como o modal é incluído dentro de views que estendem layouts.app,
        // ele automaticamente herda as variáveis do ViewComposer
        View::composer('layouts.app', AppLayoutComposer::class);
        
        // Aplicar ViewComposer também à home para garantir que as variáveis estejam disponíveis
        // (mesmo que estenda layouts.app, é melhor garantir)
        View::composer('home', AppLayoutComposer::class);
        
        // Apenas views que NÃO estendem layouts.app precisam do ViewComposer aplicado diretamente
        // play_mobile.blade.php não estende o layout, então precisa do ViewComposer
        View::composer('cassino.play_mobile', AppLayoutComposer::class);
    }
} 