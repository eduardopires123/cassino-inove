<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
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
        if (!Auth::check()) {
            // Usuário não está logado, redirecionar para a página de login
            return redirect()->route('admin.login');
        }

        if (Auth::user()->is_admin == 0) {
            // Usuário está logado mas não é administrador (is_admin != 1)
            // Redirecionar para a página inicial
            return redirect('/')->with('error', 'Você não tem permissão para acessar a área administrativa.');
        }

        return $next($request);
    }
}
