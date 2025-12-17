<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Wallet;
use App\Models\RouletteItem;
use App\Models\RouletteSpin;

class RouletteController extends Controller
{
    /**
     * Processar o giro da roleta
     */
    public function spin(Request $request)
    {
        try {
            $user = Auth::user();
            $isGuest = !$user;
            
            // Para usuários deslogados, permitir giro gratuito sempre
            if ($isGuest) {
                Log::info('Usuário convidado girando a roleta', [
                    'session_id' => session()->getId(),
                    'ip' => $request->ip()
                ]);
                
                return $this->handleGuestSpin($request);
            }

            // Verificar se o usuário pode girar hoje (logado)
            $canSpinFree = $this->canUserSpinFree($user);
            
            if (!$canSpinFree && !$this->canUserSpin($user)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você já atingiu o limite de giros por dia. Deposite para resgatar mais prêmios!'
                ], 400);
            }

            Log::info('Usuário girando a roleta', [
                'user_id' => $user->id,
                'type' => $request->input('type', 'default')
            ]);

            // Obter itens da roleta do banco de dados
            $rouletteItems = $this->getRouletteItemsFromDatabase();
            
            if ($rouletteItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhum item de roleta encontrado'
                ], 404);
            }

            // Selecionar item aleatório baseado na probabilidade
            $selectedItem = $this->selectRandomItem($rouletteItems);
            
            if (!$selectedItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao selecionar item'
                ], 500);
            }

            // Processar prêmio
            $prizeResult = $this->processPrize($user, $selectedItem, $canSpinFree);
            
            // Registrar o giro
            $this->recordSpin($user, $selectedItem, $prizeResult, $canSpinFree);

            return response()->json([
                'success' => true,
                'selectedItem' => $selectedItem,  // Mudança: selectedItem em camelCase
                'selected_item' => $selectedItem, // Mantém compatibilidade
                'prize_result' => $prizeResult,
                'is_free_spin' => $canSpinFree,
                'message' => 'Giro realizado com sucesso!'
            ]);

        } catch (\Exception $e) {
            Log::error('Erro no giro da roleta', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Verificar se o usuário pode girar hoje
     */
    private function canUserSpin($user)
    {
        $spinsToday = RouletteSpin::countUserSpinsToday($user->id);
        return $spinsToday < 5;
    }

    /**
     * Verificar se o usuário pode girar gratuitamente hoje (1 giro grátis por dia)
     */
    private function canUserSpinFree($user)
    {
        $freeSpinsToday = RouletteSpin::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->where('is_free_spin', true)
            ->count();
        
        return $freeSpinsToday < 1;
    }

    /**
     * Verificar se o convidado pode girar hoje (1 giro por dia por IP/session)
     */
    private function canGuestSpin($request)
    {
        $ipAddress = $request->ip();
        $sessionId = session()->getId();
        
        // Verificar por IP (principal)
        $ipSpinsToday = RouletteSpin::where('ip_address', $ipAddress)
            ->whereNull('user_id')
            ->whereDate('created_at', today())
            ->count();
        
        // Verificar por session (backup)
        $sessionKey = 'guest_roulette_spin_' . date('Y-m-d');
        $sessionSpinUsed = session($sessionKey, false);
        
        return $ipSpinsToday == 0 && !$sessionSpinUsed;
    }

    /**
     * Registrar giro do convidado
     */
    private function recordGuestSpin($request, $selectedItem)
    {
        $ipAddress = $request->ip();
        
        // Registrar no banco
        RouletteSpin::create([
            'user_id' => null,
            'item_id' => $selectedItem->id,
            'item_name' => $selectedItem->name,
            'coupon_code' => $selectedItem->coupon_code,
            'prize_type' => $selectedItem->hasFreeSpins() ? 'free_spins' : 'coupon',
            'prize_awarded' => 0, // Convidados não recebem prêmio direto
            'is_free_spin' => false,
            'ip_address' => $ipAddress
        ]);
        
        // Marcar na sessão como backup
        $sessionKey = 'guest_roulette_spin_' . date('Y-m-d');
        session([$sessionKey => true]);
        
        Log::info('Giro de convidado registrado', [
            'ip' => $ipAddress,
            'item_id' => $selectedItem->id,
            'item_name' => $selectedItem->name
        ]);
    }

    /**
     * Processar giro para usuários convidados (deslogados)
     */
    private function handleGuestSpin($request)
    {
        try {
            // Verificar se o convidado já girou hoje
            if (!$this->canGuestSpin($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você já girou hoje! Volte amanhã ou cadastre-se para mais giros.',
                    'guest_limit_reached' => true
                ], 429);
            }

            // Obter itens da roleta
            $rouletteItems = $this->getRouletteItemsFromDatabase();
            
            if ($rouletteItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhum item de roleta encontrado'
                ], 404);
            }

            // Selecionar item aleatório
            $selectedItem = $this->selectRandomItem($rouletteItems);
            
            // Registrar o giro do convidado
            $this->recordGuestSpin($request, $selectedItem);
            
            // Para convidados, sempre retornar com cupom para resgatar
            $prizeResult = [
                'free_spins_awarded' => 0,
                'coupon_code' => $selectedItem->coupon_code,
                'message' => 'Cadastre-se e deposite para resgatar seu prêmio!',
                'requires_deposit' => true,
                'deposit_value' => $selectedItem->deposit_value
            ];

            return response()->json([
                'success' => true,
                'selectedItem' => $selectedItem,
                'selected_item' => $selectedItem,
                'prize_result' => $prizeResult,
                'is_guest' => true,
                'guest_daily_spin_used' => true,
                'message' => 'Giro realizado com sucesso! Cadastre-se para resgatar!'
            ]);

        } catch (\Exception $e) {
            Log::error('Erro no giro para convidado', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Processar prêmio do usuário
     */
    private function processPrize($user, $selectedItem, $isFreeSpinDay = false)
    {
        $prizeResult = [
            'free_spins_awarded' => 0,
            'coupon_code' => null,
            'message' => 'Prêmio processado',
            'requires_deposit' => false,
            'deposit_value' => 0
        ];

        try {
            // Se for o giro grátis do dia, dar o prêmio direto
            if ($isFreeSpinDay && $selectedItem->hasFreeSpins()) {
                $wallet = $user->wallet ?? $user->wallet()->create([
                    'user_id' => $user->id,
                    'balance' => 0,
                    'balance_bonus' => 0,
                    'free_spins' => 0
                ]);
                
                // Adicionar giros grátis diretamente
                $wallet->increment('free_spins', $selectedItem->free_spins);
                
                $prizeResult['free_spins_awarded'] = $selectedItem->free_spins;
                $prizeResult['message'] = "Parabéns! Você ganhou {$selectedItem->free_spins} giros grátis no seu giro diário!";
                
                Log::info('Giros grátis adicionados (giro diário)', [
                    'user_id' => $user->id,
                    'free_spins' => $selectedItem->free_spins,
                    'game_name' => $selectedItem->game_name
                ]);
            } elseif ($selectedItem->hasFreeSpins()) {
                // Para giros pagos, mostrar cupom para resgatar
                $prizeResult['coupon_code'] = $selectedItem->coupon_code;
                $prizeResult['requires_deposit'] = true;
                $prizeResult['deposit_value'] = $selectedItem->deposit_value;
                $prizeResult['message'] = "Deposite e use o cupom para resgatar seus {$selectedItem->free_spins} giros grátis!";
            }

            // Se o item tem cupom e não foi processado ainda
            if ($selectedItem->hasCoupon() && !$prizeResult['coupon_code']) {
                $prizeResult['coupon_code'] = $selectedItem->coupon_code;
                $prizeResult['requires_deposit'] = true;
                $prizeResult['deposit_value'] = $selectedItem->deposit_value;
                $prizeResult['message'] = "Deposite e use o cupom: {$selectedItem->coupon_code}";
            }

            // Se não tem nenhum prêmio específico
            if (!$selectedItem->hasFreeSpins() && !$selectedItem->hasCoupon()) {
                $prizeResult['message'] = $selectedItem->name;
            }

        } catch (\Exception $e) {
            Log::error('Erro ao processar prêmio:', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'item_id' => $selectedItem->id
            ]);
        }

        return $prizeResult;
    }

    /**
     * Registrar giro no banco de dados
     */
    private function recordSpin($user, $selectedItem, $prizeResult, $isFreeSpinDay = false)
    {
        try {
            RouletteSpin::create([
                'user_id' => $user->id,
                'item_id' => $selectedItem->id,
                'item_name' => $selectedItem->name,
                'coupon_code' => $prizeResult['coupon_code'] ?? null,
                'prize_type' => $selectedItem->hasFreeSpins() ? 'free_spins' : ($selectedItem->hasCoupon() ? 'coupon' : 'none'),
                'prize_awarded' => $prizeResult['free_spins_awarded'] ?? 0,
                'is_free_spin' => $isFreeSpinDay,
                'ip_address' => request()->ip()
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao registrar giro:', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'item_id' => $selectedItem->id
            ]);
        }
    }

    /**
     * Obter itens da roleta do banco de dados
     */
    private function getRouletteItemsFromDatabase()
    {
        try {
            return RouletteItem::active()->get();
        } catch (\Exception $e) {
            Log::error('Erro ao buscar itens da roleta:', ['error' => $e->getMessage()]);
            return collect([]);
        }
    }

    /**
     * Selecionar item aleatório baseado na probabilidade
     */
    private function selectRandomItem($items)
    {
        try {
            if ($items->isEmpty()) {
                return null;
            }

            $totalProbability = $items->sum('probability');
            $random = mt_rand() / mt_getrandmax() * $totalProbability;
            
            $currentProbability = 0;
            foreach ($items as $item) {
                $currentProbability += $item->probability;
                if ($random <= $currentProbability) {
                    return $item;
                }
            }
            
            // Fallback para o primeiro item
            return $items->first();
        } catch (\Exception $e) {
            Log::error('Erro ao selecionar item aleatório:', ['error' => $e->getMessage()]);
            return $items->first();
        }
    }

    /**
     * Obter dados da roleta para o frontend
     */
    public function getRouletteData(Request $request)
    {
        try {
            $user = Auth::user();
            $rouletteItems = RouletteItem::active()->orderBy('id')->get();
            
            // Contar giros de hoje usando o model
            $spinsToday = 0;
            $freeSpinsUsed = 0;
            $canSpinFree = false;
            $guestCanSpin = false;
            $guestSpinUsed = false;
            
            if ($user) {
                // Usuário logado
                $spinsToday = RouletteSpin::countUserSpinsToday($user->id);
                $freeSpinsUsed = RouletteSpin::where('user_id', $user->id)
                    ->whereDate('created_at', today())
                    ->where('is_free_spin', true)
                    ->count();
                $canSpinFree = $freeSpinsUsed < 1;
            } else {
                // Usuário convidado
                $guestCanSpin = $this->canGuestSpin($request);
                $guestSpinUsed = !$guestCanSpin;
            }

            // Preparar segmentos para o frontend
            $segments = $rouletteItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'color' => $item->color_code,
                    'probability' => $item->probability,
                    'free_spins' => $item->free_spins,
                    'coupon_code' => $item->coupon_code,
                    'deposit_value' => $item->deposit_value
                ];
            });

            return response()->json([
                'success' => true,
                'items' => $rouletteItems,
                'segments' => $segments,
                'spins_today' => $spinsToday,
                'max_spins' => 5,
                'is_guest' => !$user,
                'can_spin_free' => $canSpinFree,
                'free_spins_used' => $freeSpinsUsed,
                'guest_can_spin' => $guestCanSpin,
                'guest_spin_used' => $guestSpinUsed
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao obter dados da roleta', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar dados da roleta'
            ], 500);
        }
    }

    /**
     * Obter histórico de giros do usuário
     */
    public function getSpinHistory(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuário não autenticado'
                ], 401);
            }

            $user = Auth::user();
            $spins = RouletteSpin::getUserHistory($user->id, 50);

            return response()->json([
                'success' => true,
                'spins' => $spins->map(function ($spin) {
                    return [
                        'id' => $spin->id,
                        'item_name' => $spin->item_name,
                        'coupon_code' => $spin->coupon_code,
                        'prize_type' => $spin->prize_type,
                        'prize_awarded' => $spin->prize_awarded,
                        'created_at' => $spin->created_at->format('d/m/Y H:i:s'),
                        'roulette_item' => $spin->rouletteItem ? [
                            'name' => $spin->rouletteItem->name,
                            'color_code' => $spin->rouletteItem->color_code
                        ] : null
                    ];
                })
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao obter histórico de giros', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar histórico'
            ], 500);
        }
    }
} 