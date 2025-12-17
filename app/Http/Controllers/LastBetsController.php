<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LastBetsController extends Controller
{
    /**
     * Obtém as últimas apostas para exibição na página inicial
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLastBets(Request $request)
    {
        try {
            $lastCheck = $request->get('last_check'); // Timestamp da última verificação
            $isInitialLoad = empty($lastCheck);
            
            // Cache mais curto para atualizações frequentes
            $cacheKey = $isInitialLoad ? 'last_bets_initial' : 'last_bets_updates_' . md5($lastCheck);
            $cacheTime = $isInitialLoad ? now()->addSeconds(15) : now()->addSeconds(5);
            
            $lastBets = Cache::remember($cacheKey, $cacheTime, function () use ($lastCheck, $isInitialLoad) {
                $query = DB::table('games_history')
                    ->join('users', 'games_history.user_id', '=', 'users.id')
                    ->leftJoin('games_api', function($join) {
                        $join->whereColumn('games_history.game', '=', 'games_api.slug')
                             ->where('games_api.status', 1);
                    })
                    ->leftJoin('providers', 'games_api.provider_id', '=', 'providers.id')
                    ->select(
                        'games_history.id',
                        'games_history.amount',
                        'games_history.action',
                        'users.name as user_name',
                        'games_api.name as game_name',
                        'games_api.image as game_image',
                        'games_api.id as game_id',
                        'providers.name as provider_name',
                        'games_history.created_at'
                    )
                    ->where('games_history.action', 'win')
                    ->where('games_history.amount', '>', 0)
                    ->whereNotNull('users.name')
                    ->whereNotNull('games_api.name')
                    ->orderBy('games_history.created_at', 'desc');
                
                // Se não for carregamento inicial, buscar apenas apostas mais recentes
                if (!$isInitialLoad && $lastCheck) {
                    $query->where('games_history.created_at', '>', $lastCheck);
                    $query->limit(50); // Limite maior para atualizações
                } else {
                    $query->limit(20); // Carregamento inicial
                }
                
                return $query->get();
            });
            
            // Se não houver apostas, retornar array vazio
            if ($lastBets->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'bets' => [],
                    'total' => 0,
                    'is_update' => !$isInitialLoad,
                    'last_check' => now()->toDateTimeString(),
                    'message' => $isInitialLoad ? 'Nenhuma aposta encontrada' : 'Nenhuma nova aposta'
                ]);
            }
            
            // Processar os dados para exibição
            $formattedBets = [];
            foreach ($lastBets as $bet) {
                // Pular apostas sem dados essenciais
                if (empty($bet->user_name) || empty($bet->game_name) || $bet->amount <= 0) {
                    continue;
                }
                
                // Mascarar o nome do usuário para privacidade
                $userName = $bet->user_name;
                if (strlen($userName) > 3) {
                    $userName = substr($userName, 0, 3) . '***';
                } else {
                    $userName = $userName . '***';
                }
                
                // Tratar imagem do jogo - apenas usar se existir e for válida
                $gameImage = null;
                if (!empty($bet->game_image)) {
                    if (str_starts_with($bet->game_image, 'http')) {
                        $gameImage = $bet->game_image;
                    } else {
                        // Verificar se o arquivo existe antes de usar
                        $imagePath = storage_path('app/public/' . $bet->game_image);
                        if (file_exists($imagePath)) {
                            $gameImage = env('APP_URL', 'https://'.request()->getHost()) . '/storage/' . $bet->game_image;
                        }
                    }
                }
                
                // Calcular valor anterior aleatório entre 10% a 50% da aposta ganha
                $randomPercentage = mt_rand(10, 50);
                $previousAmount = ($bet->amount * $randomPercentage) / 100;
                
                $formattedBets[] = [
                    'id' => $bet->id,
                    'amount' => $bet->amount,
                    'amount_formatted' => number_format($bet->amount, 2, ',', '.'),
                    'previous_amount' => $previousAmount,
                    'previous_amount_formatted' => number_format($previousAmount, 2, ',', '.'),
                    'action' => $bet->action,
                    'user_name' => $userName,
                    'game_name' => $bet->game_name,
                    'game_image' => $gameImage,
                    'game_id' => $bet->game_id,
                    'created_at' => $bet->created_at,
                    'time_ago' => $this->timeAgo($bet->created_at),
                    'timestamp' => strtotime($bet->created_at)
                ];
            }
            
            // Ordenar por timestamp mais recente primeiro
            usort($formattedBets, function($a, $b) {
                return $b['timestamp'] - $a['timestamp'];
            });
            
            return response()->json([
                'success' => true,
                'bets' => $formattedBets,
                'total' => count($formattedBets),
                'is_update' => !$isInitialLoad,
                'last_check' => now()->toDateTimeString(),
                'has_new_bets' => count($formattedBets) > 0
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao obter últimas apostas: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor ao buscar últimas apostas',
                'bets' => [],
                'total' => 0,
                'is_update' => !empty($request->get('last_check')),
                'last_check' => now()->toDateTimeString()
            ], 500);
        }
    }
    
    /**
     * Calcula o tempo decorrido desde a aposta
     *
     * @param string $datetime
     * @return string
     */
    private function timeAgo($datetime)
    {
        try {
            $time = time() - strtotime($datetime);
            
            if ($time < 60) {
                return 'agora';
            } elseif ($time < 3600) {
                $minutes = floor($time / 60);
                return $minutes . 'm atrás';
            } elseif ($time < 86400) {
                $hours = floor($time / 3600);
                return $hours . 'h atrás';
            } else {
                $days = floor($time / 86400);
                return $days . 'd atrás';
            }
        } catch (\Exception $e) {
            return 'há pouco';
        }
    }
} 