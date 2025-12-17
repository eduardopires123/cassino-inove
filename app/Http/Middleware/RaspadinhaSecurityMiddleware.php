<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RaspadinhaSecurityMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Não autenticado'], 401);
        }

        $user = Auth::user();
        $userId = $user->id;
        $userIP = $request->ip();
        $now = now();

        // 1. RATE LIMITING POR USUÁRIO
        $userKey = "raspadinha_rate_limit_user_{$userId}";
        $userRequests = Cache::get($userKey, []);
        
        // Limpar requests antigos (últimos 60 segundos)
        $userRequests = array_filter($userRequests, function($timestamp) use ($now) {
            return $now->diffInSeconds($timestamp) <= 60;
        });

        // Verificar se excedeu limite (máximo 30 requests por minuto por usuário - permite jogo rápido)
        if (count($userRequests) >= 30) {
            return response()->json([
                'success' => false, 
                'message' => 'Muitas tentativas. Aguarde um momento.'
            ], 429);
        }

        // 2. RATE LIMITING POR IP
        $ipKey = "raspadinha_rate_limit_ip_{$userIP}";
        $ipRequests = Cache::get($ipKey, []);
        
        // Limpar requests antigos do IP
        $ipRequests = array_filter($ipRequests, function($timestamp) use ($now) {
            return $now->diffInSeconds($timestamp) <= 60;
        });

        // Verificar se IP excedeu limite (máximo 60 requests por minuto por IP - permite múltiplos usuários)
        if (count($ipRequests) >= 60) {
            return response()->json([
                'success' => false, 
                'message' => 'Limite de requisições atingido para este IP.'
            ], 429);
        }

        // 3. VALIDAR INTERVALO MÍNIMO ENTRE JOGADAS (apenas para prevenir spam automático)
        if ($request->routeIs('raspadinha.play') || $request->routeIs('raspadinha.play-auto')) {
            $lastGameKey = "raspadinha_last_game_{$userId}";
            $lastGameTime = Cache::get($lastGameKey);
            
            // Reduzido para 0.5 segundos - permite jogo rápido mas previne spam
            if ($lastGameTime && $now->diffInMilliseconds($lastGameTime) < 500) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Aguarde um momento antes de jogar novamente.'
                ], 429);
            }
            
            // Registrar tempo da jogada atual
            Cache::put($lastGameKey, $now, 60); // 1 minuto de cache apenas
        }

        // 4. VALIDAR SEQUÊNCIA CLAIM-PRIZE (apenas múltiplas tentativas do mesmo claim)
        if ($request->routeIs('raspadinha.claim-prize')) {
            $this->validateClaimPrizeSequence($request, $userId);
        }

        // Registrar request atual
        $userRequests[] = $now;
        $ipRequests[] = $now;
        
        Cache::put($userKey, $userRequests, 120); // 2 minutos
        Cache::put($ipKey, $ipRequests, 120); // 2 minutos

        return $next($request);
    }

    /**
     * Validar sequência de claim prize
     */
    private function validateClaimPrizeSequence(Request $request, int $userId): void
    {
        $historyId = $request->input('history_id');
        
        if (!$historyId) {
            return;
        }

        // Verificar se já foi tentado claim para este history_id
        $claimKey = "raspadinha_claim_attempt_{$userId}_{$historyId}";
        $claimAttempts = Cache::get($claimKey, 0);
        
        if ($claimAttempts >= 5) {
            abort(429, 'Muitas tentativas de reivindicação para este jogo.');
        }
        
        // Incrementar contador de tentativas
        Cache::put($claimKey, $claimAttempts + 1, 300); // 5 minutos
    }
} 