<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RegenerateSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        
        // Se o usuário acabou de fazer login, regenera a sessão
        if (Auth::check() && session()->has('_regenerated') === false) {
            session()->put('_regenerated', true);
            session()->regenerate();
        }
        
        return $response;
    }
} 