<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
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
        
        return $next($request);
    }
} 