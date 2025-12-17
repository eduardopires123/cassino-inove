<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Game;

class CassinoController extends Controller
{
    /**
     * Mostra a página principal do cassino.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Quando acessar /cassino, forçar exibição da página home do cassino
        // Adicionar parâmetro 'force=cassino' para evitar redirect no HomeController
        $request->merge(['force' => 'cassino']);

        $homeController = new \App\Http\Controllers\HomeController();
        return $homeController->index($request);
    }

    /**
     * Mostra a página de todos os jogos.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function allGames(Request $request)
    {
        // Obtém os parâmetros de filtro da requisição
        $search = $request->input('search');
        $provider = $request->input('provider');
        $category = $request->input('category');
        $perPage = $request->input('per_page', 24); // 24 jogos por página por padrão

        // Nova abordagem: usar games_api diretamente
        $query = DB::table('games_api')
            ->leftJoin('providers', 'games_api.provider_id', '=', 'providers.id')
            ->select('games_api.id', 'games_api.name', 'games_api.image', 'games_api.slug', 'games_api.status', 'providers.name as provider_name', 'games_api.category', 'games_api.views')
            ->where('games_api.status', 1)
            ->whereNotNull('games_api.slug')
            ->whereNotNull('games_api.provider_id');

        if ($search) {
            $query->where('games_api.name', 'like', "%{$search}%");
        }

        if ($provider && $provider !== 'todos') {
            $query->where('providers.name', $provider);
        }

        if ($category && $category !== 'todos') {
            $query->where('games_api.category', $category);
        }

        $query->orderBy('games_api.views', 'desc');

        $games = $query->paginate($perPage);

        $games->getCollection()->transform(function ($game) {
            $game->provider = $game->provider_name;
            return completeGameImageUrl($game);
        });

        // Total de jogos para a barra de progresso
        $total = DB::table('games_api')
            ->leftJoin('providers', 'games_api.provider_id', '=', 'providers.id')
            ->where('games_api.status', 1)
            ->where('providers.active', 1)
            ->whereNotNull('games_api.slug')
            ->whereNotNull('games_api.provider_id')
            ->count();
        $current = $games->count();

        // Retorna a view com os jogos
        return view('cassino.todos-jogos', [
            'games' => $games,
            'total' => $total,
            'current' => $current,
            'search' => $search,
            'provider' => $provider,
            'category' => $category
        ]);
    }

    /**
     * Mostra a interface para jogar um jogo específico.
     *
     * @param int $id ID do jogo
     * @return \Illuminate\View\View
     */
    public function play($id)
    {
        // Busca o jogo pelo ID diretamente na games_api
        $game = DB::table('games_api')
            ->join('providers', 'games_api.provider_id', '=', 'providers.id')
            ->select('games_api.*', 'providers.name as provider_name')
            ->where('games_api.id', $id)
            ->where('games_api.status', 1)
            ->whereNotNull('games_api.slug')
            ->first();

        if (!$game) {
            abort(404, 'Jogo não encontrado ou não está disponível');
        }

        // Verificar se o usuário está autenticado
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Você precisa fazer login para jogar.');
        }

        $user = auth()->user();

        // Verificar se o usuário já está jogando
        if ($user->playing == 1) {
            return redirect()->route('home')->with('error', 'Você já tem um jogo aberto. Feche o jogo atual antes de abrir outro.');
        }

        // Garantir que o jogo tenha a propriedade provider
        $game->provider = $game->provider_name;

        // Incrementa a contagem de visualizações do jogo ANTES de qualquer outra operação
        DB::table('games_api')
            ->where('id', $id)
            ->increment('views');

        // Registra que o usuário está jogando (se estiver autenticado)
        if (auth()->check()) {
            $userId = auth()->id();

            // Atualiza o status de "playing" do usuário
            DB::table('users')
                ->where('id', $userId)
                ->update([
                    'playing' => 1,
                    'played' => DB::raw('played + 1')
                ]);
        }

        // Jogos similares também ordenados por visualizações
        $similarGames = DB::table('games_api')
            ->leftJoin('providers', 'games_api.provider_id', '=', 'providers.id')
            ->select('games_api.*', 'providers.name as provider_name')
            ->where('games_api.category', $game->category)
            ->where('games_api.id', '!=', $id)
            ->where('games_api.status', 1)
            ->whereNotNull('games_api.slug')
            ->orderBy('games_api.views', 'desc')
            ->limit(15)
            ->get()
            ->map(function($similarGame) {
                $similarGame->provider = $similarGame->provider_name;
                return $similarGame;
            });

        // Buscar jogos mais visualizados para mostrar na view
        $mostViewedGames = DB::table('games_api')
            ->join('providers', 'games_api.provider_id', '=', 'providers.id')
            ->select('games_api.*', 'providers.name as provider_name')
            ->where('games_api.status', 1)
            ->whereNotNull('games_api.slug')
            ->whereNotNull('games_api.provider_id')
            ->orderBy('games_api.views', 'desc')
            ->limit(30)
            ->get()
            ->map(function($game) {
                return completeGameImageUrl($game);
            });

        return view('cassino.play', [
            'game' => $game,
            'similarGames' => $similarGames,
            'mostViewedGames' => $mostViewedGames
        ]);
    }

    /**
     * Mostra a interface para jogar um jogo específico por provedor e código.
     *
     * @param string $provider O nome do provedor
     * @param string $slug O slug do jogo baseado no nome
     * @return \Illuminate\View\View
     */
    public function playByProviderCode($provider, $slug)
    {
        // Converter o provider da URL para minúsculas para evitar problemas de case sensitivity
        $provider = strtolower($provider);

        // Buscar TODOS os jogos na tabela games_api (sem validar status)
        // O slug é criado do nome do jogo na coluna 'name'
        $allGames = DB::table('games_api')->get();

        // Filtrar jogos onde o slug criado do nome corresponde ao slug da URL
        $matchingGames = $allGames->filter(function ($game) use ($slug) {
            $gameName = trim($game->name ?? '');

            if (empty($gameName)) {
                return false;
            }

            // Criar slug do nome do jogo (coluna 'name' da tabela games_api)
            // É assim que o JavaScript cria a URL: createSlug(gameData.name)
            $gameSlugFromName = $this->createSlug($gameName);

            // Verificar se o slug criado do nome corresponde exatamente ao slug da URL
            return $gameSlugFromName === $slug;
        });

        if ($matchingGames->isEmpty()) {
            return redirect()->route('home')->with('error', 'Jogo não encontrado');
        }

        // Para cada jogo que corresponde ao slug, verificar se tem registro ativo na api_games_slugs
        // e se o provider corresponde
        $game = null;

        foreach ($matchingGames as $gameData) {
            // Buscar dados do provider diretamente na games_api
            $gameWithProvider = DB::table('games_api')
                ->join('providers', 'games_api.provider_id', '=', 'providers.id')
                ->select(
                    'games_api.*',
                    'providers.name as provider_code',
                    'providers.provider_name'
                )
                ->where('games_api.id', $gameData->id)
                ->where('games_api.status', 1)
                ->whereNotNull('games_api.slug')
                ->first();

            if (!$gameWithProvider) {
                continue; // Este jogo não está ativo, pular
            }

            // Verificar se o provider corresponde
            $cleanProviderName = $this->cleanProviderName($gameWithProvider->provider_name ?? $gameWithProvider->provider_code ?? '');
            $providerSlug = strtolower($this->createSlug($cleanProviderName));
            $providerCodeLower = strtolower(trim($gameWithProvider->provider_code ?? ''));
            $providerNameLower = strtolower(trim($cleanProviderName));

            $providerMatches = $providerSlug === $provider
                || $providerCodeLower === $provider
                || $providerNameLower === $provider
                || strpos($providerCodeLower, $provider) !== false
                || strpos($providerNameLower, $provider) !== false;

            if ($providerMatches) {
                // Jogo encontrado! Usar dados da games_api
                $game = $gameWithProvider;
                $game->game_slug = $gameWithProvider->slug; // Usar o slug da games_api
                break; // Encontrou, parar de procurar
            }
        }

        if (!$game) {

            // Se for uma requisição AJAX, retornar JSON
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jogo não encontrado'
                ], 404);
            }

            // Caso contrário, redirecionar para a página inicial
            return redirect()->route('home')->with('error', 'Jogo não encontrado');
        }

        // Verificar se o usuário está autenticado
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Você precisa fazer login para jogar.');
        }

        $user = auth()->user();

        // Verificar se o usuário já está jogando
        if ($user->playing == 1) {
            return redirect()->route('home')->with('error', 'Você já tem um jogo aberto. Feche o jogo atual antes de abrir outro.');
        }

        // Garantir que o jogo tenha a propriedade provider
        $game->provider = $this->cleanProviderName($game->provider_name ?: $game->provider_code);
        $game->provider_name = $game->provider; // Para compatibilidade

        // Garantir que distribution esteja disponível (usar Inove)
        if (!isset($game->distribution)) {
            $game->distribution = $game->distribution ?? 'Inove';
        }

        // IMPORTANTE: O game_slug é o slug da tabela games_api
        // Este é o slug que deve ser usado para lançar o jogo, não o slug criado do nome
        if (!isset($game->game_slug) || empty($game->game_slug)) {
            throw new \Exception('Slug do jogo não encontrado');
        }

        // Incrementa a contagem de visualizações do jogo
        DB::table('games_api')
            ->where('id', $game->id)
            ->increment('views');

        // Registra que o usuário está jogando
        $userId = auth()->id();

        // Atualiza o status de "playing" do usuário
        DB::table('users')
            ->where('id', $userId)
            ->update([
                'playing' => 1,
                'played' => DB::raw('played + 1')
            ]);

        // Inicializar o jogo e obter URL usando o GamesController
        try {
            $gameUrl = $this->initializeGame($game, $user);
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Erro ao inicializar o jogo: ' . $e->getMessage());
        }

        // Detectar se é mobile
        $isMobile = preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);

        // Buscar jogos mais visualizados para mostrar na view
        $mostViewedGames = DB::table('games_api')
            ->join('providers', 'games_api.provider_id', '=', 'providers.id')
            ->select('games_api.*', 'providers.name as provider_name')
            ->where('games_api.status', 1)
            ->whereNotNull('games_api.slug')
            ->whereNotNull('games_api.provider_id')
            ->orderBy('games_api.views', 'desc')
            ->limit(30)
            ->get()
            ->map(function($game) {
                return completeGameImageUrl($game);
            });

        // Dados para a view
        $viewData = [
            'game' => $game,
            'gameURL' => $gameUrl,
            'name' => $game->name,
            'provider' => $game->provider_name,
            'views' => $game->views,
            'mostViewedGames' => $mostViewedGames
        ];

        // Retornar a view adequada baseada no dispositivo
        if ($isMobile) {
            return view('cassino.game_page_mobile', $viewData);
        } else {
            return view('cassino.game_page', $viewData);
        }
    }

    /**
     * Cria um slug amigável a partir de um texto
     */
    private function createSlug($text)
    {
        if (!$text) {
            return '';
        }

        return Str::slug(
            trim(
                preg_replace('/\s+/', ' ', $text) // Remove espaços duplos
            ),
            '-'
        );
    }

    /**
     * Limpa o nome do provedor removendo palavras como ORIGINAL e OFICIAL
     */
    private function cleanProviderName($providerName)
    {
        if (!$providerName) {
            return '';
        }

        // Remover palavras indesejadas e limpar
        $cleanName = preg_replace('/\b(ORIGINAL|OFICIAL)\b\s*-?\s*/i', '', $providerName);
        $cleanName = trim($cleanName);
        $cleanName = preg_replace('/\s+/', ' ', $cleanName); // Remover espaços duplos
        $cleanName = trim($cleanName, '- '); // Remover hífens e espaços nas extremidades

        return $cleanName;
    }

    /**
     * Inicializa o jogo e retorna a URL do jogo usando o GamesController
     */
    private function initializeGame($game, $user)
    {
        // Importar as classes necessárias
        $Settings = \App\Helpers\Core::getSetting();

        // Gerar token para o usuário
        $token = hash('sha256', 'token-' . md5($user->email . '-' . time()));

        // Atualizar token do usuário
        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'token_time' => time(),
                'token' => $token,
                'logged_in' => 1
            ]);

        // Instanciar o GamesController para usar seus métodos
        $gamesController = new \App\Http\Controllers\GamesController();

        // Buscar dados do jogo diretamente na games_api
        $gameData = DB::table('games_api')
            ->leftJoin('providers', 'games_api.provider_id', '=', 'providers.id')
            ->select(
                'games_api.*',
                'providers.provider_name',
                'providers.name as provider_code'
            )
            ->where('games_api.id', $game->id)
            ->where('games_api.status', 1)
            ->first();

        if (!$gameData) {
            throw new \Exception('Jogo não encontrado ou inativo');
        }

        // Usar sempre Inove para lançar jogos
            $reflection = new \ReflectionClass($gamesController);
            $method = $reflection->getMethod('launchGameInove');
            $method->setAccessible(true);
            // Usar provider_name do provider
            $providerForInove = $gameData->provider_name ?: $gameData->provider_code;
            return $method->invoke($gamesController, $gameData->slug, $providerForInove);
    }

    /**
     * Incrementa o contador de visualizações do jogo sem carregar a página de jogo
     * (para uso via AJAX quando o jogador clica na imagem do jogo)
     *
     * @param int $id ID do jogo
     * @return \Illuminate\Http\JsonResponse
     */
    public function incrementarVisualizacoes($id)
    {
        $jogo = DB::table('games_api')->where('id', $id)->first();

        if (!$jogo) {
            return response()->json(['success' => false, 'message' => 'Jogo não encontrado'], 404);
        }

        // Incrementa o contador de visualizações
        DB::table('games_api')
            ->where('id', $id)
            ->increment('views');

        // Retorna o novo valor atualizado
        $novoValor = DB::table('games_api')->where('id', $id)->value('views');

        return response()->json([
            'success' => true,
            'views' => $novoValor,
            'message' => 'Visualização registrada com sucesso'
        ]);
    }

    /**
     * Mostra os jogos slots.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function slots(Request $request)
    {
        $games = DB::table('games_api')
            ->leftJoin('providers', 'games_api.provider_id', '=', 'providers.id')
            ->select('games_api.*', 'providers.name as provider_name')
            ->where('games_api.category', 'slots')
            ->where('games_api.status', 1)
            ->where('providers.active', 1)
            ->whereNotNull('games_api.slug')
            ->orderBy('games_api.views', 'desc')
            ->paginate(24);

        // Garantir que cada jogo tenha a propriedade provider e image_url
        $games->getCollection()->transform(function ($game) {
            $game->provider = $game->provider_name;
            return completeGameImageUrl($game);
        });

        return view('cassino.slots', [
            'games' => $games
        ]);
    }

    /**
     * Mostra a página de cassino ao vivo.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function live(Request $request)
    {
        $games = DB::table('games_api')
            ->leftJoin('providers', 'games_api.provider_id', '=', 'providers.id')
            ->select('games_api.*', 'providers.name as provider_name')
            ->where(function($query) {
                $query->where('games_api.category', 'live')
                    ->orWhere('games_api.category', 'Ao Vivo');
            })
            ->where('games_api.status', 1)
            ->where('providers.active', 1)
            ->whereNotNull('games_api.slug')
            ->orderBy('games_api.views', 'desc')
            ->paginate(24);

        // Garantir que cada jogo tenha a propriedade provider e image_url
        $games->getCollection()->transform(function ($game) {
            $game->provider = $game->provider_name;
            return completeGameImageUrl($game);
        });

        // Retornar a view correta
        return view('cassino.categoria.cassino-ao-vivo', [
            'games' => $games,
            'total' => $games->total(),
            'current' => $games->count()
        ]);
    }

    /**
     * Obtém jogos destacados para a página inicial.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getFeaturedGames()
    {
        return DB::table('games_api')
            ->leftJoin('providers', 'games_api.provider_id', '=', 'providers.id')
            ->select('games_api.*', 'providers.name as provider_name')
            ->where('games_api.destaque', 1)
            ->where('games_api.status', 1)
            ->where('providers.active', 1)
            ->whereNotNull('games_api.slug')
            ->orderBy('games_api.views', 'desc')
            ->limit(6)
            ->get()
            ->map(function($game) {
                $game->provider = $game->provider_name;
                return completeGameImageUrl($game);
            });
    }

    public function provedores()
    {
        return view('cassino.provedores');
    }

    /**
     * Lista todos os provedores disponíveis com contagem de jogos
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listarProvedores(Request $request)
    {
        // Obter categoria para filtrar, se informada
        $category = $request->input('category');

        // Nova abordagem: usar games_api diretamente
        $query = DB::table('games_api')
            ->leftJoin('providers', 'games_api.provider_id', '=', 'providers.id')
            ->select('providers.name as provider_name', DB::raw('count(games_api.id) as games_count'))
            ->where('games_api.status', 1)
            ->where('providers.active', 1)
            ->whereNotNull('games_api.slug');

        // Filtrar por categoria se fornecida
        if ($category) {
            $query->where('games_api.category', $category);
        }

        // Obter os provedores
        $providers = $query->groupBy('providers.name')
            ->orderBy('games_count', 'desc')
            ->get()
            ->map(function($providerObj) {
                return [
                    'id' => Str::slug($providerObj->provider_name),
                    'name' => $providerObj->provider_name,
                    'games_count' => $providerObj->games_count
                ];
            });

        return response()->json(['providers' => $providers]);
    }

    /**
     * Carrega mais jogos para paginação com filtros
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function carregarMaisJogos(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 24); // Número de jogos por página
        $sort = $request->input('sort', 'views');
        $search = $request->input('search');
        $category = $request->input('category'); // Obter a categoria da requisição

        $query = DB::table('games_api')
            ->leftJoin('providers', 'games_api.provider_id', '=', 'providers.id')
            ->where('games_api.status', 1)
            ->where('providers.active', 1)
            ->whereNotNull('games_api.slug');

        // Aplicar filtro de categoria se fornecido
        if ($category) {
            $query->where('games_api.category', $category);
        } elseif ($request->has('categories')) {
            $categories = json_decode($request->input('categories'));
            if (is_array($categories) && count($categories) > 0) {
                $query->whereIn('games_api.category', $categories);
            }
        }

        // Aplicar filtro de busca se fornecido
        if ($search) {
            $query->where('games_api.name', 'like', "%{$search}%");
        }

        // Aplicar filtro de provedor
        if ($request->has('provider')) {
            $provider = $request->input('provider');
            $query->where('providers.name', $provider);
        } elseif ($request->has('providers')) {
            $providers = json_decode($request->input('providers'));
            if (is_array($providers) && count($providers) > 0) {
                $query->whereIn('providers.name', $providers);
            }
        }

        // Aplicar ordenação (sempre por views por padrão)
        if ($sort === 'name') {
            $query->orderBy('games_api.name', 'asc');
        } elseif ($sort === 'newest') {
            $query->orderBy('games_api.created_at', 'desc');
        } else {
            // Ordenação padrão por visualizações (views) em ordem decrescente
            $query->orderBy('games_api.views', 'desc');
        }

        // Obter contagem total de jogos (para paginação)
        $totalQuery = clone $query;
        $total = $totalQuery->count();

        // Obter jogos da página atual
        $games = $query->select('games_api.id', 'games_api.name', 'games_api.image', 'games_api.slug', 'games_api.status', 'providers.name as provider_name', 'games_api.views')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get()
            ->map(function($game) {
                // Garantir que provider esteja disponível para o frontend
                $game->provider = $game->provider_name;
                return completeGameImageUrl($game);
            });

        return response()->json([
            'games' => $games,
            'page' => (int)$page,
            'perPage' => $perPage,
            'total' => $total,
            'lastPage' => ceil($total / $perPage)
        ]);
    }

    /**
     * Pesquisa jogos com base em um termo específico
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pesquisarJogos(Request $request)
    {
        $search = $request->input('search');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 12);
        $category = $request->input('category'); // Adicionar suporte à categoria

        // Iniciar consulta básica usando games_api diretamente
        $query = DB::table('games_api')
            ->leftJoin('providers', 'games_api.provider_id', '=', 'providers.id')
            ->where('games_api.status', 1)
            ->whereNotNull('games_api.slug')
            ->where(function($q) use ($search) {
                $q->where('games_api.name', 'like', "%{$search}%")
                    ->orWhere('providers.name', 'like', "%{$search}%");
            });

        // Filtrar por categoria se fornecida
        if ($category && $category !== 'all') {
            $query->where('games_api.category', $category);
        }

        // Contar total para paginação
        $totalQuery = clone $query;
        $total = $totalQuery->count();

        // Obter resultados paginados
        $games = $query->select('games_api.id', 'games_api.name', 'games_api.image', 'games_api.slug', 'games_api.status', 'providers.name as provider_name', 'games_api.category')
            ->orderBy('games_api.views', 'desc')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get()
            ->map(function($game) {
                // Adicionar informações necessárias para o frontend
                $game->provider = $game->provider_name;

                // Verificar se o campo image é uma URL completa
                if ($game->image && !str_starts_with($game->image, 'http')) {
                    // Se começa com "storage/", usar diretamente (está na pasta public)
                    $game->image_url = asset($game->image);
                } else {
                    $game->image_url = $game->image;
                }

                return $game;
            });

        return response()->json([
            'games' => $games,
            'page' => (int)$page,
            'perPage' => (int)$perPage,
            'total' => $total,
            'lastPage' => ceil($total / $perPage),
            'category' => $category // Retornar a categoria usada para referência
        ]);
    }

    public function jogosAoVivo(Request $request) {
        $games = Game::where('category', 'ao vivo')->paginate(24); // Filtra jogos da categoria "ao vivo"
        $total = $games->total();
        $current = $games->count();

        return view('cassino.categoria.cassino-ao-vivo', compact('games', 'total', 'current'));
    }

    /**
     * Lista todas as categorias disponíveis com contagem de jogos
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listarCategorias(Request $request)
    {
        // Obter provedor para filtrar, se informado
        $provider = $request->input('provider');

        // Iniciar a consulta usando games_api diretamente
        $query = DB::table('games_api')
            ->leftJoin('providers', 'games_api.provider_id', '=', 'providers.id')
            ->select('games_api.category', DB::raw('count(games_api.id) as games_count'))
            ->where('games_api.status', 1)
            ->whereNotNull('games_api.slug')
            ->whereNotNull('games_api.category')
            ->where('games_api.category', '!=', '');

        // Filtrar por provedor se fornecido
        if ($provider) {
            $query->where('providers.name', $provider);
        }

        // Obter as categorias
        $categories = $query->groupBy('games_api.category')
            ->orderBy('games_count', 'desc')
            ->get()
            ->map(function($categoryObj) {
                return [
                    'slug' => Str::slug($categoryObj->category),
                    'name' => $categoryObj->category,
                    'display_name' => ucfirst($categoryObj->category),
                    'games_count' => $categoryObj->games_count
                ];
            })
            ->filter(function($category) {
                // Filtrar categorias vazias ou inválidas
                return !empty($category['name']) && $category['games_count'] > 0;
            })
            ->values(); // Reindexar array

        return response()->json([
            'success' => true,
            'categories' => $categories,
            'total' => $categories->count()
        ]);
    }

    /**
     * Mostra a página de slots.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function jogosSlots(Request $request)
    {
        $query = DB::table('games_api')
            ->leftJoin('providers', 'games_api.provider_id', '=', 'providers.id')
            ->where('games_api.status', 1)
            ->where('providers.active', 1)
            ->where('games_api.category', 'slots')
            ->whereNotNull('games_api.slug');

        $query->orderBy('games_api.views', 'desc');

        $totalQuery = clone $query;
        $total = $totalQuery->count();

        $games = $query->select('games_api.id', 'games_api.name', 'games_api.image', 'providers.name as provider_name', 'games_api.views', 'games_api.slug')
            ->take(24)
            ->get()
            ->map(function($game) {
                $game->provider = $game->provider_name;
                return completeGameImageUrl($game);
            });

        $current = $games->count();

        return view('cassino.categoria.slots', compact('games', 'total', 'current'));
    }

    /**
     * Mostrar jogos de um provedor específico
     *
     * @param string $provider
     * @return \Illuminate\Contracts\View\View
     */
    public function showProviderGames($provider)
    {
        // Decodificar o nome do provedor (remover underscores e converter para o formato original)
        $providerName = ucwords(str_replace('', ' ', $provider));

        // Buscar o provedor real na base de dados para obter o nome correto
        $realProvider = DB::table('providers')
            ->where('name', 'like', '%' . str_replace('', '%', $provider) . '%')
            ->orWhere(DB::raw('LOWER(REPLACE(name, " ", ""))'), strtolower($provider))
            ->first();

        if ($realProvider) {
            $providerName = $realProvider->name;
        }

        // Buscar jogos iniciais (primeiros 24) do provedor específico
        $games = DB::table('games_api')
            ->join('providers', 'games_api.provider_id', '=', 'providers.id')
            ->where('providers.name', $providerName)
            ->where('games_api.status', 1)
            ->where('providers.active', 1)
            ->whereNotNull('games_api.slug')
            ->select('games_api.id', 'games_api.name', 'games_api.image', 'games_api.views', 'providers.name as provider_name')
            ->orderBy('games_api.views', 'desc')
            ->limit(24)
            ->get()
            ->map(function($game) {
                // Adicionar a propriedade provider para evitar o erro
                $game->provider = $game->provider_name;
                return completeGameImageUrl($game);
            });

        // Contar o total de jogos do provedor
        $total = DB::table('games_api')
            ->join('providers', 'games_api.provider_id', '=', 'providers.id')
            ->where('providers.name', $providerName)
            ->where('games_api.status', 1)
            ->where('providers.active', 1)
            ->whereNotNull('games_api.slug')
            ->count();

        return view('cassino.categoria.games-providers', [
            'games' => $games,
            'current' => count($games),
            'total' => $total,
            'provider_slug' => $provider, // Passar o slug do provedor para a view
            'provider_name' => $providerName // Passar o nome real do provedor
        ]);
    }

    public function verificarProvedoresAtivos(Request $request)
    {
        $providerNames = $request->input('providers', []);
        $distribution = $request->input('distribution'); // Opcional: filtrar por distribuição

        // Consultar quais provedores têm active = 1
        $query = DB::table('providers')
            ->whereIn('name', $providerNames)
            ->where('active', 1);

        // Se uma distribuição foi especificada, filtrar por ela
        if ($distribution && $distribution !== 'all') {
            $query->where('distribution', $distribution);
        }

        $activeProviders = $query->pluck('name')->toArray();

        return response()->json(['active_providers' => $activeProviders]);
    }
}
