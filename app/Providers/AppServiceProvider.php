<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\BannerController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        // Registrar o serviço de imagem
        $this->app->singleton(App\Services\ImageService::class, function ($app) {
            return new App\Services\ImageService();
        });
        
        // Procure por algo como:
        // config(['app.key' => null]);
        // Ou qualquer manipulação da configuração 'app.key'
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request): void
    {
        URL::forceScheme('https');
        
        // Define padrões globais de rota para parâmetros comuns
        \Illuminate\Support\Facades\Route::pattern('id', '[0-9]+');
        \Illuminate\Support\Facades\Route::pattern('slug', '[a-z0-9-]+');

        // Compartilhar o ranking do usuário com todas as views
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $view->with('ranking', Auth::user()->getRanking());
            }
        });

        // Definir idiomas suportados
        $supportedLocales = ['en', 'es', 'pt_BR'];
        $defaultLocale = 'pt_BR';
        
        // Obter o locale da URL se estiver presente
        $locale = $request->segment(1);
        
        // Verificar se o locale está entre os suportados (en, es)
        if (in_array($locale, ['en', 'es'])) {
            App::setLocale($locale);
            // Armazenar em cookie para futuras visitas
            Cookie::queue('user_locale', $locale, 60 * 24 * 365); // 1 ano
            
            // Configura o resolvedor de caminho da paginação para manter o prefixo de idioma
            Paginator::currentPathResolver(function () use ($locale) {
                $path = request()->path();
                // Remove o prefixo de idioma se já estiver presente para evitar duplicação
                if (strpos($path, $locale) === 0) {
                    return $path;
                }
                return $locale . '/' . ltrim($path, '/');
            });
            
            // Para o LengthAwarePaginator, substituímos a resolução de URL padrão
            // Isso afeta todas as instâncias de paginação criadas após este ponto
            URL::defaults(['locale' => $locale]);
        } else {
            // Se não há locale na URL, tenta usar o cookie
            $cookieLocale = $request->cookie('user_locale');
            if ($cookieLocale && in_array($cookieLocale, $supportedLocales)) {
                App::setLocale($cookieLocale);
                
            } else {
                // Caso contrário, usar o padrão (pt_BR)
                App::setLocale($defaultLocale);
            }
        }
        // Registrar observers
        \App\Models\Transactions::observe(\App\Observers\TransactionObserver::class);
        \App\Models\SportBetSummary::observe(\App\Observers\SportBetObserver::class);
    }
}