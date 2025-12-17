<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class AdminRedirectMiddleware
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
        // Sempre redirecionar para a versão sem prefixo de idioma do admin
        $locale = App::getLocale();
        $path = $request->path();
        
        // Se o caminho tem formato "en/admin" ou similar, redirecionar para "/admin"
        if (preg_match('#^(en|es)/admin(/.*)?$#', $path, $matches)) {
            $adminPath = 'admin';
            if (isset($matches[2])) {
                $adminPath .= $matches[2];
            }
            
            return redirect('/' . $adminPath);
        }
        
        return $next($request);
    }
} 