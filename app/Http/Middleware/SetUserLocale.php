<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class SetUserLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $supportedLocales = ['pt_BR', 'en', 'es'];
        $defaultLocale = 'pt_BR';
        
        $locale = $this->determineLocale($request, $supportedLocales, $defaultLocale);
        
        // Definir o idioma da aplicação
        App::setLocale($locale);
        
        return $next($request);
    }
    
    /**
     * Determina o idioma a ser usado baseado na prioridade:
     * 1. Cookie existente (após troca manual)
     * 2. Usuário logado (banco de dados)
     * 3. Accept-Language do browser
     * 4. Idioma padrão
     */
    private function determineLocale(Request $request, array $supportedLocales, string $defaultLocale): string
    {
        // 1. Prioridade: Cookie existente (sempre que o usuário escolher manualmente)
        if ($request->cookie('user_locale') && in_array($request->cookie('user_locale'), $supportedLocales)) {
            return $request->cookie('user_locale');
        }
        
        // 2. Usuário logado com idioma definido (só se não houver cookie)
        if (Auth::check() && Auth::user()->language && in_array(Auth::user()->language, $supportedLocales)) {
            return Auth::user()->language;
        }
        
        // 3. Accept-Language do browser
        if ($request->header('Accept-Language')) {
            $browserLocale = $this->getBrowserLocale($request, $supportedLocales);
            if ($browserLocale) {
                return $browserLocale;
            }
        }
        
        // 4. Idioma padrão
        return $defaultLocale;
    }
    
    /**
     * Obtém o idioma preferido do browser
     */
    private function getBrowserLocale(Request $request, array $supportedLocales): ?string
    {
        $browserLang = $request->getPreferredLanguage(['pt-BR', 'pt', 'en', 'es']);
        
        switch ($browserLang) {
            case 'pt-BR':
            case 'pt':
                return in_array('pt_BR', $supportedLocales) ? 'pt_BR' : null;
            case 'en':
                return in_array('en', $supportedLocales) ? 'en' : null;
            case 'es':
                return in_array('es', $supportedLocales) ? 'es' : null;
            default:
                return null;
        }
    }
} 