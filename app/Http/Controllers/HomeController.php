<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Banner;
use App\Models\VisitLogs;
use App\Models\Admin\Banners;
use App\Models\HomeSectionsSettings;
use App\Models\HomeCustomField;
use App\Models\SocialLink;
use App\Models\Admin\Icon;
use App\Models\Raspadinha;
use App\Models\Settings;
use App\Helpers\Core;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Controllers\PartialsController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Firebase\JWT\JWT;

use App\Models\GamesApi;

class HomeController extends Controller
{
    /**
     * TTL padrão para cache (em minutos)
     */
    private const CACHE_TTL = 30;

    /**
     * Mostra a página inicial com os banners.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        // Verificar se o usuário quer forçar a página de cassino (via parâmetro na URL)
        $forceCassino = $request->has('force') && $request->get('force') === 'cassino';

        // Se não estiver forçando cassino, verificar configuração de página inicial padrão
        if (!$forceCassino) {
            // Verificar configuração de página inicial padrão (usando cache)
            $settings = Core::getSetting();
            $defaultHomePage = $settings->default_home_page ?? 'cassino';

            // Se a página inicial padrão for esportes, redirecionar
            if ($defaultHomePage === 'esportes') {
                // Verificar qual provedor de esportes está ativo (já usa o settings em cache)
                $sportsProvider = $settings->sports_api_provider ?? 'digitain';
                if ($sportsProvider === 'betby') {
                    return redirect()->route('sports.betby');
                } elseif ($sportsProvider === 'digitain' || $sportsProvider === null) {
                    return redirect()->route('esportes');
                }
                // Se nenhum estiver ativo, continuar para cassino
            }
        }

        $ipAddress = $request->ip();
        $userAgent = $request->header('User-Agent');
        $referer = $request->headers->get('referer');
        $deviceType = 'Desktop';

        if (preg_match('/android/i', $userAgent)) {
            $deviceType = 'Android';
        } elseif (preg_match('/iphone|ipod|ipad/i', $userAgent)) {
            $deviceType = 'iOS';
        }

        $Checa = VisitLogs::Where('ip', $ipAddress)->first();

        if (!$Checa) {
            VisitLogs::create([
                'ip' => $ipAddress,
                'agent' => $deviceType,
                'referer' => $referer,
            ]);
        }

        // Obter todos os dados de cache
        $cachedData = $this->getCachedHomeData();

        // Configurações das seções da página inicial
        $homeSections = HomeSectionsSettings::getSettings();

        // Buscar jogos dos provedores ativos
        $providerGames = $this->getProviderGames($cachedData['activeProviders_cache']);

        // Buscar jogos para modo surpresa
        $surpresaGames = $this->getSurpresaGames();

        // Buscar campos personalizados ativos
        $customFields = collect([]);
        $customFieldsData = [];
        try {
            $customFields = HomeCustomField::getActiveFields();

            // Garantir que é uma coleção
            if (!$customFields instanceof \Illuminate\Support\Collection) {
                $customFields = collect($customFields ?? []);
            }

            if ($customFields->isNotEmpty()) {
                foreach ($customFields as $field) {
                    try {
                        // Verificar se o campo tem ID válido
                        if (!isset($field->id) || !$field->id) {
                            continue;
                        }

                        $games = $field->getGamesWithDetails();

                        // Verificar se games é uma coleção válida e tem itens
                        if ($games instanceof \Illuminate\Support\Collection && $games->count() > 0) {
                            $customFieldsData[$field->id] = $games;
                        }
                    } catch (\Exception $e) {
                        // Log do erro mas continue com os outros campos
                        Log::error("Erro ao processar custom field " . ($field->id ?? 'sem ID') . ": " . $e->getMessage());
                        continue;
                    }
                }
            }
        } catch (\Exception $e) {
            // Log do erro mas não quebrar a aplicação
            Log::error("Erro ao processar custom fields: " . $e->getMessage());
            $customFields = collect([]);
            $customFieldsData = [];
        }

        // Configurações do Betby para os banners (se Betby estiver ativo)
        $betbyConfig = null;
        $jwtToken = null;

        if (Settings::isBetbyActive()) {
            $betbyConfig = [
                'brand_id' => config('betby.brand_id'),
                'operator_id' => config('betby.operator_id'),
                'api_url' => config('betby.is_production')
                    ? config('betby.production_api_url')
                    : config('betby.api_url'),
                'bt_library_url' => config('betby.bt_library_url'),
                'external_api_url' => config('betby.external_api_url'),
                'theme_name' => config('betby.theme_name'),
                'currency' => config('betby.currency'),
                'language' => app()->getLocale() ?? 'pt_BR',
            ];

            // Gerar token JWT se o usuário estiver autenticado
            $user = Auth::user();
            if ($user) {
                $jwtToken = $this->generateJWTToken($user);
            }
        }

        return view('home', compact(
            'homeSections',
            'cachedData',
            'providerGames',
            'surpresaGames',
            'customFields',
            'customFieldsData',
            'betbyConfig',
            'jwtToken'
        ));
    }

    /**
     * Obtém todos os dados de cache
     */
    private function getCachedHomeData(): array
    {
        $data = [];

        // Banners
        $data['bannerDesktop_cache'] = $this->cacheBannerDesktop();
        $data['bannerMobile_cache'] = $this->cacheBannerMobile();
        $data['hasActivePromoBanners_cache'] = $this->cacheActivePromoBanners();
        $data['banners_mini_cache'] = $this->cacheBannersMini();

        // Configurações e Links
        $data['socialLinks_whatsapp_cache'] = $this->cacheSocialLinks();
        $data['show_top_wins_cache'] = $this->cacheShowTopWins();
        $data['show_last_bets_cache'] = $this->cacheShowLastBets();

        // Jogos e Categorias
        $data['categories_cache'] = $this->cacheCategories();
        $data['liveGames_cache'] = $this->cacheLiveGames();
        $data['recentGames_cache'] = $this->cacheRecentGames();
        $data['mostViewedGames_cache'] = $this->cacheMostViewedGames();

        // Raspadinhas
        $data['mostPlayedRaspadinhas_cache'] = $this->cacheMostPlayedRaspadinhas();

        // Provedores
        $data['activeProviders_cache'] = $this->cacheActiveProviders();
        $data['providers_cache'] = $this->cacheProviders();

        // Wins e Bets
        $data['top_wins_cache'] = $this->cacheTopWins();
        $data['last_bets_cache'] = $this->cacheLastBets();

        // Ícones
        $data['leagueIcons_cache'] = $this->cacheLeagueIcons();

        // Ícones da home
        $data['icons_cache'] = $this->cacheIcons();

        // Banner promocional
        $data['promoBanner_cache'] = $this->cachePromoBanner();

        return $data;
    }

    /**
     * Cache para banner desktop
     */
    private function cacheBannerDesktop()
    {
        return Cache::remember('home:banner:desktop', now()->addMinutes(self::CACHE_TTL), function () {
            return DB::table('banners')
                ->where('tipo', 'mini')
                ->where('active', true)
                ->where('mobile', 'não')
                ->orderBy('ordem')
                ->first();
        });
    }

    /**
     * Cache para banner mobile
     */
    private function cacheBannerMobile()
    {
        return Cache::remember('home:banner:mobile', now()->addMinutes(self::CACHE_TTL), function () {
            return DB::table('banners')
                ->where('tipo', 'mini')
                ->where('active', true)
                ->where('mobile', 'sim')
                ->orderBy('ordem')
                ->first();
        });
    }

    /**
     * Cache para verificar banners promocionais ativos
     */
    private function cacheActivePromoBanners()
    {
        return Cache::remember('home:banners:promo:active', now()->addMinutes(self::CACHE_TTL), function () {
            return Banners::where('tipo', 'promo')->where('active', 1)->exists();
        });
    }

    /**
     * Cache para banners mini
     */
    private function cacheBannersMini()
    {
        return Cache::remember('home:banners:mini', now()->addMinutes(self::CACHE_TTL), function () {
            return DB::table('banners')
                ->select('imagem', 'link', 'tipo', 'ordem', 'active', 'mobile')
                ->where('tipo', 'mini')
                ->where('active', 1)
                ->orderBy('ordem', 'asc')
                ->get();
        });
    }

    /**
     * Cache para configurações do WhatsApp
     */
    private function cacheSocialLinks()
    {
        return Cache::remember('home:social:whatsapp', now()->addMinutes(self::CACHE_TTL), function () {
            return SocialLink::first();
        });
    }

    /**
     * Cache para configuração show_top_wins
     */
    private function cacheShowTopWins()
    {
        return Cache::remember('home:settings:show_top_wins', now()->addMinutes(self::CACHE_TTL), function () {
            return DB::table('home_sections_settings')->value('show_top_wins') ?? 1;
        });
    }

    /**
     * Cache para configuração show_last_bets
     */
    private function cacheShowLastBets()
    {
        return Cache::remember('home:settings:show_last_bets', now()->addMinutes(self::CACHE_TTL), function () {
            return DB::table('home_sections_settings')->value('show_last_bets') ?? 1;
        });
    }

    /**
     * Cache para categorias de jogos
     */
    private function cacheCategories()
    {
        return Cache::remember('home:games:categories', now()->addMinutes(self::CACHE_TTL), function () {
            return DB::table('games_api')
                ->select('category')
                ->where('status', 1)
                ->whereNotNull('slug')
                ->whereNotNull('category')
                ->where('category', '!=', '')
                ->distinct()
                ->orderBy('category')
                ->pluck('category')
                ->filter() // Remove valores vazios/null
                ->values() // Reindexar array
                ->toArray();
        });
    }

    /**
     * Cache para jogos ao vivo
     */
    private function cacheLiveGames()
    {
        return Cache::remember('home:games:live', now()->addMinutes(self::CACHE_TTL), function () {
            $games = GamesApi::toBase()
                ->select(
                    'games_api.id',
                    'games_api.name',
                    'games_api.image',
                    'games_api.slug',
                    'providers.provider_name'
                )
                ->join('providers', 'providers.id', '=', 'games_api.provider_id')
                ->where(function ($q) {
                    $q->where('category', 'live')
                        ->orWhere('category', 'Ao Vivo');
                })
                ->where('games_api.status', 1)
                ->orderBy('games_api.views', 'desc')
                ->limit(30)
                ->get()
                ->toArray();

            return $games;
        });
    }

    /**
     * Cache para jogos recentes
     */
    private function cacheRecentGames()
    {
        return Cache::remember('home:games:recent', now()->addMinutes(self::CACHE_TTL), function () {
            $games = GamesApi::toBase()
                ->select(
                    'games_api.id',
                    'games_api.name',
                    'games_api.image',
                    'games_api.slug',
                    'providers.provider_name AS provider_name'
                )
                ->join('providers', 'providers.id', '=', 'games_api.provider_id')
                ->where('games_api.status', 1)
                ->orderBy('games_api.views', 'desc')
                ->orderBy('games_api.id', 'desc')
                ->limit(30)
                ->get()
                ->toArray();

            return $games;
        });
    }

    /**
     * Cache para jogos mais visualizados
     */
    private function cacheMostViewedGames()
    {
        return Cache::remember('home:games:most_viewed', now()->addMinutes(self::CACHE_TTL), function () {
            $games = GamesApi::toBase()
                ->select('games_api.id', 'games_api.name', 'games_api.image', 'games_api.slug', 'providers.provider_name AS provider_name')
                ->join('providers', 'providers.id', '=', 'games_api.provider_id')
                ->orderBy('games_api.views', 'desc')
                ->where('games_api.status', 1)
                ->limit(30)->get()->toArray();

            return $games;
        });
    }

    /**
     * Cache para raspadinhas mais jogadas
     */
    private function cacheMostPlayedRaspadinhas()
    {
        return Cache::remember('home:raspadinhas:most_played', now()->addMinutes(self::CACHE_TTL), function () {
            return Raspadinha::mostPlayed(12)->get()->map(function ($raspadinha) {
                return (object) [
                    'id' => $raspadinha->id,
                    'name' => $raspadinha->name,
                    'image_url' => $raspadinha->image_url,
                    'price' => $raspadinha->price,
                    'turbo_price' => $raspadinha->turbo_price,
                    'plays_count' => $raspadinha->plays_count ?? 0
                ];
            });
        });
    }

    /**
     * Cache para provedores ativos na home
     */
    private function cacheActiveProviders()
    {
        return Cache::remember('home:providers:active', now()->addMinutes(self::CACHE_TTL), function () {
            return DB::table('providers')
                ->select('id', 'name', 'name_home', 'img', 'order_value')
                ->where('active', 1)
                ->where('showmain', 1)
                ->orderBy('order_value', 'asc')
                ->get()
                ->map(function($provider) {
                    // Se name_home tem valor, usar name_home, senão usar name
                    $provider->display_name = !empty($provider->name_home) ? $provider->name_home : $provider->name;
                    return $provider;
                });
        });
    }

    /**
     * Cache para todos os provedores
     */
    private function cacheProviders()
    {
        return Cache::remember('home:providers:all', now()->addMinutes(self::CACHE_TTL), function () {
            return DB::table('providers')->where('active', 1)->get();
        });
    }

    /**
     * Cache para maiores ganhos (5 minutos para dados mais atualizados)
     */
    private function cacheTopWins()
    {
        return Cache::remember('home:wins:top', now()->addMinutes(5), function () {
            return $this->getTopWinsData();
        });
    }

    /**
     * Cache para últimas apostas
     */
    private function cacheLastBets()
    {
        return Cache::remember('home:bets:last', now()->addMinutes(self::CACHE_TTL), function () {
            return $this->getLastBetsData();
        });
    }

    /**
     * Cache para ícones das ligas
     */
    private function cacheLeagueIcons()
    {
        return Cache::remember('home:icons:league', now()->addMinutes(self::CACHE_TTL), function () {
            return \App\Models\Admin\Icon::where('active', true)
                ->where('type', 'league')
                ->orderBy('ordem', 'asc')
                ->get();
        });
    }

    /**
     * Cache para ícones da home
     */
    private function cacheIcons()
    {
        return Cache::remember('home:icons:main', now()->addMinutes(self::CACHE_TTL), function () {
            $icons = \App\Models\Admin\Icon::where('active', true)
                ->where('type', 'icon')
                ->orderBy('ordem', 'asc')
                ->get();

            // Processar cada ícone para adicionar lógica de links
            foreach ($icons as $icon) {
                if ($icon->link) {
                    // Verificar se é uma rota Laravel
                    $isRoute = strpos($icon->link, '{{') !== false && strpos($icon->link, '}}') !== false;
                    $isJsFunction = preg_match('/^[A-Za-z0-9_]+\(.*\)$/', $icon->link);

                    $icon->is_route = $isRoute;
                    $icon->is_js_function = $isJsFunction;

                    // Processar a rota se for uma rota Laravel
                    if ($isRoute) {
                        try {
                            // Remove as chaves duplas e avalia a expressão
                            $routeExpression = str_replace(['{{', '}}'], '', $icon->link);
                            $icon->route_url = eval("return $routeExpression;");
                        } catch (\Exception $e) {
                            // Em caso de erro, mantém o link original
                            $icon->route_url = $icon->link;
                            $icon->is_route = false;
                        }
                    }
                } else {
                    $icon->is_route = false;
                    $icon->is_js_function = false;
                }
            }

            return $icons;
        });
    }

    /**
     * Cache para banner promocional
     */
    private function cachePromoBanner()
    {
        return Cache::remember('home:banner:promo', now()->addMinutes(self::CACHE_TTL), function () {
            return DB::table('banners')
                ->where('tipo', 'promo')
                ->where('active', true)
                ->first();
        });
    }

    /**
     * Buscar jogos dos provedores ativos
     */
    private function getProviderGames($activeProviders): array
    {
        $providerIds = $activeProviders->pluck('id');

        $allGames = GamesApi::toBase()
            ->select(
                'games_api.id',
                'games_api.name',
                'games_api.image',
                'games_api.slug',
                'games_api.provider_id',
                'providers.provider_name'
            )
            ->join('providers', 'providers.id', '=', 'games_api.provider_id')
            ->whereIn('games_api.provider_id', $providerIds)
            ->where('games_api.show_home', 1)
            ->where('games_api.status', 1)
            ->orderBy('games_api.destaque', 'desc')
            ->orderBy('games_api.views', 'desc')
            ->get()
            ->groupBy('provider_id');

        $providerGames = [];

        foreach ($providerIds as $providerId) {
            $providerGames[$providerId] = Cache::remember(
                "home:provider:games:{$providerId}",
                now()->addMinutes(self::CACHE_TTL),
                fn() =>
                ($allGames[$providerId] ?? collect())->take(30)->values()
            );
        }

        return $providerGames;
    }

    /**
     * Buscar jogos para modo surpresa
     */
    private function getSurpresaGames()
    {
        return Cache::remember('home:games:surpresa', now()->addMinutes(self::CACHE_TTL), function () {
            $allIds = GamesApi::toBase()->select('id', 'name', 'image', 'slug')->where('status', 1)->pluck('id');
            $randomIds = $allIds->random(50);

            return GamesApi::toBase()->whereIn('id', $randomIds)->get();
        });
    }

    /**
     * Mostra a página de download do aplicativo.
     *
     * @return \Illuminate\View\View
     */
    public function app()
    {
        return view('app.download');
    }

    /**
     * Mostra a página de termos e condições.
     *
     * @return \Illuminate\View\View
     */
    public function terms()
    {
        return view('terms-and-conditions');
    }

    /**
     * Mostra a página de política de privacidade.
     *
     * @return \Illuminate\View\View
     */
    public function privacy()
    {
        return view('legal.privacy');
    }

    /**
     * Mostra a página de jogo responsável.
     *
     * @return \Illuminate\View\View
     */
    public function responsible()
    {
        return view('legal.responsible-gaming');
    }

    /**
     * Buscar maiores ganhos do dia (método corrigido para usar games_history)
     */
    private function getTopWinsData()
    {
        try {

            // Primeiro, tenta buscar os ganhos do dia atual
            $todayWins = DB::table('games_history')
                ->join('users', 'games_history.user_id', '=', 'users.id')
                ->leftJoin('games_api', function($join) {
                    $join->whereColumn('games_history.game', '=', 'games_api.slug')
                        ->where('games_api.status', 1);
                })
                ->select(
                    'games_history.id',
                    'games_history.amount',
                    'games_history.game',
                    'users.name as user_name',
                    'games_api.name as game_name',
                    'games_api.image as game_image',
                    'games_api.id as game_id',
                    'games_history.created_at'
                )
                ->where('games_history.action', 'win')
                ->where('games_history.amount', '>', 0)
                ->whereDate('games_history.created_at', now()->toDateString())
                ->orderBy('games_history.amount', 'desc')
                ->limit(50)
                ->get();

            // Se não houver ganhos hoje ou tiver menos de 50, buscar os mais recentes para completar
            if ($todayWins->count() == 0) {
                // Se não há ganhos hoje, buscar os 50 ganhos mais recentes
                $recentWins = DB::table('games_history')
                    ->join('users', 'games_history.user_id', '=', 'users.id')
                    ->leftJoin('games_api', function($join) {
                        $join->whereColumn('games_history.game', '=', 'games_api.slug')
                            ->where('games_api.status', 1);
                    })
                    ->select(
                        'games_history.id',
                        'games_history.amount',
                        'games_history.game',
                        'users.name as user_name',
                        'games_api.name as game_name',
                        'games_api.image as game_image',
                        'games_api.id as game_id',
                        'games_history.created_at'
                    )
                    ->where('games_history.action', 'win')
                    ->where('games_history.amount', '>', 0)
                    ->orderBy('games_history.amount', 'desc')
                    ->limit(50)
                    ->get();

                $topWins = $recentWins;
            } elseif ($todayWins->count() < 50) {

                $recentWins = DB::table('games_history')
                    ->join('users', 'games_history.user_id', '=', 'users.id')
                    ->leftJoin('games_api', function($join) {
                        $join->whereColumn('games_history.game', '=', 'games_api.slug')
                            ->where('games_api.status', 1);
                    })
                    ->select(
                        'games_history.id',
                        'games_history.amount',
                        'games_history.game',
                        'users.name as user_name',
                        'games_api.name as game_name',
                        'games_api.image as game_image',
                        'games_api.id as game_id',
                        'games_history.created_at'
                    )
                    ->where('games_history.action', 'win')
                    ->where('games_history.amount', '>', 0)
                    ->whereNotIn('games_history.id', $todayWins->pluck('id')->toArray())
                    ->orderBy('games_history.amount', 'desc')
                    ->limit(50 - $todayWins->count())
                    ->get();

                // Combina os dois conjuntos de resultados e ordena por valor
                $topWins = $todayWins->concat($recentWins)->sortByDesc('amount');
            } else {
                // Se há 50 ou mais ganhos hoje, usar apenas os de hoje
                $topWins = $todayWins;
            }

            // Processar cada resultado para garantir que temos dados válidos
            foreach ($topWins as $win) {
                // Se não conseguimos obter os dados do jogo via JOIN, tentar buscar manualmente
                if (empty($win->game_name) || empty($win->game_image)) {
                    $gameData = $this->findGameBySlug($win->game);
                    if ($gameData) {
                        $win->game_name = $gameData->name;
                        $win->game_image = $gameData->image;
                        $win->game_id = $gameData->id;
                    }
                }

                // Aplicar a função para garantir URLs completas das imagens
                if (!empty($win->game_image) && !str_starts_with($win->game_image, 'http')) {
                    // Se a imagem não começar com http, adicionar o domínio base apontando para public
                    $win->game_image = env('APP_URL', 'https://'.request()->getHost()) . '/' . $win->game_image;
                }

                // Se ainda não tem nome do jogo mesmo após buscar no banco, usar o slug como último recurso
                if (empty($win->game_name)) {
                    // Tentar fazer uma busca mais agressiva antes de usar o slug
                    $gameDataFallback = DB::table('games_api')
                        ->select('name')
                        ->where(function($query) use ($win) {
                            $query->where('games_api.slug', 'LIKE', "%{$win->game}%")
                                ->orWhere('games_api.name', 'LIKE', "%{$win->game}%");
                        })
                        ->where('games_api.status', 1)
                        ->first();

                    if ($gameDataFallback) {
                        $win->game_name = $gameDataFallback->name;
                    } else {
                        // Apenas como último recurso, usar o slug formatado
                        $win->game_name = ucwords(str_replace(['-', '_'], ' ', $win->game ?? 'Jogo'));

                    }
                }

                // Mascarar o nome do usuário para privacidade
                $win->masked_user_name = $this->maskUserName($win->user_name);
            }

            $result = $topWins->take(50);

            return $result;

        } catch (\Exception $e) {
            Log::error('Error in getTopWinsData: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            // Em caso de erro, retornar uma coleção vazia
            return collect([]);
        }
    }

    /**
     * Função auxiliar para buscar dados do jogo quando o JOIN não funciona
     * Reutilizando a mesma lógica robusta, mas agora usando api_games_slugs
     */
    private function findGameBySlug($gameSlug)
    {
        if (empty($gameSlug)) {
            return null;
        }

        // PRIORIDADE 1: Se for numérico, buscar por ID primeiro (nova abordagem)
        if (is_numeric($gameSlug)) {
            $game = DB::table('games_api')
                ->join('providers', 'games_api.provider_id', '=', 'providers.id')
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
                    ->join('providers', 'games_api.provider_id', '=', 'providers.id')
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
                ->join('providers', 'games_api.provider_id', '=', 'providers.id')
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
            ->join('providers', 'games_api.provider_id', '=', 'providers.id')
            ->select('games_api.id', 'games_api.name', 'games_api.image', 'providers.name as provider_name')
            ->where('games_api.slug', $gameSlug)
            ->where('games_api.status', 1)
            ->first();

        if ($game) {
            return $game;
        }

        // PRIORIDADE 4: Busca mais flexível usando LIKE na games_api
        $game = DB::table('games_api')
            ->join('providers', 'games_api.provider_id', '=', 'providers.id')
            ->select('games_api.id', 'games_api.name', 'games_api.image', 'providers.name as provider_name')
            ->where('games_api.slug', 'LIKE', "%{$gameSlug}%")
            ->where('games_api.status', 1)
            ->first();

        return $game;
    }

    /**
     * Buscar últimas apostas (método corrigido para usar games_history)
     */
    private function getLastBetsData()
    {
        $lastBets = DB::table('games_history')
            ->join('users', 'games_history.user_id', '=', 'users.id')
            ->leftJoin('games_api', function($join) {
                $join->on('games_history.game', '=', 'games_api.slug')
                    ->where('games_api.status', 1);
            })
            ->leftJoin('providers', 'games_api.provider_id', '=', 'providers.id')
            ->select(
                'games_history.id',
                'games_history.amount',
                'games_history.action',
                'games_history.created_at',
                'games_history.game',
                'games_api.name as game_name',
                'games_api.image as game_image',
                'games_api.id as game_id',
                'providers.name as provider_name',
                'users.name as user_name'
            )
            ->where('games_history.action', 'win')
            ->where('games_history.amount', '>', 0)
            ->orderBy('games_history.created_at', 'desc')
            ->limit(20)
            ->get();

        foreach ($lastBets as $bet) {
            // Se não conseguimos obter os dados do jogo via JOIN, tentar buscar manualmente
            if (empty($bet->game_name) || empty($bet->game_image)) {
                $gameData = $this->findGameBySlug($bet->game);
                if ($gameData) {
                    $bet->game_name = $gameData->name;
                    $bet->game_image = $gameData->image;
                    $bet->game_id = $gameData->id;
                    $bet->provider_name = $gameData->provider_name;
                }
            }

            // Aplicar a função para garantir URLs completas das imagens
            if (!empty($bet->game_image) && !str_starts_with($bet->game_image, 'http')) {
                $bet->game_image = env('APP_URL', 'https://'.request()->getHost()) . '/' . $bet->game_image;
            }

            // Se ainda não tem nome do jogo mesmo após buscar no banco, usar o slug como último recurso
            if (empty($bet->game_name)) {
                // Tentar fazer uma busca mais agressiva antes de usar o slug
                $gameDataFallback = DB::table('games_api')
                    ->select('name')
                    ->where(function($query) use ($bet) {
                        $query->where('games_api.slug', 'LIKE', "%{$bet->game}%")
                            ->orWhere('games_api.name', 'LIKE', "%{$bet->game}%");
                    })
                    ->where('games_api.status', 1)
                    ->first();

                if ($gameDataFallback) {
                    $bet->game_name = $gameDataFallback->name;
                } else {
                    // Apenas como último recurso, usar o slug formatado
                    $bet->game_name = ucwords(str_replace(['-', '_'], ' ', $bet->game ?? 'Jogo'));
                }
            }

            // Mascarar o nome do usuário para privacidade
            $bet->masked_user_name = $this->maskUserName($bet->user_name);

            // Calcular valor anterior aleatório entre 5% a 60% da aposta ganha
            $randomPercentage = mt_rand(5, 60);
            $bet->previous_amount = ($bet->amount * $randomPercentage) / 100;
            $bet->previous_amount_formatted = number_format($bet->previous_amount, 2, ',', '.');
            $bet->amount_formatted = number_format($bet->amount, 2, ',', '.');
        }

        return $lastBets;
    }

    /**
     * Mascarar nome do usuário para privacidade
     * Formato: Primeiro Nome + Primeira letra do último nome + ****
     * Exemplo: "Eduardo Vieira de Lima" -> "Eduardo L****"
     */
    private function maskUserName($name)
    {
        if (empty($name) || $name === 'Jogador') {
            return 'Jogador';
        }

        // Dividir o nome em partes usando espaços
        $nameParts = array_filter(explode(' ', trim($name)));

        if (count($nameParts) === 0) {
            return 'Jogador';
        }

        if (count($nameParts) === 1) {
            // Se há apenas um nome, retorna como está
            return $nameParts[0];
        }

        // Pegar o primeiro nome
        $firstName = $nameParts[0];

        // Pegar a primeira letra do último nome
        $lastNameFirstLetter = substr($nameParts[count($nameParts) - 1], 0, 1);

        // Retornar formato: Primeiro L****
        return $firstName . ' ' . $lastNameFirstLetter . '***';
    }

    /**
     * Limpar cache da home
     */
    public function clearHomeCache()
    {
        try {
            $clearedCount = 0;

            // Limpar caches específicos
            $cacheKeys = [
                'home:banner:desktop',
                'home:banner:mobile',
                'home:banners:promo:active',
                'home:banners:mini',
                'home:social:whatsapp',
                'home:settings:show_top_wins',
                'home:settings:show_last_bets',
                'home:games:categories',
                'home:games:live',
                'home:games:recent',
                'home:games:most_viewed',
                'home:providers:active',
                'home:providers:all',
                'home:wins:top',
                'home:bets:last',
                'home:icons:league',
                'home:icons:main',
                'home:banner:promo',
                'home:games:surpresa',
                'home:raspadinhas:most_played'
            ];

            foreach ($cacheKeys as $key) {
                if (Cache::forget($key)) {
                    $clearedCount++;
                }
            }

            // Limpar cache de jogos por provedor (padrão dinâmico)
            $providers = DB::table('providers')->where('active', 1)->get();
            foreach ($providers as $provider) {
                $providerCacheKey = "home:provider:games:{$provider->id}";
                if (Cache::forget($providerCacheKey)) {
                    $clearedCount++;
                }
            }

            // Limpar também caches relacionados do PartialsController
            $partialsCacheKeys = [
                'partials:footer:data',
                'partials:banners:data',
                'partials:latest_banner:data',
                'partials:slider_banners:data',
                'partials:latest_slider_banner:data'
            ];

            foreach ($partialsCacheKeys as $key) {
                if (Cache::forget($key)) {
                    $clearedCount++;
                }
            }

            Log::info("Cache da home limpo com sucesso. Total de chaves removidas: {$clearedCount}");
            return true;

        } catch (\Exception $e) {
            Log::error('Erro ao limpar cache da home: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Gerar token JWT para o usuário (para banners Betby)
     */
    private function generateJWTToken($user)
    {
        $now = time();

        if ($user->language == "pt_BR") {
            $idioma = "pt-br";
        } else {
            $idioma = $user->language;
        }

        $Settings = Core::getSetting();

        $payload = [
            'iat' => $now,
            'exp' => $now + (config('betby.token_expiry_hours', 24) * 60 * 60),
            'jti' => uniqid(),
            'iss' => config('betby.brand_id'),
            'aud' => config('betby.brand_id'),
            'sub' => $Settings->sportpartnername . '-' . $user->id,
            'name' => $user->name,
            'lang' => $idioma,
            'currency' => config('betby.currency'),
            'odds_format' => config('betby.odds_format'),
            'ff' => config('betby.feature_flags'),
            'nbf' => $now // Not before
        ];

        $privateKeyPath = config('betby.private_key');
        $privateKey = file_get_contents($privateKeyPath);

        $algorithm = config('betby.jwt_algorithm', 'ES256');

        try {
            return JWT::encode($payload, $privateKey, $algorithm);
        } catch (\Exception $e) {
            Log::error('Erro ao gerar token JWT para Betby: ' . $e->getMessage());
            return null;
        }
    }
}
