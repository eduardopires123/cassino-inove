<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use App\Models\GamesApi;

class ProfileController extends Controller
{
    /**
     * Mostrar a página de perfil do usuário
     */
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    /**
     * Atualizar informações do perfil
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'nullable|string',
            // Adicione outras validações conforme necessário
        ]);
        
        $user->update($validated);
        
        return redirect()->route('profile')->with('success', 'Perfil atualizado com sucesso!');
    }

    /**
     * Mostrar histórico de apostas/transações
     */
    public function history()
    {
        $user = Auth::user();
        // Buscar histórico de transações, apostas, etc.
        return view('profile.history', compact('user'));
    }

    /**
     * Mostrar jogos favoritos
     */
    public function favorites()
    {
        $user = Auth::user();
        // Buscar jogos favoritos
        return view('profile.favorites', compact('user'));
    }

    /**
     * Mostrar configurações da conta
     */
    public function settings()
    {
        $user = Auth::user();
        return view('profile.settings', compact('user'));
    }

    /**
     * Mostrar informações da carteira do usuário
     */
    public function wallet()
    {
        $user = Auth::user();
        // Certifique-se de que o usuário tem uma carteira associada
        if (!$user->wallet) {
            // Se o usuário não tiver uma carteira, você pode criar uma com saldo zero
            $user->wallet()->create(['balance' => 0]);
            $user->refresh(); // Atualiza o modelo do usuário para incluir a nova carteira
        }
        
        return view('profile.wallet', compact('user'));
    }

    /**
     * Mostrar extrato completo das transações do usuário
     */
    public function extrato(Request $request)
    {
        $user = Auth::user();
        $sourceType = $request->input('source_type', 'all');
        
        // Buscar transações financeiras (depósitos e saques)
        $transactions = \App\Models\Transactions::where('user_id', $user->id)
            ->select(
                'id',
                'type',
                'amount',
                'status',
                'created_at',
                DB::raw("0 as with_bonus"), // Criar campo virtual já que não existe
                DB::raw("'financial' as source_type"),
                DB::raw("NULL as game_name"), // Adicionar coluna nula para manter cardinalidade
                DB::raw("NULL as action_type") // Adicionar coluna para indicar o tipo de ação
            );
        
        // Buscar adições manuais de saldo da tabela logs
        $manualAdditions = DB::table('logs')
            ->where('user_id', $user->id)
            ->whereIn('field_name', ['Adição de Saldo', 'Remoção de Saldo'])
            ->select(
                'id',
                DB::raw("CASE 
                    WHEN old_value IS NOT NULL AND new_value IS NOT NULL 
                    THEN CASE 
                        WHEN CAST(REPLACE(REPLACE(REPLACE(new_value, '.', ''), ',', '.'), ' ', '') AS DECIMAL(10,2)) - CAST(REPLACE(REPLACE(REPLACE(old_value, '.', ''), ',', '.'), ' ', '') AS DECIMAL(10,2)) >= 0 
                        THEN 0 
                        ELSE 1 
                    END
                    ELSE 0
                END as type"), // 0 para adição, 1 para remoção
                DB::raw("ABS(CASE 
                    WHEN old_value IS NOT NULL AND new_value IS NOT NULL 
                    THEN CAST(REPLACE(REPLACE(REPLACE(new_value, '.', ''), ',', '.'), ' ', '') AS DECIMAL(10,2)) - CAST(REPLACE(REPLACE(REPLACE(old_value, '.', ''), ',', '.'), ' ', '') AS DECIMAL(10,2))
                    ELSE 0 
                END) as amount"), // Sempre valor absoluto para exibição
                DB::raw("1 as status"), // Todas as adições manuais são aprovadas
                'created_at',
                DB::raw("0 as with_bonus"), // Campo virtual
                DB::raw("'manual_addition' as source_type"), // Identificador para adição manual
                DB::raw("CASE 
                    WHEN old_value IS NOT NULL AND new_value IS NOT NULL 
                    THEN CASE 
                        WHEN CAST(REPLACE(REPLACE(REPLACE(new_value, '.', ''), ',', '.'), ' ', '') AS DECIMAL(10,2)) - CAST(REPLACE(REPLACE(REPLACE(old_value, '.', ''), ',', '.'), ' ', '') AS DECIMAL(10,2)) >= 0 
                        THEN 'Adição manual de saldo' 
                        ELSE 'Remoção manual de saldo' 
                    END
                    ELSE 'Adição manual de saldo'
                END as game_name"),
                DB::raw("CASE 
                    WHEN old_value IS NOT NULL AND new_value IS NOT NULL 
                    THEN CASE 
                        WHEN CAST(REPLACE(REPLACE(REPLACE(new_value, '.', ''), ',', '.'), ' ', '') AS DECIMAL(10,2)) - CAST(REPLACE(REPLACE(REPLACE(old_value, '.', ''), ',', '.'), ' ', '') AS DECIMAL(10,2)) >= 0 
                        THEN 'addition' 
                        ELSE 'removal' 
                    END
                    ELSE 'addition'
                END as action_type")
            );
        
        // Buscar histórico de jogos de cassino
        $casinoGames = DB::table('games_history')
            ->where('user_id', $user->id)
            ->leftJoin('games_api', 'games_history.game', '=', 'games_api.slug')
            ->leftJoin('providers', 'games_api.provider_id', '=', 'providers.id')
            ->select(
                'games_history.id',
                DB::raw("CASE WHEN games_history.action = 'win' THEN 0 ELSE 1 END as type"),
                'games_history.amount',
                DB::raw("1 as status"),
                DB::raw("0 as with_bonus"),
                'games_history.created_at',
                DB::raw("'casino' as source_type"),
                'games_api.name as game_name',
                'games_history.action as action_type'
            );
        
        // Buscar histórico de apostas esportivas
        $sportBets = DB::table('sportbetsummary')
            ->where('user_id', $user->id)
            ->select(
                'id',
                DB::raw("CASE WHEN operation = 'credit' THEN 0 ELSE 1 END as type"),
                'amount',
                DB::raw("CASE 
                    WHEN status = 'won' OR status = 'lost' THEN 1 
                    WHEN status = 'pending' THEN 0
                    ELSE 2 END as status"), // Mapeando para 1=aprovado, 0=pendente, 2=recusado
                DB::raw("0 as with_bonus"), // Campo virtual
                'created_at',
                DB::raw("'sports' as source_type"),
                DB::raw("NULL as game_name"), // Adicionar coluna nula para manter cardinalidade
                'operation as action_type' // Guardar o tipo de operação para usar na view
            );
        
        // Aplicar filtro por tipo de fonte se especificado
        $query = $transactions->union($manualAdditions);
        
        if ($sourceType === 'financial') {
            $query = $transactions;
        } elseif ($sourceType === 'casino') {
            $query = $casinoGames;
        } elseif ($sourceType === 'sports') {
            $query = $sportBets;
        } elseif ($sourceType === 'all') {
            $query = $transactions->union($casinoGames)->union($sportBets)->union($manualAdditions);
        }
        
        // Paginar resultados
        $allTransactions = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Preservar parâmetros de filtro na paginação
        $allTransactions->appends($request->query());
        
        // Processar nomes de jogos para casino quando necessário
        if ($sourceType === 'casino' || $sourceType === 'all') {
            foreach ($allTransactions as $transaction) {
                if ($transaction->source_type === 'casino' && empty($transaction->game_name) && !empty($transaction->game)) {
                    $gameData = $this->findGameBySlug($transaction->game);
                    if ($gameData) {
                        $transaction->game_name = $gameData->name;
                    } else {
                        $transaction->game_name = ucwords(str_replace(['-', '_'], ' ', $transaction->game ?? 'Jogo'));
                    }
                }
            }
        }
        
        return view('profile.extrato-completo', compact('user', 'allTransactions'));
    }

    public function updateAvatar(Request $request)
    {
        $validatedData = $request->validate([
            'avatar' => 'required|string',
        ]);
        
        $user = Auth::user();
        $user->image = $validatedData['avatar'];
        $user->save();
        
        return response()->json([
            'success' => true,
            'avatar' => $user->image,
            'message' => 'Avatar atualizado com sucesso!'
        ]);
    }

    public function casinoHistory(Request $request)
    {
        $user = auth()->user();
        // Alteramos o padrão para 'all' em vez de 'today' para mostrar todos os registros inicialmente
        $period = $request->input('period', 'all');
        
        $query = DB::table('games_history')
            ->where('user_id', $user->id)
            ->leftJoin('games_api', 'games_history.game', '=', 'games_api.slug')
            ->leftJoin('providers', 'games_api.provider_id', '=', 'providers.id');
        
        // Filtro por período
        switch($period) {
            case 'today':
                $query->whereDate('games_history.created_at', Carbon::today());
                break;
            case '1': // Ontem
                $query->whereDate('games_history.created_at', Carbon::yesterday());
                break;
            case '7': // Últimos 7 dias
                $query->whereDate('games_history.created_at', '>=', Carbon::now()->subDays(7));
                break;
            case '30': // Últimos 30 dias
                $query->whereDate('games_history.created_at', '>=', Carbon::now()->subDays(30));
                break;
            case '90': // Últimos 90 dias
                $query->whereDate('games_history.created_at', '>=', Carbon::now()->subDays(90));
                break;
            // 'all' não precisa de filtro adicional
        }
        
        // Adicionar um log para debugging
        \Log::info('Consultando histórico de jogos para o usuário: ' . $user->id . ' com filtro: ' . $period);
        
        $history = $query->select(
                'games_history.id',
                DB::raw("CASE WHEN games_history.action = 'win' THEN 0 ELSE 1 END as type"),
                'games_history.amount',
                DB::raw("1 as status"),
                DB::raw("0 as with_bonus"),
                'games_history.created_at',
                'games_history.action',
                'games_api.name as game_name'
            )
            ->orderBy('games_history.created_at', 'desc')
            ->paginate(15);
        
        // Log para verificar quantos registros foram encontrados
        \Log::info('Registros encontrados: ' . $history->total());
        
        return view('profile.historico-cassino', compact('history', 'user'));
    }

    /**
     * Função auxiliar para buscar dados do jogo quando o JOIN não funciona
     * Usando a nova lógica com games_api diretamente
     */
    private function findGameBySlug($gameSlug)
    {
        if (empty($gameSlug)) {
            return null;
        }

        // PRIORIDADE 1: Se for numérico, buscar por ID primeiro (nova abordagem)
        if (is_numeric($gameSlug)) {
            $game = DB::table('games_api')
                ->leftJoin('providers', 'games_api.provider_id', '=', 'providers.id')
                ->select('games_api.id', 'games_api.name', 'games_api.image', 'providers.name as provider_name')
                ->where('games_api.id', $gameSlug)
                ->where('games_api.status', 1)
                ->first();

            if ($game) {
                return $game;
            }
        }

        // PRIORIDADE 2: Se o slug contém '/', pode ser um slug complexo (ex: casino/provider/game_id)
        if (strpos($gameSlug, '/') !== false) {
            $slugParts = explode('/', $gameSlug);
            $lastPart = end($slugParts); // Pega a última parte do slug

            // Tenta buscar pela última parte como ID primeiro
            if (is_numeric($lastPart)) {
                $game = DB::table('games_api')
                    ->leftJoin('providers', 'games_api.provider_id', '=', 'providers.id')
                    ->select('games_api.id', 'games_api.name', 'games_api.image', 'providers.name as provider_name')
                    ->where('games_api.id', $lastPart)
                    ->where('games_api.status', 1)
                    ->first();

                if ($game) {
                    return $game;
                }
            }

            // Tenta buscar pela última parte na games_api por slug (compatibilidade)
            $game = DB::table('games_api')
                ->leftJoin('providers', 'games_api.provider_id', '=', 'providers.id')
                ->select('games_api.id', 'games_api.name', 'games_api.image', 'providers.name as provider_name')
                ->where('games_api.slug', 'LIKE', "%{$lastPart}")
                ->where('games_api.status', 1)
                ->first();

            if ($game) {
                return $game;
            }
        }

        // PRIORIDADE 3: Buscar jogo pelo slug diretamente na games_api (compatibilidade)
        $game = DB::table('games_api')
            ->leftJoin('providers', 'games_api.provider_id', '=', 'providers.id')
            ->select('games_api.id', 'games_api.name', 'games_api.image', 'providers.name as provider_name')
            ->where('games_api.slug', $gameSlug)
            ->where('games_api.status', 1)
            ->first();

        if ($game) {
            return $game;
        }

        // PRIORIDADE 4: Busca mais flexível usando LIKE na games_api
        $game = DB::table('games_api')
            ->leftJoin('providers', 'games_api.provider_id', '=', 'providers.id')
            ->select('games_api.id', 'games_api.name', 'games_api.image', 'providers.name as provider_name')
            ->where('games_api.slug', 'LIKE', "%{$gameSlug}%")
            ->where('games_api.status', 1)
            ->first();

        return $game;
    } 

    /**
     * Mostra o histórico de apostas esportivas do usuário.
     */
    public function sportHistory(Request $request)
    {
        $user = auth()->user();
        $period = $request->period ?? 'all';
        
        // Obter o provedor de sports ativo das configurações
        $settings = \App\Helpers\Core::getSetting();
        $activeProvider = $settings->sports_api_provider ?? 'digitain';
        
        // Query base apenas com provedor ativo
        $query = DB::table('sportbetsummary')
            ->where('user_id', $user->id)
            ->where('provider', $activeProvider); // Filtrar apenas pelo provedor ativo
        
        // Para Betby: mostrar todas as operações (make, win, lost, etc.)
        // Para Digitain: mostrar apenas debit e credit (conforme lógica original)
        
        // Filtro por período
        if ($period !== 'all') {
            if ($period === 'today') {
                $query->whereDate('created_at', Carbon::today());
            } elseif (is_numeric($period)) {
                $query->where('created_at', '>=', Carbon::now()->subDays($period));
            }
        }
        
        // Buscar dados da SportBetSummary
        $rawHistory = $query->orderBy('created_at', 'desc')->get();
        
        // Processar dados baseado no provedor
        $processedHistory = collect([]);
        
        if ($activeProvider === 'betby') {
            // Betby: Agrupar por transactionId e processar conforme BetbySportsController
            $groupedTransactions = $rawHistory->groupBy('transactionId');
            
            foreach ($groupedTransactions as $transactionId => $transactions) {
                // Buscar a transação principal (operation = 'make')
                $mainTransaction = $transactions->where('operation', 'make')->first();
                
                if ($mainTransaction) {
                    // Verificar se há resultado
                    $resultTransaction = $transactions->where('operation', '!=', 'make')->first();
                    
                    // Determinar status final
                    $finalStatus = $mainTransaction->status;
                    if ($resultTransaction) {
                        $finalStatus = $resultTransaction->status;
                    }
                    
                    // Determinar operation para compatibilidade com a view
                    $operation = 'debit'; // Padrão para aposta
                    if ($finalStatus === 'win') {
                        $operation = 'credit';
                    } elseif ($finalStatus === 'lost') {
                        $operation = 'lose';
                    }
                    
                    // Verificar cashout
                    $isCashout = false;
                    if ($mainTransaction->betslip) {
                        try {
                            $betslipData = json_decode($mainTransaction->betslip, true);
                            if (is_array($betslipData) && isset($betslipData['is_cashout'])) {
                                $isCashout = $betslipData['is_cashout'] === "1" || $betslipData['is_cashout'] === 1;
                            }
                        } catch (\Exception $e) {
                            // Continua sem cashout
                        }
                    }
                    
                    // Determinar bet_type baseado no betslip
                    $betType = 'simple';
                    if ($mainTransaction->betslip) {
                        try {
                            $betslipData = json_decode($mainTransaction->betslip, true);
                            if (is_array($betslipData)) {
                                // Verificar estrutura padrão de bet_stakes
                                if (isset($betslipData['betslip'])) {
                                    if ((isset($betslipData['betslip']['type']) && strpos($betslipData['betslip']['type'], '/') !== false)) {
                                        $typeParts = explode('/', $betslipData['betslip']['type']);
                                        if (count($typeParts) >= 2 && (int)$typeParts[0] > 1) {
                                            $betType = 'multiple';
                                        }
                                    }
                                    if (isset($betslipData['betslip']['bets']) && is_array($betslipData['betslip']['bets']) && count($betslipData['betslip']['bets']) > 1) {
                                        $betType = 'multiple';
                                    }
                                }
                            }
                        } catch (\Exception $e) {
                            // Mantém como simple
                        }
                    }
                    
                    // Criar objeto para a view
                    $processedItem = (object) [
                        'id' => $mainTransaction->id,
                        'transactionId' => $transactionId,
                        'operation' => $operation,
                        'amount' => $mainTransaction->amount / 100, // Converter de centavos para reais
                        'created_at' => $mainTransaction->created_at,
                        'betslip' => $mainTransaction->betslip,
                        'is_cashout' => $isCashout ? 1 : 0,
                        'bet_type' => $betType,
                        'status' => $finalStatus,
                        'provider' => 'betby'
                    ];
                    
                    $processedHistory->push($processedItem);
                }
            }
        } else {
            // Digitain: usar lógica original
            foreach ($rawHistory as $item) {
                $processedItem = (object) [
                    'id' => $item->id,
                    'transactionId' => $item->transactionId,
                    'operation' => $item->operation,
                    'amount' => $item->amount, // Digitain já vem em reais
                    'created_at' => $item->created_at,
                    'betslip' => $item->betslip,
                    'is_cashout' => 0, // Digitain não tem cashout implementado
                    'bet_type' => 'simple', // Determinar baseado no betslip se necessário
                    'status' => $item->status,
                    'provider' => 'digitain'
                ];
                
                $processedHistory->push($processedItem);
            }
        }
        
        // Ordenar por data de criação (mais recente primeiro)
        $processedHistory = $processedHistory->sortByDesc('created_at');
        
        // Paginar os resultados processados
        $perPage = 15;
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $currentItems = $processedHistory->slice(($currentPage - 1) * $perPage, $perPage)->values();
        
        $history = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $processedHistory->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        if ($request->ajax()) {
            return view('profile.historico-apostas', compact('history', 'user', 'activeProvider'))
                ->with('isAjax', true);
        }
        
        return view('profile.historico-apostas', compact('history', 'user', 'activeProvider'));
    }

    /**
     * Mostra a página de afiliados/indicações do usuário
     */
    public function showAfiliados()
    {
        $user = auth()->user();
        $history = \App\Models\Affiliates::where('inviter', $user->id)
                          ->with('inviterUser')
                          ->paginate(15);
        
        // Contar o número de usuários indicados pelo usuário atual
        $indicados = \App\Models\Affiliates::where('inviter', $user->id)->count();
        
        // Buscar os usuários indicados para exibir na tabela
        $indicatesTable = \App\Models\User::whereIn('id', function($query) use ($user) {
            $query->select('user_id')
                  ->from('affiliates')
                  ->where('inviter', $user->id);
        })->get();
        
        // Verificar se é requisição AJAX
        if (request()->ajax()) {
            return view('afiliado.afiliado', compact('history', 'user', 'indicados', 'indicatesTable'))
                ->with('isAjax', true);
        }
        
        return view('afiliado.afiliado', compact('history', 'user', 'indicados', 'indicatesTable'));
    }

    /**
     * Método auxiliar para aplicar filtro de período às queries
     */
    private function applyPeriodFilter($query, $period)
    {
        switch($period) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;
            case '1': // Ontem
                $query->whereDate('created_at', Carbon::yesterday());
                break;
            case '7': // Últimos 7 dias
                $query->whereDate('created_at', '>=', Carbon::now()->subDays(7));
                break;
            case '30': // Últimos 30 dias
                $query->whereDate('created_at', '>=', Carbon::now()->subDays(30));
                break;
            case '90': // Últimos 90 dias
                $query->whereDate('created_at', '>=', Carbon::now()->subDays(90));
                break;
            // 'all' não precisa de filtro adicional
        }
        
        return $query;
    }

    // Histórico de Depósitos
    public function historicodepositos()
    {
        // Inicializa a variável $deposits com um valor padrão
        $deposits = collect();
        
        try {
            // Busca apenas os depósitos do usuário atual
            $deposits = \App\Models\Transactions::where('user_id', auth()->id())
                                  ->where('type', '0')  // Ou qualquer coluna que identifique depósitos
                                  ->orderBy('created_at', 'desc')
                                  ->paginate(15);
            
            // Verifica se é uma requisição Ajax e retorna apenas o conteúdo necessário
            if (request()->ajax()) {
                return view('profile.historico-deposit', compact('deposits'))
                    ->with('isAjax', true);
            }
            
            return view('profile.historico-deposit', compact('deposits'));
        } catch (\Exception $e) {
            \Log::error('Erro ao carregar histórico de depósitos: ' . $e->getMessage());
            
            // Para requisições Ajax, retorne uma resposta de erro
            if (request()->ajax()) {
                return response()->json([
                    'error' => 'Ocorreu um erro ao carregar o histórico de depósitos',
                    'message' => $e->getMessage()
                ], 500);
            }
            
            // Para requisições normais, redirecione com mensagem de erro
            return back()->with('error', 'Ocorreu um erro ao carregar o histórico de depósitos');
        }
    }

    // Histórico de Saques
    public function historicosaques()
    {
        // Inicializa a variável $saques com um valor padrão
        $saques = collect();
        
        try {
            // Busca apenas os saques do usuário atual
            $saques = \App\Models\Transactions::where('user_id', auth()->id())
                              ->where('type', '1')  // Ou qualquer coluna que identifique saques
                              ->orderBy('created_at', 'desc')
                              ->paginate(15);
            
            // Verifica se é uma requisição Ajax e retorna apenas o conteúdo necessário
            if (request()->ajax()) {
                return view('profile.historico-saque', compact('saques'))
                    ->with('isAjax', true);
            }
            
            return view('profile.historico-saque', compact('saques'));
        } catch (\Exception $e) {
            \Log::error('Erro ao carregar histórico de saques: ' . $e->getMessage());
            
            // Para requisições Ajax, retorne uma resposta de erro
            if (request()->ajax()) {
                return response()->json([
                    'error' => 'Ocorreu um erro ao carregar o histórico de saques',
                    'message' => $e->getMessage()
                ], 500);
            }
            
            // Para requisições normais, redirecione com mensagem de erro
            return back()->with('error', 'Ocorreu um erro ao carregar o histórico de saques');
        }
    }

    // Histórico de Login
    public function loginHistory(Request $request)
    {
        $user = auth()->user();
        $period = $request->period ?? 'all';
        
        // Buscar os dados reais de login do banco de dados
        $query = \App\Models\LoginHistory::where('user_id', $user->id);
        
        // Aplicar filtro por período
        if ($period !== 'all') {
            if ($period === 'today') {
                $query->whereDate('created_at', \Carbon\Carbon::today());
            } elseif (is_numeric($period)) {
                $query->whereDate('created_at', '>=', \Carbon\Carbon::now()->subDays($period));
            }
        }
        
        // Ordenar do mais recente para o mais antigo
        $loginHistory = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Verifica se é uma requisição Ajax
        if ($request->ajax()) {
            return view('profile.historico-login', compact('loginHistory'))
                ->with('isAjax', true);
        }
        
        // Retorna a view completa para requisições não-Ajax
        return view('profile.historico-login', compact('loginHistory', 'user'));
    }

    /**
     * Método para definir o idioma do usuário
     */
    public function setLanguage(Request $request)
    {
        $validatedData = $request->validate([
            'language' => 'required|in:pt_BR,en,es'
        ]);

        $language = $validatedData['language'];

        // Definir o idioma na sessão
        Session::put('locale', $language);
        
        // Definir o idioma globalmente
        App::setLocale($language);

        // Se o usuário estiver autenticado, salvar preferência de idioma
        if (Auth::check()) {
            $user = Auth::user();
            $user->language = $language;
            $user->save();
        }

        // Definir cookie de idioma para 1 ano (consistente com os outros métodos)
        $cookie = Cookie::make('user_locale', $language, 525600);

        // Redirecionar para a URL correta com base no idioma
        if ($language === 'pt_BR') {
            return redirect('/')->withCookie($cookie);
        } else {
            return redirect('/' . $language)->withCookie($cookie);
        }
    }

    /**
     * Método para obter o idioma atual
     */
    public function getCurrentLanguage()
    {
        $currentLocale = App::getLocale();
        
        return response()->json([
            'current_locale' => $currentLocale
        ]);
    }

    /**
     * Método para exibir a página de dados da conta do usuário
     */
    public function account()
    {
        $user = Auth::user();
        return view('user.account', compact('user'));
    }
    
    /**
     * Método para exibir a página de segurança da conta do usuário
     */
    public function security() 
    {
        $user = Auth::user();
        return view('user.security', compact('user'));
    }
}
