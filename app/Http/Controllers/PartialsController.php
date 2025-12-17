<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\MenuItems;
use App\Models\MenuCategoria;
use App\Models\FooterSettings;
use App\Models\SocialLink;
use App\Models\Setting;
use App\Models\Banner;
use Exception;

class PartialsController extends Controller
{
    /**
     * TTL padrão para cache (em minutos)
     */
    private const CACHE_TTL = 15;

    /**
     * Obter dados consolidados do sidebar
     *
     * @return array
     */
    public static function getSidebarData()
    {
        $locale = app()->getLocale();
        $cacheKey = "partials:sidebar:data:{$locale}";
        
        return Cache::remember($cacheKey, now()->addMinutes(self::CACHE_TTL), function () {
            $data = [];
            
            try {
                // Buscar itens de menu da categoria 6 (top menu)
                $topMenuItems = DB::table('menu_items')
                    ->where('active', '1')
                    ->where('categoria', 6)
                    ->orderBy('ordem', 'asc')
                    ->get(['slug', 'nome', 'link', 'icone']);
                    
                $data['topMenuItems'] = [];
                foreach ($topMenuItems as $key => $item) {
                    $processed = [
                        'key' => $key,
                        'slug' => $item->slug,
                        'nome' => $item->nome,
                        'link' => $item->link,
                        'icone' => $item->icone
                    ];
                    
                    // Processar traduções
                    if (preg_match('/\{\{\s*__\(\'(.*?)\'\)\s*\}\}/', $item->slug, $matches)) {
                        $processed['translatedSlug'] = __($matches[1]);
                    } else {
                        $processed['translatedSlug'] = $item->slug;
                    }
                    
                    if (preg_match('/\{\{\s*__\(\'(.*?)\'\)\s*\}\}/', $item->nome, $matches)) {
                        $processed['translatedName'] = __($matches[1]);
                    } else {
                        $processed['translatedName'] = $item->nome;
                    }
                    
                    // Processar links
                    if (preg_match('/\{\{\s*route\([\'"](.+?)[\'"]\)\s*\}\}/', $item->link, $matches)) {
                        try {
                            $processed['linkUrl'] = route($matches[1]);
                        } catch (Exception $e) {
                            $processed['linkUrl'] = 'javascript:void(0);';
                        }
                    } else {
                        $processed['linkUrl'] = $item->link ?: 'javascript:void(0);';
                    }
                    
                    // Definir classes do botão
                    if ($key == 0) {
                        $processed['buttonClass'] = "f0Xlz CcmQs hNTLMN relative";
                    } elseif ($key == 1) {
                        $processed['buttonClass'] = "f0Xlz CcmQs Cfgx8 relative";
                    } else {
                        $processed['buttonClass'] = "f0Xlz CcmQs hNTLM relative";
                    }
                    
                    $data['topMenuItems'][] = $processed;
                }
                
                // Buscar categorias de menu e seus itens
                $categoriesWithItems = DB::table('menu_categoria as mc')
                    ->leftJoin('menu_items as mi', function($join) {
                        $join->on('mc.id', '=', 'mi.categoria')
                             ->where('mi.active', '=', '1');
                    })
                    ->where('mc.tipo', 0)
                    ->where('mc.active', '1')
                    ->where('mc.id', '!=', 6)
                    ->orderBy('mc.id', 'asc')
                    ->orderBy('mi.ordem', 'asc')
                    ->get([
                        'mc.id as category_id',
                        'mc.slug as category_slug',
                        'mi.nome as item_nome',
                        'mi.link as item_link',
                        'mi.icone as item_icone'
                    ]);
                
                // Agrupar os resultados por categoria
                $groupedData = [];
                foreach ($categoriesWithItems as $row) {
                    $categoryId = $row->category_id;
                    
                    if (!isset($groupedData[$categoryId])) {
                        $groupedData[$categoryId] = [
                            'id' => $categoryId,
                            'slug' => $row->category_slug,
                            'items' => []
                        ];
                        
                        // Processar tradução da categoria
                        if (preg_match('/\{\{\s*__\(\'(.*?)\'\)\s*\}\}/', $row->category_slug, $matches)) {
                            $groupedData[$categoryId]['translatedSlug'] = __($matches[1]);
                        } else {
                            $groupedData[$categoryId]['translatedSlug'] = $row->category_slug;
                        }
                    }
                    
                    // Adicionar item se existir
                    if ($row->item_nome) {
                        $processedItem = [
                            'nome' => $row->item_nome,
                            'link' => $row->item_link,
                            'icone' => $row->item_icone
                        ];
                        
                        // Processar nome do item
                        if (preg_match('/\{\{\s*__\(\'(.*?)\'\)\s*\}\}/', $row->item_nome, $matches)) {
                            $processedItem['translatedName'] = __($matches[1]);
                        } else {
                            $processedItem['translatedName'] = $row->item_nome;
                        }
                        
                        // Processar link do item
                        if (preg_match('/\{\{\s*route\([\'"](.+?)[\'"]\)\s*\}\}/', $row->item_link, $matches)) {
                            try {
                                $processedItem['processedLink'] = 'href="' . route($matches[1]) . '"';
                            } catch (Exception $e) {
                                $processedItem['processedLink'] = "href='javascript:void(0);'";
                            }
                        } elseif (preg_match('/OpenGame\([\'"](.+?)[\'"]\s*,\s*[\'"](.+?)[\'"]\)/', $row->item_link, $matches)) {
                            $processedItem['processedLink'] = 'href="javascript:void(0);" onclick="OpenGame(\'' . $matches[1] . '\', \'' . $matches[2] . '\');"';
                        } elseif (preg_match('/LinkMobile\([\'"](.+?)[\'"]\)/', $row->item_link, $matches)) {
                            $processedItem['processedLink'] = 'href="javascript:void(0);" onclick="LinkMobile(\'' . $matches[1] . '\');"';
                        } elseif (strpos($row->item_link, 'casino/') !== false) {
                            $processedItem['processedLink'] = 'href="javascript:void(0);" onclick="OpenGame(\'games\', \'' . $row->item_link . '\');"';
                        } elseif ($row->item_link != "") {
                            $processedItem['processedLink'] = 'href="' . $row->item_link . '"';
                        } else {
                            $processedItem['processedLink'] = "href='javascript:void(0);'";
                        }
                        
                        $groupedData[$categoryId]['items'][] = $processedItem;
                    }
                }
                
                $data['categories'] = array_values($groupedData);
                
            } catch (Exception $e) {
                // Em caso de erro, retornar estrutura vazia para evitar quebrar o sidebar
                $data = [
                    'topMenuItems' => [],
                    'categories' => []
                ];
            }
            
            return $data;
        });
    }
    
    /**
     * Obter dados do footer
     *
     * @return array
     */
    public static function getFooterData()
    {
        return Cache::remember('partials:footer:data', now()->addMinutes(self::CACHE_TTL), function () {
            $data = [];
            
            try {
                // Buscar configurações do footer
                $footerSettings = FooterSettings::getSettings();
                $socialLinks = SocialLink::first();
                $siteInfo = Setting::first();
                
                // Processar dados do footer
                $data['footerSettings'] = $footerSettings;
                $data['socialLinks'] = $socialLinks;
                $data['siteInfo'] = $siteInfo;
                
                // URLs das redes sociais
                $data['whatsappUrl'] = $socialLinks->whatsapp ?? '';
                $data['instagramUrl'] = $socialLinks->instagram ?? '';
                $data['telegramUrl'] = $socialLinks->telegram ?? '';
                $data['facebookUrl'] = $socialLinks->facebook ?? '';
                
                // URLs e configurações
                $data['contactButtonUrl'] = $footerSettings->contact_button_url ?? '';
                $data['showSocialLinks'] = $footerSettings->show_social_links ?? false;
                $data['showAutorizadoCassino'] = $footerSettings->show_autorizado_cassino ?? false;
                
                // Configurações individuais das redes sociais
                $data['showInstagram'] = $socialLinks->show_instagram ?? false;
                $data['showFacebook'] = $socialLinks->show_facebook ?? false;
                $data['showWhatsapp'] = $socialLinks->show_whatsapp ?? false;
                $data['showTelegram'] = $socialLinks->show_telegram ?? false;
                
                // Detectar idioma atual e configurar bandeira/texto patriótico
                $currentLocale = app()->getLocale() ?: 'pt_BR';
                
                // Configurar bandeira e texto baseado no idioma
                switch ($currentLocale) {
                    case 'en':
                        $data['flagImage'] = 'img/flags/USA.svg';
                        $data['flagAlt'] = 'USA';
                        break;
                    case 'es':
                        $data['flagImage'] = 'img/flags/ESP.svg';
                        $data['flagAlt'] = 'ESP';
                        break;
                    case 'pt_BR':
                    default:
                        $data['flagImage'] = 'img/flags/BRA.svg';
                        $data['flagAlt'] = 'BRA';
                        break;
                }
                
                // Calcular tempo logado se usuário estiver autenticado
                if (auth()->check()) {
                    $loginTimestamp = session('login_timestamp');
                    $data['loginTimestamp'] = $loginTimestamp;
                    
                    if ($loginTimestamp && is_numeric($loginTimestamp)) {
                        $initialDiff = time() - $loginTimestamp;
                        $hours = floor($initialDiff / 3600);
                        $minutes = floor(($initialDiff % 3600) / 60);
                        $seconds = $initialDiff % 60;
                        $data['initialTimeLogged'] = sprintf('%02dh %02dm %02ds', $hours, $minutes, $seconds);
                    } else {
                        $data['initialTimeLogged'] = '00h 00m 00s';
                    }
                    
                    // Formatação do último login
                    $lastLogin = auth()->user()->last_login;
                    if (!empty($lastLogin)) {
                        try {
                            $lastLoginDate = \Carbon\Carbon::parse($lastLogin);
                            $data['formattedLastLogin'] = $lastLoginDate->format('d/m/Y, H:i');
                        } catch (\Exception $e) {
                            $data['formattedLastLogin'] = 'N/A';
                        }
                    } else {
                        $data['formattedLastLogin'] = 'N/A';
                    }
                }
                
                // Gerar emails baseados no domínio
                $domain = parse_url(config('app.url'), PHP_URL_HOST);
                $data['emails'] = [
                    'support' => "suporte@{$domain}",
                    'contact' => "contato@{$domain}",
                    'atendimento' => $footerSettings->support_email ?? "atendimento@{$domain}"
                ];
                
            } catch (Exception $e) {
                // Em caso de erro, retornar estrutura mínima
                $data = [
                    'footerSettings' => (object) ['footer_text' => '', 'footer_subtext' => ''],
                    'socialLinks' => (object) [],
                    'siteInfo' => (object) ['name' => config('app.name'), 'logo' => 'img/logo-inove.png'],
                    'showSocialLinks' => false,
                    'showAutorizadoCassino' => false,
                    'flagImage' => 'img/flags/BRA.svg',
                    'flagAlt' => 'BRA',
                    'emails' => ['atendimento' => 'atendimento@exemplo.com']
                ];
            }
            
            return $data;
        });
    }
    
    /**
     * Obter todos os dados necessários para a sidebar já processados
     *
     * @return array
     */
    public static function getCompleteSidebarData()
    {
        $currentLocale = app()->getLocale() ?: 'pt_BR';
        $cacheKey = "partials:complete_sidebar:data:{$currentLocale}";
        
        return Cache::remember($cacheKey, now()->addMinutes(self::CACHE_TTL), function () use ($currentLocale) {
            try {
                // Verificar o estado do topbar com base no cookie
                $topbarClosed = isset($_COOKIE['topbar_closed']) && $_COOKIE['topbar_closed'] === 'true';
                $sidebarHeight = $topbarClosed ? '65px' : '105px';
                
                // Buscar informações do site
                $footerSettings = FooterSettings::getSettings();
                $siteInfos = Setting::first();
                
                $siteInfo = (object)[
                    'name' => $siteInfos->name ?? config('app.name'),
                    'logo' => $siteInfos->logo ?? 'img/logo-inove.png'
                ];
                
                // Buscar itens do menu topo (categoria 6)
                $topMenuItems = [];
                $topMenuData = MenuItems::where('active', '1')
                    ->where('categoria', 6)
                    ->orderBy('ordem', 'asc')
                    ->get();
                
                foreach ($topMenuData as $key => $item) {
                    // Processar slug para tradução
                    $slugText = $item->slug;
                    if (preg_match('/\{\{\s*__\(\'(.*?)\'\)\s*\}\}/', $slugText, $matches)) {
                        $translationKey = $matches[1];
                        $translatedSlug = __($translationKey);
                    } else {
                        $translatedSlug = $slugText;
                    }

                    // Processar nome para tradução
                    $nameText = $item->nome;
                    if (preg_match('/\{\{\s*__\(\'(.*?)\'\)\s*\}\}/', $nameText, $matches)) {
                        $translationKey = $matches[1];
                        $translatedName = __($translationKey);
                    } else {
                        $translatedName = $nameText;
                    }

                    // Processar link para interpretar rotas do Laravel
                    $linkText = $item->link;
                    if (preg_match('/\{\{\s*route\([\'"](.+?)[\'"]\)\s*\}\}/', $linkText, $matches)) {
                        $routeName = $matches[1];
                        $linkUrl = route($routeName);
                    } else {
                        $linkUrl = $linkText ?: 'javascript:void(0);';
                    }

                    // Definir classes do botão com base na posição
                    if ($key == 0) {
                        $buttonClass = "f0Xlz CcmQs hNTLMN relative";
                    } elseif ($key == 1) {
                        $buttonClass = "f0Xlz CcmQs Cfgx8 relative";
                    } else {
                        $buttonClass = "f0Xlz CcmQs hNTLM relative";
                    }
                    
                    $topMenuItems[] = [
                        'translatedName' => $translatedName,
                        'translatedSlug' => $translatedSlug,
                        'linkUrl' => $linkUrl,
                        'buttonClass' => $buttonClass,
                        'icone' => $item->icone
                    ];
                }
                
                // Buscar categorias e seus itens
                $categories = [];
                $categoriesData = MenuCategoria::where('tipo', 0)
                    ->where('active', '1')
                    ->where('id', '!=', 6)
                    ->orderBy('id', 'asc')
                    ->get();
                
                foreach ($categoriesData as $category) {
                    // Processar slug da categoria para tradução
                    $slugText = $category->slug;
                    if (preg_match('/\{\{\s*__\(\'(.*?)\'\)\s*\}\}/', $slugText, $matches)) {
                        $translationKey = $matches[1];
                        $translatedSlug = __($translationKey);
                    } else {
                        $translatedSlug = $slugText;
                    }
                    
                    // Buscar itens da categoria
                    $categoryItems = [];
                    $itemsData = MenuItems::where('categoria', $category->id)
                        ->where('active', '1')
                        ->orderBy('ordem', 'asc')
                        ->get();
                    
                    foreach ($itemsData as $item) {
                        // Processar link do item
                        if (preg_match('/\{\{\s*route\([\'"](.+?)[\'"]\)\s*\}\}/', $item->link, $matches)) {
                            $routeName = $matches[1];
                            $link = 'href="' . route($routeName) . '"';
                        }
                        // Se for um link para OpenGame
                        elseif (preg_match('/OpenGame\([\'"](.+?)[\'"]\s*,\s*[\'"](.+?)[\'"]\)/', $item->link, $matches)) {
                            $gameType = $matches[1];
                            $gameSlug = $matches[2];
                            $link = 'href="JavaScript: Void(0);" onclick="OpenGame(\'' . $gameType . '\', \'' . $gameSlug . '\');"';
                        }
                        // Se for um LinkMobile
                        elseif (preg_match('/LinkMobile\([\'"](.+?)[\'"]\)/', $item->link, $matches)) {
                            $mobilePath = $matches[1];
                            $link = 'href="javascript: void(0);" onclick="LinkMobile(\'' . $mobilePath . '\');"';
                        }
                        // Se o link contém casino/ (padrão de link direto para jogos)
                        elseif (strpos($item->link, 'casino/') !== false) {
                            $gameSlug = $item->link;
                            $link = 'href="JavaScript: Void(0);" onclick="OpenGame(\'games\', \'' . $gameSlug . '\');"';
                        }
                        // Se não for rota e tiver um link definido
                        elseif ($item->link != "") {
                            $link = 'href="' . $item->link . '"';
                        }
                        // Se não tiver link
                        else {
                            $link = "href='JavaScript: Void(0);'";
                        }

                        // Processar nome do item para tradução
                        $itemName = $item->nome;
                        if (preg_match('/\{\{\s*__\(\'(.*?)\'\)\s*\}\}/', $itemName, $matches)) {
                            $nameKey = $matches[1];
                            $translatedName = __($nameKey);
                        } else {
                            $translatedName = $itemName;
                        }
                        
                        $categoryItems[] = [
                            'link' => $link,
                            'icone' => $item->icone,
                            'translatedName' => $translatedName
                        ];
                    }
                    
                    $categories[] = [
                        'translatedSlug' => $translatedSlug,
                        'items' => $categoryItems
                    ];
                }
                
                // Configurações de idioma
                $currentLanguageName = match($currentLocale) {
                    'pt_BR' => 'Português',
                    'en' => 'English',
                    'es' => 'Español',
                    default => 'Português'
                };
                
                // Opções de idiomas (excluindo o atual)
                $allLanguages = [
                    'pt_BR' => ['name' => 'Português', 'flag' => 'pt_BR.png'],
                    'en' => ['name' => 'English', 'flag' => 'en.png'],
                    'es' => ['name' => 'Español', 'flag' => 'es.png']
                ];
                
                $languageOptions = [];
                foreach ($allLanguages as $locale => $data) {
                    if ($locale !== $currentLocale) {
                        $languageOptions[] = [
                            'locale' => $locale,
                            'name' => $data['name'],
                            'flag' => $data['flag'],
                            'url' => route('language.switch', ['locale' => $locale])
                        ];
                    }
                }
                
                // Compilar dados finais
                $data = [
                    'sidebarHeight' => $sidebarHeight,
                    'homeUrl' => route('home'),
                    'isHomeActive' => request()->routeIs('home*'),
                    'siteInfo' => $siteInfo,
                    'topMenuItems' => $topMenuItems,
                    'categories' => $categories,
                    'contactButtonUrl' => $footerSettings->contact_button_url ?? '#',
                    'currentLocale' => $currentLocale,
                    'currentLanguageName' => $currentLanguageName,
                    'languageOptions' => $languageOptions,
                    'translations' => [
                        'help_center' => __('messages.help_center'),
                        'blog' => __('messages.blog')
                    ]
                ];
                
                return $data;
                
            } catch (\Exception $e) {
                // Retornar estrutura básica em caso de erro
                return [
                    'sidebarHeight' => '105px',
                    'homeUrl' => route('home'),
                    'isHomeActive' => false,
                    'siteInfo' => (object)['name' => config('app.name'), 'logo' => 'img/logo-inove.png'],
                    'topMenuItems' => [],
                    'categories' => [],
                    'contactButtonUrl' => '#',
                    'currentLocale' => app()->getLocale() ?: 'pt_BR',
                    'currentLanguageName' => 'Português',
                    'languageOptions' => [],
                    'translations' => [
                        'help_center' => 'Central de Ajuda',
                        'blog' => 'Blog'
                    ]
                ];
            }
        });
    }

    /**
     * Obter dados dos banners
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getBannersData()
    {
        return Cache::remember('partials:banners:data', now()->addMinutes(self::CACHE_TTL), function () {
            try {
                return Banner::where('active', true)->orderBy('ordem')->get();
            } catch (Exception $e) {
                Log::error('Error getting banners data: ' . $e->getMessage());
                return collect(); // Retorna coleção vazia em caso de erro
            }
        });
    }

    /**
     * Obter dados do banner mais recente para exibição inicial
     *
     * @return array
     */
    public static function getLatestBannerData()
    {
        return Cache::remember('partials:latest_banner:data', now()->addMinutes(self::CACHE_TTL), function () {
            try {
                $banners = Banner::where('active', true)
                    ->where('tipo', 'slide')
                    ->orderBy('ordem', 'asc')
                    ->get();
                
                $bannerMaisRecente = $banners->first();
                
                return [
                    'bannerMaisRecente' => $bannerMaisRecente,
                    'hasLatestBanner' => $bannerMaisRecente ? true : false,
                    'preloadUrl' => $bannerMaisRecente && !empty($bannerMaisRecente->imagem) ? asset($bannerMaisRecente->imagem) : null
                ];
            } catch (Exception $e) {
                Log::error('Error getting latest banner data: ' . $e->getMessage());
                return [
                    'bannerMaisRecente' => null,
                    'hasLatestBanner' => false,
                    'preloadUrl' => null
                ];
            }
        });
    }

    /**
     * Obter banners do tipo slide ativos via AJAX
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSliderBanners()
    {
        try {
            $banners = Cache::remember('partials:slider_banners:data', now()->addMinutes(self::CACHE_TTL), function () {
                return Banner::where('active', true)
                    ->where('tipo', 'slide')
                    ->orderBy('ordem', 'asc')
                    ->get(['id', 'imagem', 'link', 'ordem']);
            });
            
            // Processar dados dos banners
            $processedBanners = $banners->map(function ($banner) {
                return [
                    'id' => $banner->id,
                    'imagem' => $banner->imagem ? asset($banner->imagem) : null,
                    'link' => $banner->link,
                    'ordem' => $banner->ordem
                ];
            });

            return response()->json($processedBanners);
        } catch (\Exception $e) {
            Log::error('Error getting slider banners: ' . $e->getMessage());
            return response()->json([
                'error' => 'Erro ao carregar banners',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Obter apenas o banner mais recente do tipo slide via AJAX
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLatestSliderBanner()
    {
        try {
            $banner = Cache::remember('partials:latest_slider_banner:data', now()->addMinutes(self::CACHE_TTL), function () {
                return Banner::where('active', true)
                    ->where('tipo', 'slide')
                    ->orderBy('ordem', 'asc')
                    ->first(['id', 'imagem', 'link', 'ordem']);
            });
            
            if (!$banner) {
                return response()->json(null);
            }
            
            $processedBanner = [
                'id' => $banner->id,
                'imagem' => $banner->imagem ? asset($banner->imagem) : null,
                'link' => $banner->link,
                'ordem' => $banner->ordem
            ];

            return response()->json($processedBanner);
        } catch (\Exception $e) {
            Log::error('Error getting latest slider banner: ' . $e->getMessage());
            return response()->json([
                'error' => 'Erro ao carregar banner',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Limpar cache das partials
     *
     * @return bool
     */
    public static function clearPartialsCache()
    {
        try {
            // Limpar caches específicos
            $locales = ['pt_BR', 'en', 'es'];
            
            foreach ($locales as $locale) {
                Cache::forget("partials:sidebar:data:{$locale}");
                Cache::forget("partials:complete_sidebar:data:{$locale}");
            }
            
            Cache::forget('partials:footer:data');
            Cache::forget('partials:banners:data');
            Cache::forget('partials:latest_banner:data');
            Cache::forget('partials:slider_banners:data');
            Cache::forget('partials:latest_slider_banner:data');
            
            return true;
            
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Método para debug - verificar dados do banco
     */
    public static function debugSidebarData()
    {
        try {
            $menuItems = DB::table('menu_items')->get();
            $categories = DB::table('menu_categoria')->get();
            
            return [
                'menu_items_count' => $menuItems->count(),
                'categories_count' => $categories->count(),
                'menu_items' => $menuItems,
                'categories' => $categories
            ];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Processar dados de paginação
     *
     * @param mixed $items - Objeto de paginação
     * @param string $paginationId - ID único para a paginação
     * @param string $route - Rota para navegação
     * @param string $targetId - ID do contêiner a ser atualizado
     * @return array
     */
    public static function processPaginationData($items = null, $paginationId = 'pagination-default', $route = null, $targetId = 'content-container')
    {
        try {
            // Definir variáveis com valores padrão
            $items = $items ?? request()->get('saques') ?? null;
            
            // Obter o idioma atual da aplicação
            $currentLocale = app()->getLocale();
            
            // Verificar se $items é um objeto de paginação válido com resultados
            $hasPagination = $items && method_exists($items, 'total') && $items->total() > 0;

            if (!$hasPagination) {
                return [
                    'hasPagination' => false,
                    'paginationId' => $paginationId,
                    'targetId' => $targetId,
                    'currentLocale' => $currentLocale
                ];
            }

            // Configuração para exibição de páginas
            $onEachSide = 2; // Número de links em cada lado da página atual
            
            $currentPage = $items->currentPage();
            $lastPage = $items->lastPage();
            
            // Determinar o intervalo de páginas a exibir
            $startPage = max(1, $currentPage - $onEachSide);
            $endPage = min($lastPage, $currentPage + $onEachSide);
            
            // Ajustar para garantir que mostramos pelo menos 5 links quando possível
            if ($startPage > 1 && $endPage < $lastPage) {
                $diff = $onEachSide * 2 + 1 - ($endPage - $startPage + 1);
                if ($diff > 0) {
                    if ($startPage > 1) {
                        $addToStart = min($startPage - 1, $diff);
                        $startPage -= $addToStart;
                        $diff -= $addToStart;
                    }
                    
                    if ($diff > 0 && $endPage < $lastPage) {
                        $endPage += min($lastPage - $endPage, $diff);
                    }
                }
            }

            // Calcular informações de exibição
            $firstItem = $items->firstItem() ?? 0;
            $lastItem = $items->lastItem() ?? 0;
            $total = $items->total() ?? 0;
            
            // Se não há itens, mostrar 0 para ambos
            if ($total == 0) {
                $firstItem = 0;
                $lastItem = 0;
            }

            // Gerar array de páginas para loop
            $pageRange = [];
            for ($i = $startPage; $i <= $endPage; $i++) {
                $pageRange[] = [
                    'number' => $i,
                    'url' => $items->url($i),
                    'isCurrent' => $i == $currentPage
                ];
            }

            // Preservar parâmetros de query string
            $queryParams = [];
            foreach (request()->query() as $key => $value) {
                if ($key !== 'page') {
                    $queryParams[] = [
                        'name' => $key,
                        'value' => $value
                    ];
                }
            }

            return [
                'hasPagination' => true,
                'paginationId' => $paginationId,
                'targetId' => $targetId,
                'currentLocale' => $currentLocale,
                'items' => $items,
                'currentPage' => $currentPage,
                'lastPage' => $lastPage,
                'startPage' => $startPage,
                'endPage' => $endPage,
                'pageRange' => $pageRange,
                'firstItem' => $firstItem,
                'lastItem' => $lastItem,
                'total' => $total,
                'queryParams' => $queryParams,
                'urls' => [
                    'first' => $items->url(1),
                    'previous' => $items->previousPageUrl(),
                    'next' => $items->nextPageUrl(),
                    'last' => $items->url($lastPage),
                    'current' => url()->current()
                ],
                'states' => [
                    'onFirstPage' => $items->onFirstPage(),
                    'hasMorePages' => $items->hasMorePages(),
                    'showFirstEllipsis' => $startPage > 2,
                    'showLastEllipsis' => $endPage < $lastPage - 1,
                    'showFirstPage' => $startPage > 1,
                    'showLastPage' => $endPage < $lastPage
                ],
                'translations' => [
                    'first_page' => __('pagination.first_page'),
                    'prev_page' => __('pagination.prev_page'),
                    'next_page' => __('pagination.next_page'),
                    'last_page' => __('pagination.last_page'),
                    'goto_page' => __('pagination.goto_page'),
                    'showing' => __('pagination.showing'),
                    'to' => __('pagination.to'),
                    'of' => __('pagination.of'),
                    'records' => __('pagination.records')
                ]
            ];

        } catch (Exception $e) {
            Log::error('Error processing pagination data: ' . $e->getMessage());
            
            return [
                'hasPagination' => false,
                'paginationId' => $paginationId,
                'targetId' => $targetId,
                'currentLocale' => app()->getLocale(),
                'error' => true
            ];
        }
    }
} 