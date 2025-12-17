<?php

namespace App\Http\Controllers;

use App\Models\Raspadinha;
use App\Models\RaspadinhaHistory;
use App\Models\Admin\Logs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RaspadinhaController extends Controller
{
    /**
     * Mostrar página principal das raspadinhas
     */
    public function index()
    {
        $perPage = 12; // Número de raspadinhas por página
        
        $query = Raspadinha::active()->with('items');
        
        // Aplicar filtros se existirem
        if (request()->filled('status') && request('status') !== 'all') {
            if (request('status') === 'active') {
                $query->where('is_active', true);
            } elseif (request('status') === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        if (request()->filled('price') && request('price') !== 'all') {
            switch (request('price')) {
                case 'low':
                    $query->where('price', '<=', 5.00);
                    break;
                case 'medium':
                    $query->whereBetween('price', [5.01, 20.00]);
                    break;
                case 'high':
                    $query->where('price', '>', 20.00);
                    break;
            }
        }
        
        if (request()->filled('search')) {
            $searchTerm = request('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }
        
        $total = $query->count();
        $raspadinhas = $query->take($perPage)->get();
        $current = min($perPage, $total);
        
        // Buscar ganhadores recentes
        $recentWinners = RaspadinhaHistory::with(['user', 'raspadinha'])
            ->where('amount_won', '>', 0)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('raspadinha.index', compact('raspadinhas', 'recentWinners', 'current', 'total'));
    }

    /**
     * Carregar mais raspadinhas via AJAX
     */
    public function carregarMais(Request $request)
    {
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 12);
        $offset = ($page - 1) * $perPage;
        
        $query = Raspadinha::active()->with('items');
        
        // Aplicar filtros
        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        // Filtro de múltiplos status
        if ($request->filled('statuses')) {
            $statuses = json_decode($request->statuses, true);
            if (is_array($statuses) && count($statuses) > 0) {
                $query->where(function($q) use ($statuses) {
                    foreach ($statuses as $status) {
                        if ($status === 'active') {
                            $q->orWhere('is_active', true);
                        } elseif ($status === 'inactive') {
                            $q->orWhere('is_active', false);
                        }
                    }
                });
            }
        }
        
        if ($request->filled('price') && $request->price !== 'all') {
            switch ($request->price) {
                case 'low':
                    $query->where('price', '<=', 5.00);
                    break;
                case 'medium':
                    $query->whereBetween('price', [5.01, 20.00]);
                    break;
                case 'high':
                    $query->where('price', '>', 20.00);
                    break;
            }
        }
        
        // Filtro de múltiplos preços
        if ($request->filled('prices')) {
            $prices = json_decode($request->prices, true);
            if (is_array($prices) && count($prices) > 0) {
                $query->where(function($q) use ($prices) {
                    foreach ($prices as $price) {
                        switch ($price) {
                            case 'low':
                                $q->orWhere('price', '<=', 5.00);
                                break;
                            case 'medium':
                                $q->orWhereBetween('price', [5.01, 20.00]);
                                break;
                            case 'high':
                                $q->orWhere('price', '>', 20.00);
                                break;
                        }
                    }
                });
            }
        }
        
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }
        
        $total = $query->count();
        $raspadinhas = $query->skip($offset)->take($perPage)->get();
        
        // Preparar dados das raspadinhas para AJAX
        $raspadinhasData = $raspadinhas->map(function($raspadinha) {
            $stats = $raspadinha->getProbabilityStats();
            $prizeTypes = $raspadinha->items()->active()
                ->selectRaw('DISTINCT premio_type')
                ->pluck('premio_type')
                ->map(function($type) {
                    return match($type ?? 'saldo_real') {
                        'saldo_real' => '💰',
                        'saldo_bonus' => '🎁',
                        'rodadas_gratis' => '🎰',
                        'produto' => '📱',
                        default => '💰'
                    };
                })
                ->join(' ');
            
            return [
                'id' => $raspadinha->id,
                'name' => $raspadinha->name,
                'description' => $raspadinha->description ?? 'Raspe e ganhe!',
                'price' => $raspadinha->price,
                'turbo_price' => $raspadinha->turbo_price,
                'image_url' => $raspadinha->image_url ?? asset('img/raspadinha/default.png'),
                'win_chance' => $stats['win_chance'] ?? 0,
                'prize_types' => $prizeTypes ?: '💰'
            ];
        });
        
        return response()->json([
            'success' => true,
            'raspadinhas' => $raspadinhasData,
            'page' => $page,
            'total' => $total,
            'per_page' => $perPage,
            'has_more' => ($offset + $perPage) < $total
        ]);
    }

    /**
     * Mostrar página de jogo de uma raspadinha específica
     */
    public function show(Raspadinha $raspadinha)
    {
        if (!$raspadinha->is_active) {
            return redirect()->route('raspadinha.index')
                ->with('error', 'Esta raspadinha não está disponível.');
        }

        // Verificar se o usuário está autenticado
        if (!Auth::check()) {
            return redirect()->route('home')
                ->with('error', 'Você precisa estar logado para acessar a raspadinha.');
        }

        // Detectar se é mobile e redirecionar para versão mobile
        $userAgent = request()->header('User-Agent', '');
        $isMobile = preg_match('/(android|iphone|ipad|ipod|mobile|blackberry|opera mini|windows phone|iemobile|webos|palm|symbian)/i', $userAgent);
        
        // Verificar também pela largura da tela via cookie ou header
        $screenWidth = request()->header('X-Screen-Width');
        if ($screenWidth && $screenWidth <= 768) {
            $isMobile = true;
        }
        
        if ($isMobile) {
            return redirect()->route('raspadinha.show.mobile', $raspadinha);
        }

        $user = Auth::user();
        
        // Buscar prêmios recentes desta raspadinha
        $recentPrizes = RaspadinhaHistory::with('user')
            ->where('raspadinha_id', $raspadinha->id)
            ->where('amount_won', '>', 0)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Estatísticas do usuário
        $userStats = [
            'total_plays' => RaspadinhaHistory::getTotalPlaysByUser($user->id),
            'total_won' => RaspadinhaHistory::getTotalWonByUser($user->id),
            'total_invested' => RaspadinhaHistory::getTotalInvestedByUser($user->id),
        ];

        return view('raspadinha.game', compact('raspadinha', 'recentPrizes', 'userStats'));
    }

    /**
     * Exibir página de jogo da raspadinha para mobile
     */
    public function showMobile(Raspadinha $raspadinha)
    {
        if (!$raspadinha->is_active) {
            return redirect()->route('raspadinha.index')
                ->with('error', 'Esta raspadinha não está disponível.');
        }

        // Verificar se o usuário está autenticado
        if (!Auth::check()) {
            return redirect()->route('home')
                ->with('error', 'Você precisa estar logado para acessar a raspadinha.');
        }

        // Detectar se é desktop e redirecionar para versão desktop
        $userAgent = request()->header('User-Agent');
        $isMobile = preg_match('/(android|iphone|ipad|mobile|blackberry|opera mini)/i', $userAgent);
        
        if (!$isMobile) {
            return redirect()->route('raspadinha.show', $raspadinha);
        }

        $user = Auth::user();
        
        // Buscar prêmios recentes desta raspadinha
        $recentPrizes = RaspadinhaHistory::with('user')
            ->where('raspadinha_id', $raspadinha->id)
            ->where('amount_won', '>', 0)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Estatísticas do usuário
        $userStats = [
            'total_plays' => RaspadinhaHistory::getTotalPlaysByUser($user->id),
            'total_won' => RaspadinhaHistory::getTotalWonByUser($user->id),
            'total_invested' => RaspadinhaHistory::getTotalInvestedByUser($user->id),
        ];

        return view('raspadinha.game_mobile', compact('raspadinha', 'recentPrizes', 'userStats'));
    }

    /**
     * Jogar raspadinha (uma única vez)
     */
    public function play(Request $request, Raspadinha $raspadinha)
    {
        $validator = Validator::make($request->all(), [
            'is_turbo' => 'boolean',
            'session_token' => 'sometimes|string',
            'csrf_token' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Dados inválidos']);
        }

        if (!$raspadinha->is_active) {
            return response()->json(['success' => false, 'message' => 'Esta raspadinha não está disponível']);
        }

        $user = Auth::user();
        $securityService = app(\App\Services\RaspadinhaSecurityService::class);
        
        // Validar token CSRF se fornecido
        if ($request->has('csrf_token')) {
            if (!$securityService->validateAntiCSRFToken($request->csrf_token, $user->id, 'play')) {
                return response()->json(['success' => false, 'message' => 'Token de segurança inválido']);
            }
        }

        $wallet = $user->wallet;
        
        if (!$wallet) {
            // Tentar criar carteira se não existir
            try {
                $wallet = Wallet::create([
                    'user_id' => $user->id,
                    'balance' => 0.00,
                    'balance_bonus' => 0.00,
                    'coin' => 0,
                    'free_spins' => 0
                ]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Erro ao acessar carteira. Contate o suporte.']);
            }
        }

        $isTurbo = $request->boolean('is_turbo');
        $price = $isTurbo ? $raspadinha->turbo_price : $raspadinha->price;

        // Verificar saldo
        if ($wallet->balance < $price) {
            return response()->json([
                'success' => false, 
                'message' => 'Saldo insuficiente. Você precisa de R$ ' . number_format($price, 2, ',', '.')
            ]);
        }

        return DB::transaction(function () use ($raspadinha, $user, $wallet, $price, $isTurbo, $request) {
            try {
                $securityService = app(\App\Services\RaspadinhaSecurityService::class);
                
                // Debitar valor da carteira IMEDIATAMENTE
                $oldBalance = $wallet->balance;
                $wallet->balance -= $price;
                $wallet->save();

                // Gerar resultados (aplicar boost se for turbo)
                $results = $raspadinha->generateResults($isTurbo);
                $prizeInfo = $raspadinha->calculatePrize($results);
                
                // Adicionar timestamp para validação
                $gameData = [
                    'results' => $results,
                    'prize_info' => $prizeInfo,
                    'timestamp' => now()->timestamp,
                    'is_turbo' => $isTurbo,
                    'price_paid' => $price
                ];

                // Encontrar o item premiado se houver
                $prizedItem = null;
                if ($prizeInfo['has_prize']) {
                    $prizedItem = $raspadinha->items()
                        ->where('id', $prizeInfo['item_id'])
                        ->where('is_active', true)
                        ->first();
                }

                // Criar histórico com status 'pending' (prêmio ainda não foi processado)
                $history = RaspadinhaHistory::create([
                    'user_id' => $user->id,
                    'raspadinha_id' => $raspadinha->id,
                    'raspadinha_item_id' => $prizedItem ? $prizedItem->id : null,
                    'amount_paid' => $price,
                    'amount_won' => $prizeInfo['prize_value'],
                    'is_turbo' => $isTurbo,
                    'is_auto' => false,
                    'status' => 'pending', // Status pending até raspar
                    'results' => $results,
                    'prize_type' => $prizeInfo['prize_type'],
                    'prize_description' => $prizeInfo['prize_description'],
                ]);

                // Criar sessão segura de jogo
                $gameData['history_id'] = $history->id;
                $sessionInfo = $securityService->createGameSession($user->id, $raspadinha->id, $gameData);
                
                // Atualizar sessão para estado 'playing'
                $securityService->updateSessionState($sessionInfo['session_id'], 'playing', ['history_id' => $history->id]);

                // NÃO processar prêmio ainda - será processado em claimPrize()

                // Log da jogada (apenas débito)
                Logs::create([
                    'field_name' => 'Raspadinha - Jogada',
                    'old_value' => $oldBalance,
                    'new_value' => $wallet->balance,
                    'updated_by' => 1,
                    'user_id' => $user->id,
                    'type' => 1,
                    'log' => "Jogada em raspadinha: R$ {$price} - {$raspadinha->name}" . ($isTurbo ? ' (Turbo)' : '') . ' - Aguardando raspagem'
                ]);

                // Gerar grid 3x3 para exibição baseado na lógica de vitória/derrota
                $items = $raspadinha->items()->active()->get();
                $grid = [];
                
                if ($prizeInfo['has_prize']) {
                    // VITÓRIA: Mostrar exatamente 3x o item premiado + 6 outros diferentes
                    $winningItemId = $prizeInfo['item_id'];
                    
                    // Adicionar 3x o item vencedor
                    for ($i = 0; $i < 3; $i++) {
                        $grid[] = $winningItemId;
                    }
                    
                    // Adicionar 6 itens diferentes (não repetir o item vencedor)
                    $otherItems = $items->where('id', '!=', $winningItemId);
                    $usedItems = [$winningItemId]; // Controlar itens já usados
                    
                    for ($i = 0; $i < 6; $i++) {
                        // Buscar items que ainda não foram usados muito
                        $availableItems = $otherItems->filter(function($item) use ($usedItems) {
                            $itemCount = array_count_values($usedItems)[$item->id] ?? 0;
                            return $itemCount < 2; // Máximo 2 de cada item não vencedor
                        });
                        
                        if ($availableItems->count() > 0) {
                            $selectedItem = $availableItems->random();
                            $grid[] = $selectedItem->id;
                            $usedItems[] = $selectedItem->id;
                        } else {
                            // Se não há items disponíveis, usar items com valor 0.00
                            $zeroValueItems = $items->where('value', 0.00)->where('id', '!=', $winningItemId);
                            if ($zeroValueItems->count() > 0) {
                                $selectedItem = $zeroValueItems->random();
                                $grid[] = $selectedItem->id;
                            } else {
                                // Fallback: usar qualquer item diferente do vencedor
                                if ($otherItems->count() > 0) {
                                    $grid[] = $otherItems->random()->id;
                                }
                            }
                        }
                    }
                } else {
                    // DERROTA: Garantir que nenhum item aparece 3 vezes
                    $itemCounts = [];
                    
                    for ($i = 0; $i < 9; $i++) {
                        // Filtrar items que ainda podem ser usados (máximo 2 vezes cada)
                        $availableItems = $items->filter(function($item) use ($itemCounts) {
                            return ($itemCounts[$item->id] ?? 0) < 2;
                        });
                        
                        if ($availableItems->count() > 0) {
                            $selectedItem = $availableItems->random();
                            $itemCounts[$selectedItem->id] = ($itemCounts[$selectedItem->id] ?? 0) + 1;
                            $grid[] = $selectedItem->id;
                        } else {
                            // Se não há items disponíveis, usar qualquer item
                            $selectedItem = $items->random();
                            $grid[] = $selectedItem->id;
                        }
                    }
                }
                
                // Embaralhar o grid para randomizar as posições
                shuffle($grid);

                return response()->json([
                    'success' => true,
                    'results' => $results,
                    'grid' => $grid, // Grid 3x3 para exibição
                    'prize_info' => $prizeInfo,
                    'prize_value' => $prizeInfo['prize_value'],
                    'amount_won' => $prizeInfo['prize_value'],
                    'formatted_prize' => $this->formatPrizeDisplay($prizeInfo),
                    'new_balance' => $wallet->balance, // Saldo após débito, mas antes do prêmio
                    'formatted_balance' => 'R$ ' . number_format($wallet->balance, 2, ',', '.'),
                    'is_winner' => $prizeInfo['has_prize'],
                    'history_id' => $history->id, // ID necessário para claimPrize
                    'session_token' => $sessionInfo['session_token'], // Token de segurança da sessão
                    'session_expires' => $sessionInfo['expires_at'],
                    'game_checksum' => $securityService->generateGameDataChecksum($gameData, $user->id, $raspadinha->id),
                    'items' => $raspadinha->items()->active()->get()->map(function($item) {
                        return [
                            'id' => $item->id,
                            'name' => $item->name,
                            'image_url' => $item->image_url,
                            'formatted_value' => $item->formatted_value,
                            'premio_description' => $item->premio_description,
                            'is_product' => $item->isProduct()
                        ];
                    })
                ]);

            } catch (\Exception $e) {
                \Log::error('RaspadinhaController@play - Error in transaction: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Erro interno. Tente novamente.'
                ]);
            }
        });
    }

    /**
     * Processar prêmio após raspagem
     */
    public function claimPrize(Request $request, Raspadinha $raspadinha)
    {
        $validator = Validator::make($request->all(), [
            'history_id' => 'required|integer|exists:raspadinha_history,id',
            'session_token' => 'sometimes|string', // Tornar opcional
            'game_checksum' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Dados inválidos']);
        }

        $user = Auth::user();
        $historyId = $request->input('history_id');
        $sessionToken = $request->input('session_token');
        $gameChecksum = $request->input('game_checksum');
        
        $securityService = app(\App\Services\RaspadinhaSecurityService::class);
        
        // VALIDAÇÃO CRÍTICA: Verificar sessão apenas se o token foi fornecido
        if ($sessionToken && !$securityService->validateClaimPrize($sessionToken, $historyId)) {
            return response()->json(['success' => false, 'message' => 'Sessão inválida ou expirada']);
        }

        // Buscar o histórico
        $history = RaspadinhaHistory::where('id', $historyId)
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if (!$history) {
            return response()->json(['success' => false, 'message' => 'Jogo não encontrado ou já processado']);
        }

        return DB::transaction(function () use ($history, $user, $raspadinha, $sessionToken, $gameChecksum, $securityService) {
            try {
                $wallet = $user->wallet;
                if (!$wallet) {
                    return response()->json(['success' => false, 'message' => 'Carteira não encontrada']);
                }
                
                // Atualizar estado da sessão para 'claiming' apenas se temos sessionToken
                $sessionData = null;
                if ($sessionToken) {
                    $sessionData = $securityService->validateGameSession($sessionToken, $user->id);
                    if ($sessionData) {
                        $securityService->updateSessionState($sessionData['session_id'], 'claiming');
                    }
                }

                // Reconstruir informações do prêmio
                $prizeInfo = [
                    'has_prize' => $history->amount_won > 0,
                    'prize_value' => $history->amount_won,
                    'prize_type' => $history->prize_type,
                    'prize_description' => $history->prize_description,
                    'item_id' => $history->raspadinha_item_id
                ];

                $prizedItem = null;
                if ($history->raspadinha_item_id) {
                    $prizedItem = $raspadinha->items()
                        ->where('id', $history->raspadinha_item_id)
                        ->where('is_active', true)
                        ->first();
                }

                // Processar prêmio AGORA
                $prizeProcessed = $this->processPrize($user, $wallet, $prizeInfo, $prizedItem);

                // Atualizar status do histórico para 'completed'
                $history->status = 'completed';
                $history->save();

                // Finalizar sessão de segurança apenas se temos sessionData
                if ($sessionData) {
                    $securityService->updateSessionState($sessionData['session_id'], 'completed');
                }

                // Recarregar carteira para obter saldo atualizado
                $wallet->refresh();

                return response()->json([
                    'success' => true,
                    'prize_processed' => $prizeProcessed,
                    'new_balance' => $wallet->balance,
                    'new_bonus_balance' => $wallet->balance_bonus,
                    'new_free_spins' => $wallet->free_spins,
                    'formatted_balance' => 'R$ ' . number_format($wallet->balance, 2, ',', '.'),
                    'formatted_bonus_balance' => 'R$ ' . number_format($wallet->balance_bonus, 2, ',', '.'),
                    'is_winner' => $prizeInfo['has_prize'],
                    'amount_won' => $prizeInfo['prize_value'],
                    'prize_info' => $prizeInfo
                ]);

            } catch (\Exception $e) {
                \Log::error('RaspadinhaController@claimPrize - Error in transaction: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Erro interno ao processar prêmio.'
                ]);
            }
        });
    }

    /**
     * Jogar raspadinha automaticamente (múltiplas vezes)
     */
    public function playAuto(Request $request, Raspadinha $raspadinha)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1|max:100',
            'is_turbo' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Dados inválidos']);
        }

        if (!$raspadinha->is_active) {
            return response()->json(['success' => false, 'message' => 'Esta raspadinha não está disponível']);
        }

        $user = Auth::user();
        $wallet = $user->wallet;
        
        if (!$wallet) {
            return response()->json(['success' => false, 'message' => 'Carteira não encontrada']);
        }

        $quantity = $request->integer('quantity');
        $isTurbo = $request->boolean('is_turbo');
        $price = $isTurbo ? $raspadinha->turbo_price : $raspadinha->price;
        $totalCost = $price * $quantity;

        // Verificar saldo
        if ($wallet->balance < $totalCost) {
            return response()->json([
                'success' => false, 
                'message' => 'Saldo insuficiente. Você precisa de R$ ' . number_format($totalCost, 2, ',', '.')
            ]);
        }

        return DB::transaction(function () use ($raspadinha, $user, $wallet, $price, $quantity, $isTurbo, $totalCost) {
            try {
                $oldBalance = $wallet->balance;
                $results = [];
                $totalPrize = 0;

                // Debitar valor total da carteira
                $wallet->balance -= $totalCost;
                $wallet->save();

                // Jogar múltiplas vezes
                for ($i = 0; $i < $quantity; $i++) {
                    $gameResults = $raspadinha->generateResults($isTurbo);
                    $prizeInfo = $raspadinha->calculatePrize($gameResults);
                    
                    // Encontrar o item premiado se houver
                    $prizedItem = null;
                    if ($prizeInfo['has_prize']) {
                        $prizedItem = $raspadinha->items()
                            ->where('id', $prizeInfo['item_id'])
                            ->where('is_active', true)
                            ->first();
                    }

                    // Criar histórico para cada jogada
                    $history = RaspadinhaHistory::create([
                        'user_id' => $user->id,
                        'raspadinha_id' => $raspadinha->id,
                        'raspadinha_item_id' => $prizedItem ? $prizedItem->id : null,
                        'amount_paid' => $price,
                        'amount_won' => $prizeInfo['prize_value'],
                        'prize_type' => $prizeInfo['prize_type'],
                        'prize_description' => $prizeInfo['prize_description'],
                        'is_turbo' => $isTurbo,
                        'is_auto' => true,
                        'auto_quantity' => $quantity,
                        'status' => 'completed',
                        'results' => $gameResults,
                    ]);

                    $results[] = [
                        'game' => $i + 1,
                        'results' => $gameResults,
                        'prize_info' => $prizeInfo,
                        'prize_value' => $prizeInfo['prize_value'],
                        'history_id' => $history->id,
                        'is_winner' => $prizeInfo['has_prize']
                    ];

                    $totalPrize += $prizeInfo['prize_value'];
                }

                // Processar prêmios por tipo
                $realBalance = 0;
                $bonusBalance = 0;
                $freeSpins = 0;
                
                foreach ($results as $result) {
                    if ($result['prize_info']['has_prize']) {
                        switch ($result['prize_info']['prize_type']) {
                            case 'saldo_real':
                                $realBalance += $result['prize_info']['prize_value'];
                                break;
                            case 'saldo_bonus':
                                $bonusBalance += $result['prize_info']['prize_value'];
                                break;
                            case 'rodadas_gratis':
                                $freeSpins += $result['prize_info']['prize_value'];
                                break;
                            // Produtos não creditam valor monetário
                        }
                    }
                }

                // Creditar prêmios nas colunas apropriadas
                $balanceUpdated = false;
                if ($realBalance > 0) {
                    $wallet->balance += $realBalance;
                    $balanceUpdated = true;
                }
                if ($bonusBalance > 0) {
                    $wallet->balance_bonus += $bonusBalance;
                    $balanceUpdated = true;
                }
                if ($freeSpins > 0) {
                    $wallet->free_spins += $freeSpins;
                    $balanceUpdated = true;
                }
                
                if ($balanceUpdated) {
                    $wallet->save();

                    // Log do prêmio total
                    $logMessages = [];
                    if ($realBalance > 0) $logMessages[] = "Saldo Real: R$ {$realBalance}";
                    if ($bonusBalance > 0) $logMessages[] = "Saldo Bônus: R$ {$bonusBalance}";
                    if ($freeSpins > 0) $logMessages[] = "Rodadas Grátis: {$freeSpins}";
                    
                    Logs::create([
                        'field_name' => 'Raspadinha - Prêmio Auto',
                        'old_value' => $oldBalance - $totalCost,
                        'new_value' => $wallet->balance,
                        'updated_by' => 1,
                        'user_id' => $user->id,
                        'type' => 1,
                        'log' => "Prêmios de {$quantity} raspadinhas: " . implode(', ', $logMessages) . " - {$raspadinha->name}"
                    ]);
                }

                // Log da jogada auto
                Logs::create([
                    'field_name' => 'Raspadinha - Jogada Auto',
                    'old_value' => $oldBalance,
                    'new_value' => $oldBalance - $totalCost,
                    'updated_by' => 1,
                    'user_id' => $user->id,
                    'type' => 1,
                    'log' => "Jogada automática de {$quantity} raspadinhas: R$ {$totalCost} - {$raspadinha->name}" . ($isTurbo ? ' (Turbo)' : '')
                ]);

                return response()->json([
                    'success' => true,
                    'results' => $results,
                    'total_cost' => $totalCost,
                    'total_prize' => $totalPrize,
                    'prizes' => [
                        'saldo_real' => $realBalance,
                        'saldo_bonus' => $bonusBalance,
                        'rodadas_gratis' => $freeSpins,
                        'formatted_real' => 'R$ ' . number_format($realBalance, 2, ',', '.'),
                        'formatted_bonus' => 'R$ ' . number_format($bonusBalance, 2, ',', '.'),
                        'formatted_spins' => $freeSpins . ' rodadas grátis'
                    ],
                    'formatted_total_cost' => 'R$ ' . number_format($totalCost, 2, ',', '.'),
                    'formatted_total_prize' => 'R$ ' . number_format($totalPrize, 2, ',', '.'),
                    'new_balance' => $wallet->balance,
                    'new_bonus_balance' => $wallet->balance_bonus,
                    'new_free_spins' => $wallet->free_spins,
                    'formatted_balance' => 'R$ ' . number_format($wallet->balance, 2, ',', '.'),
                    'formatted_bonus_balance' => 'R$ ' . number_format($wallet->balance_bonus, 2, ',', '.'),
                    'quantity' => $quantity,
                    'winners_count' => count(array_filter($results, function($r) { return $r['is_winner']; }))
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro interno. Tente novamente.'
                ]);
            }
        });
    }

    /**
     * Histórico de jogadas do usuário
     */
    public function history(Request $request)
    {
        $user = Auth::user();
        
        $history = RaspadinhaHistory::with(['raspadinha', 'raspadinhaItem'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('raspadinha.history', compact('history'));
    }

    /**
     * Obter saldo atual do usuário via AJAX
     */
    public function getBalance()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não autenticado'
            ], 401);
        }
        
        $wallet = $user->wallet;
        
        if (!$wallet) {
            return response()->json([
                'success' => false,
                'balance' => 0,
                'balance_bonus' => 0,
                'free_spins' => 0,
                'formatted_balance' => 'R$ 0,00',
                'formatted_balance_bonus' => 'R$ 0,00',
                'message' => 'Carteira não encontrada'
            ]);
        }
        
        return response()->json([
            'success' => true,
            'balance' => $wallet->balance,
            'balance_bonus' => $wallet->balance_bonus,
            'free_spins' => $wallet->free_spins,
            'formatted_balance' => 'R$ ' . number_format($wallet->balance, 2, ',', '.'),
            'formatted_balance_bonus' => 'R$ ' . number_format($wallet->balance_bonus, 2, ',', '.'),
        ]);
    }



         /**
      * Processar prêmio e creditar na carteira
      * @param $user
      * @param $wallet
      * @param array $prizeInfo
      * @param $prizedItem
      * @return array
      */
     protected function processPrize($user, $wallet, $prizeInfo, $prizedItem)
     {
         \Log::info('RaspadinhaController@processPrize - Iniciando processamento', [
             'user_id' => $user->id,
             'has_prize' => $prizeInfo['has_prize'],
             'prize_value' => $prizeInfo['prize_value'],
             'prize_type' => $prizeInfo['prize_type'],
             'item_id' => $prizeInfo['item_id'],
             'wallet_balance_before' => $wallet->balance
         ]);
         
         $prizeProcessed = [
             'success' => false,
             'message' => 'Nenhum prêmio',
             'type' => 'none'
         ];

         if ($prizeInfo['has_prize'] && $prizeInfo['prize_value'] > 0) {
             $oldBalance = $wallet->balance;
             
             // Se o prize_type não estiver definido, usar o tipo do item
             $prizeType = $prizeInfo['prize_type'];
             if (!$prizeType && $prizedItem) {
                 $prizeType = $prizedItem->premio_type ?? 'saldo_real';
             }
             
             // Default para saldo_real se não definido
             if (!$prizeType) {
                 $prizeType = 'saldo_real';
             }
             
             \Log::info('RaspadinhaController@processPrize - Processando prêmio', [
                 'prize_type_final' => $prizeType,
                 'prize_value' => $prizeInfo['prize_value'],
                 'old_balance' => $oldBalance
             ]);
             
             // Processar baseado no tipo de prêmio
             switch ($prizeType) {
                 case 'saldo_real':
                     // Creditar saldo real
                     $wallet->balance += $prizeInfo['prize_value'];
                     $wallet->save();
                     
                     \Log::info('RaspadinhaController@processPrize - Saldo real creditado', [
                         'amount' => $prizeInfo['prize_value'],
                         'old_balance' => $oldBalance,
                         'new_balance' => $wallet->balance
                     ]);
                     
                     $prizeProcessed = [
                         'success' => true,
                         'message' => 'Saldo real creditado com sucesso!',
                         'type' => $prizeType,
                         'prize_value' => $prizeInfo['prize_value'],
                         'formatted_prize' => $prizeInfo['prize_description']
                     ];
                     break;
                     
                 case 'saldo_bonus':
                     // Creditar saldo bônus
                     $wallet->balance_bonus += $prizeInfo['prize_value'];
                     $wallet->save();
                     
                     \Log::info('RaspadinhaController@processPrize - Saldo bônus creditado', [
                         'amount' => $prizeInfo['prize_value'],
                         'old_bonus_balance' => $wallet->balance_bonus - $prizeInfo['prize_value'],
                         'new_bonus_balance' => $wallet->balance_bonus
                     ]);
                     
                     $prizeProcessed = [
                         'success' => true,
                         'message' => 'Saldo bônus creditado com sucesso!',
                         'type' => $prizeType,
                         'prize_value' => $prizeInfo['prize_value'],
                         'formatted_prize' => $prizeInfo['prize_description']
                     ];
                     break;
                     
                 case 'rodadas_gratis':
                     // Creditar rodadas grátis
                     $wallet->free_spins += $prizeInfo['prize_value'];
                     $wallet->save();
                     
                     \Log::info('RaspadinhaController@processPrize - Rodadas grátis creditadas', [
                         'amount' => $prizeInfo['prize_value'],
                         'old_free_spins' => $wallet->free_spins - $prizeInfo['prize_value'],
                         'new_free_spins' => $wallet->free_spins
                     ]);
                     
                     $prizeProcessed = [
                         'success' => true,
                         'message' => 'Rodadas grátis creditadas com sucesso!',
                         'type' => 'rodadas_gratis',
                         'prize_value' => $prizeInfo['prize_value'],
                         'formatted_prize' => $prizeInfo['prize_description']
                     ];
                     break;
                     
                 case 'produto':
                     // Para produtos físicos, apenas registrar (sem crédito monetário)
                     \Log::info('RaspadinhaController@processPrize - Produto premiado', [
                         'product_name' => $prizedItem->name ?? 'Produto desconhecido'
                     ]);
                     
                     $prizeProcessed = [
                         'success' => true,
                         'message' => 'Produto premiado! Entre em contato para retirada.',
                         'type' => 'produto',
                         'prize_value' => 0, // Produtos não têm valor monetário direto
                         'formatted_prize' => $prizeInfo['prize_description'],
                         'product_description' => $prizedItem->product_description ?? ''
                     ];
                     break;
                     
                 default:
                     \Log::warning('RaspadinhaController@processPrize - Tipo de prêmio desconhecido', [
                         'prize_type' => $prizeType
                     ]);
                     
                     // Fallback: tratar como saldo real
                     $wallet->balance += $prizeInfo['prize_value'];
                     $wallet->save();
                     
                     $prizeProcessed = [
                         'success' => true,
                         'message' => 'Prêmio creditado com sucesso!',
                         'type' => 'saldo_real',
                         'prize_value' => $prizeInfo['prize_value'],
                         'formatted_prize' => $prizeInfo['prize_description']
                     ];
                     break;
             }

             // Log do prêmio
             Logs::create([
                 'field_name' => 'Raspadinha - Prêmio',
                 'old_value' => $oldBalance,
                 'new_value' => $wallet->balance,
                 'updated_by' => 1,
                 'user_id' => $user->id,
                 'type' => 1,
                 'log' => "Prêmio de raspadinha: {$prizeInfo['prize_description']} - Tipo: {$prizeType} - Valor: R$ " . number_format($prizeInfo['prize_value'], 2, ',', '.')
             ]);

             // Adicionar informações do item premiado
             if ($prizedItem) {
                 $prizeProcessed['prized_item'] = [
                     'id' => $prizedItem->id,
                     'name' => $prizedItem->name,
                     'value' => $prizedItem->value,
                     'type' => $prizedItem->premio_type,
                     'description' => $prizedItem->product_description
                 ];
             }
         }

         return $prizeProcessed;
     }

    /**
     * Formata o valor do prêmio para exibição.
     *
     * @param  array  $prizeInfo
     * @return string
     */
    protected function formatPrizeDisplay($prizeInfo)
    {
        if ($prizeInfo['has_prize']) {
            return 'R$ ' . number_format($prizeInfo['prize_value'], 2, ',', '.');
        }
        return 'R$ 0,00';
    }
} 