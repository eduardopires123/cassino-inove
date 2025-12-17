<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Settings;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class CheckLicenseMiddleware
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
        $path = $request->path();
        $routeName = $request->route()?->getName();
        
        // Rotas que sempre devem ser permitidas (independente da licença)
        $alwaysAllowedRoutes = [
            'maintenance',
            'check.license',
            'RenovaPlano',
            'callback',
            'paxpay.callback',
            'primepag.callback',
            'clearcacheafterpayment',
        ];
        
        if (in_array($routeName, $alwaysAllowedRoutes)) {
            return $next($request);
        }

        // Verificar também por path para callbacks de pagamento que podem não ter nome de rota
        $callbackPaths = [
            'callback',
            'paxpay/callback',
            'primepag/callback',
            'cpayment',
            'clearcacheafterpayment',
        ];
        
        foreach ($callbackPaths as $callbackPath) {
            if (str_starts_with($path, $callbackPath)) {
                return $next($request);
            }
        }

        $isAdminRoute = str_starts_with($path, 'admin');
        
        // Verificar status da licença
        $cacheKey = 'license_check';
        $setting = Cache::remember($cacheKey, now()->addMinutes(1), function () {
            return Settings::first();
        });

        $licenseExpired = false;
        if ($setting) {
            try {
                $expira = Carbon::parse($setting->expire);
                $hoje = Carbon::now();
                $licenseExpired = $hoje->greaterThanOrEqualTo($expira);
            } catch (\Exception $e) {
                // Se houver erro ao processar a data, considerar licença válida
            }
        }

        // Se for rota admin
        if ($isAdminRoute) {
            // Rotas admin que são sempre permitidas (mesmo com licença expirada)
            $adminAlwaysAllowed = [
                'admin.login',
                'admin.login.post',
                'admin.check-auth',
                'admin.logout',
            ];
            
            if (in_array($routeName, $adminAlwaysAllowed)) {
                return $next($request);
            }

            // Se licença estiver expirada, permitir apenas dashboard
            if ($licenseExpired) {
                // Rotas de dashboard permitidas mesmo com licença expirada
                $dashboardRoutes = [
                    'admin.index',
                    'admin.dash',
                    'admin.dash.ggr-data',
                    'admin.dash.pix-transactions',
                    'admin.dash.manual-transactions',
                    'admin.dash.financial-data',
                    'admin.dash.casino-data',
                    'admin.dash.sports-data',
                    'admin.dash.normal-withdrawals',
                    'admin.dash.affiliate-withdrawals',
                ];
                
                // Se for rota de dashboard, permitir acesso
                if (in_array($routeName, $dashboardRoutes)) {
                    return $next($request);
                }
                
                // Se for outra rota admin, redirecionar para dashboard
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'status' => false,
                        'redirect' => route('admin.index'),
                        'message' => 'Licença expirada. Acesso permitido apenas ao dashboard.'
                    ], 403);
                }
                
                return redirect()->route('admin.index')->with('error', 'Licença expirada. Acesso permitido apenas ao dashboard.');
            }
            
            // Licença válida - permitir acesso a todas as rotas admin
            return $next($request);
        }

        // Se não for rota admin e licença estiver expirada
        if ($licenseExpired) {
            // Permitir webhooks e APIs críticas mesmo com licença expirada
            $webhookPaths = ['playfiver/webhook', 'tbs2api/webhook', 'games/webhook'];
            if (in_array($path, $webhookPaths)) {
                return $next($request);
            }

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => false,
                    'redirect' => url('/maintenance'),
                    'message' => 'Licença expirada. Site em manutenção.'
                ], 403);
            }
            
            // Redirecionar para página de manutenção
            return redirect('/maintenance');
        }

        // Licença válida - permitir acesso
        return $next($request);
    }
}

