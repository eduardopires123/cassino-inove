<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;

class VerifyCsrfToken extends Middleware
{
    /**
     * Os URIs que devem ser excluídos da verificação CSRF.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/admin/login',
        '/login',
        '/logout',
        '/logout-ajax',
        // Rotas de pagamento protegidas
        '/wh-pay-r9t4k2',           // callback
        '/fin-d3p-k8n2',            // PagPix
        '/fin-s4q-m7x1',            // Saque
        '/fin-s4q-aff-p2r9',        // SaqueAff
        '/fin-s4q-bns-j5t3',        // SaqueBonus
        // Rotas de jogos protegidas
        '/gm-exit-h4n9',            // outgame
        '/wh-gm-x7k9m2/*',          // games webhook
        '/api-gm-x5h9w2/*',         // inoveplay
        // Outras rotas
        '/bookiewiseapi/*',
        '/betby/callback/*',
        '/sports/token/refresh',
        '/betby/token/refresh',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            return parent::handle($request, $next);
        } catch (TokenMismatchException $e) {
            Log::warning('CSRF token mismatch', [
                'ip' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'path' => $request->path(),
            ]);

            // Regenerar token CSRF
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sessão expirada. Por favor, recarregue a página e tente novamente.',
                    'csrf_error' => true,
                    'new_token' => csrf_token()
                ], 419);
            }

            return redirect()->back()
                ->withInput($request->except('password'))
                ->with('error', 'Sessão expirada. Por favor, tente novamente.');
        }
    }
}
