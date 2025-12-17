<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GamesApi;
use App\Models\Admin\Providers;
use App\Models\GameHistory;
use App\Models\User;
use App\Models\HomeCustomField;
use App\Models\HomeCustomFieldGame;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CassinoController extends Controller
{
    public function jogosProvedores()
    {
        // Buscar todos os provedores ordenados por ID
        $ProvidersName = Providers::orderBy('id', 'asc')->get();

        // Obter todas as distribuições disponíveis
        $availableDistributions = Providers::whereNotNull('distribution')
            ->where('distribution', '!=', '')
            ->distinct()
            ->pluck('distribution')
            ->filter()
            ->values()
            ->toArray();

        // Obter todas as carteiras disponíveis para filtro
        $providersWallets = Providers::whereNotNull('wallets')
            ->where('wallets', '!=', '')
            ->where('wallets', '!=', '[]')
            ->distinct()
            ->pluck('wallets');
            
        $availableWallets = [];
        foreach ($providersWallets as $walletData) {
            $decodedWallets = null;
            
            // Tentar como JSON primeiro
            if (is_string($walletData)) {
                $decodedWallets = json_decode($walletData, true);
            }
            
            // Se já é array
            if (!is_array($decodedWallets) && is_array($walletData)) {
                $decodedWallets = $walletData;
            }
            
            // Se é string com vírgulas
            if (!is_array($decodedWallets) && is_string($walletData)) {
                if (strpos($walletData, ',') !== false) {
                    $decodedWallets = explode(',', $walletData);
                    $decodedWallets = array_map('trim', $decodedWallets);
                } else if (!empty(trim($walletData)) && $walletData !== '[]') {
                    $decodedWallets = [trim($walletData)];
                }
            }
            
            if (is_array($decodedWallets) && !empty($decodedWallets)) {
                // Filtrar valores vazios
                $validWallets = array_filter($decodedWallets, function($wallet) {
                    return !empty(trim($wallet));
                });
                $availableWallets = array_merge($availableWallets, $validWallets);
            }
        }
        
        $availableWallets = array_unique($availableWallets);
        sort($availableWallets);

        // Obter provedores originais (que contenham "ORIGINAL" no nome)
        $originalProviders = Providers::where('name', 'LIKE', '%ORIGINAL%')
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.cassino.jogos_provedores', compact('ProvidersName', 'availableDistributions', 'availableWallets', 'originalProviders'));
    }

    public function jogosCategorias()
    {
        return view('admin.cassino.jogos_categorias');
    }

    public function jogosTodos()
    {
        try {
            // Obter lista de provedores únicos incluindo TODOS os provedores que têm jogos
            $providersWithData = Providers::select('providers.name', 'providers.distribution', 'providers.wallets')
                ->whereExists(function($query) {
                    $query->select(DB::raw(1))
                          ->from('games_api')
                          ->whereColumn('games_api.provider_id', 'providers.id')
                          ->where('games_api.status', 1);
                })
                ->distinct()
                ->orderBy('providers.name')
                ->get()
                ->map(function($provider) {
                    return [
                        'name' => $provider->name,
                        'distribution' => $provider->distribution,
                        'wallets' => $provider->wallets
                    ];
                })
                ->toArray();

            // Obter lista simples de provedores para compatibilidade
            $providers = collect($providersWithData)->pluck('name')->toArray();

            // Obter lista de distribuições únicas da tabela games_api consolidada
            $distributions = DB::table('games_api')
                ->select('distribution')
                ->distinct()
                ->whereNotNull('distribution')
                ->where('distribution', '!=', '')
                ->where('status', 1)
                ->orderBy('distribution')
                ->pluck('distribution')
                ->toArray();

            // Obter todas as carteiras disponíveis para filtro (apenas de provedores com jogos ativos)
            $providersWallets = Providers::select('wallets')
                ->whereExists(function($query) {
                    $query->select(DB::raw(1))
                          ->from('games_api')
                          ->whereColumn('games_api.provider_id', 'providers.id')
                          ->where('games_api.status', 1);
                })
                ->whereNotNull('wallets')
                ->where('wallets', '!=', '')
                ->where('wallets', '!=', '[]')
                ->distinct()
                ->pluck('wallets');
                
            $availableWallets = [];
            foreach ($providersWallets as $walletData) {
                $decodedWallets = null;
                
                // Tentar como JSON primeiro
                if (is_string($walletData)) {
                    $decodedWallets = json_decode($walletData, true);
                }
                
                // Se já é array
                if (!is_array($decodedWallets) && is_array($walletData)) {
                    $decodedWallets = $walletData;
                }
                
                // Se é string com vírgulas
                if (!is_array($decodedWallets) && is_string($walletData)) {
                    if (strpos($walletData, ',') !== false) {
                        $decodedWallets = explode(',', $walletData);
                        $decodedWallets = array_map('trim', $decodedWallets);
                    } else if (!empty(trim($walletData)) && $walletData !== '[]') {
                        $decodedWallets = [trim($walletData)];
                    }
                }
                
                if (is_array($decodedWallets) && !empty($decodedWallets)) {
                    // Filtrar valores vazios
                    $validWallets = array_filter($decodedWallets, function($wallet) {
                        return !empty(trim($wallet));
                    });
                    $availableWallets = array_merge($availableWallets, $validWallets);
                }
            }
            
            $availableWallets = array_unique($availableWallets);
            sort($availableWallets);
                
            return view('admin.cassino.jogos_todos', compact('providers', 'distributions', 'availableWallets', 'providersWithData'));
        } catch (\Exception $e) {
            \Log::error('Erro no método jogosTodos', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Retornar arrays vazios em caso de erro
            $providers = [];
            $distributions = [];
            $availableWallets = [];
            $providersWithData = [];
            return view('admin.cassino.jogos_todos', compact('providers', 'distributions', 'availableWallets', 'providersWithData'));
        }
    }

    /**
     * Fornece dados para a tabela de jogos de cassino
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function jogosTodosData(Request $request)
    {
        try {
            // Nova abordagem consolidada: Buscar diretamente na games_api
            $query = DB::table('games_api')
                ->leftJoin('providers', 'games_api.provider_id', '=', 'providers.id')
                ->select(
                    'games_api.id as game_id',
                    'games_api.slug',
                    'games_api.distribution',
                    'games_api.status',
                    'games_api.provider_id',
                    'games_api.image', 
                    'games_api.name', 
                    'games_api.show_home', 
                    'games_api.destaque', 
                    'games_api.views', 
                    'games_api.status', 
                    'games_api.created_at',
                    'providers.name as provider_name',
                    'providers.wallets as provider_wallets'
                );

            // Aplicar filtros
            if ($request->has('status') && $request->get('status') !== 'all' && $request->get('status') !== '') {
                $query->where('games_api.status', $request->get('status'));
            }

            if ($request->has('provider') && $request->get('provider') !== 'all' && $request->get('provider') !== '') {
                $query->where('providers.name', $request->get('provider'));
            }

            if ($request->has('distribution') && $request->get('distribution') !== 'all' && $request->get('distribution') !== '') {
                $query->where('games_api.distribution', $request->get('distribution'));
            }

            if ($request->has('wallet') && $request->get('wallet') !== 'all' && $request->get('wallet') !== '') {
                $wallet = $request->get('wallet');
                $query->where('providers.wallets', 'LIKE', '%' . $wallet . '%');
            }

            if ($request->has('original') && $request->get('original') !== 'all' && $request->get('original') !== '') {
                $original = $request->get('original');
                if ($original === 'original') {
                    $query->where('providers.name', 'LIKE', '%ORIGINAL%');
                } else { // clone
                    $query->where('providers.name', 'NOT LIKE', '%ORIGINAL%');
                }
            }

            // Criar e configurar o DataTable
            return DataTables::of($query)
                ->filter(function ($query) use ($request) {
                    // Filtro de busca global (barra de pesquisa do DataTable)
                    if ($request->has('search') && !empty($request->search['value'])) {
                        $searchTerm = $request->search['value'];
                        $query->where(function($q) use ($searchTerm) {
                            $q->where('games_api.name', 'LIKE', "%{$searchTerm}%")
                              ->orWhere('games_api.slug', 'LIKE', "%{$searchTerm}%")
                              ->orWhere('providers.name', 'LIKE', "%{$searchTerm}%");
                        });
                    }
                })
                ->addColumn('id', function ($row) {
                    return '<strong>' . $row->game_id . '</strong>';
                })
                ->addColumn('capa', function ($row) {
                    // Processar a URL da imagem para garantir que esteja correta
                    $imageUrl = $row->image;
                    
                    // Se a URL já contém o domínio completo, usar como está
                    if (strpos($imageUrl, 'http') === 0) {
                        $finalImageUrl = $imageUrl;
                    } else {
                        // Se não contém o domínio, construir a URL completa
                        $finalImageUrl = asset($imageUrl);
                    }
                    
                    return '<div class="d-flex align-items-center">
                                <!-- Preview da imagem do jogo -->
                                <div class="game-image-container me-3" style="position: relative;">
                                    <div id="gameImagePreview'.$row->game_id.'" class="game-image-preview" 
                                        style="width: 50px;height: 50px;border-radius: 8px;overflow: hidden; background-color: #4361ee; display: flex;align-items: center;justify-content: center;border: 1px solid #6984ff;cursor: pointer;"
                                        onclick="openGameImageModal(\''.$row->game_id.'\', \''.$row->name.'\', \''.$finalImageUrl.'\')">
                                        <img src="'.$finalImageUrl.'" 
                                            alt="'.$row->name.'" 
                                            class="img-fluid" 
                                            style="width: 100%; height: 100%; object-fit: contain;"
                                            onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'flex\';">
                                        <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center;">
                                            <i class="fa fa-image text-muted" style="font-size: 20px;color: #fff!important;"></i>
                                        </div>
                                    </div>
                                    
                                    <!-- Botão para trocar imagem -->
                                    <button type="button" 
                                        class="btn btn-sm btn-danger game-image-btn" 
                                        title="Trocar imagem do jogo"
                                        onclick="document.getElementById(\'gameImageInput'.$row->game_id.'\').click();"
                                        style="position: absolute; bottom: -5px; right: -5px; width: 20px; height: 20px; border-radius: 50%; padding: 0; display: flex; align-items: center; justify-content: center;">
                                        <i class="fa fa-camera" style="font-size: 10px;"></i>
                                    </button>
                                    
                                    <!-- Input file oculto -->
                                    <input id="gameImageInput'.$row->game_id.'" 
                                        type="file" 
                                        style="display: none;" 
                                        accept="image/jpeg,image/jpg,image/png,image/gif,image/webp,image/avif"
                                        data-game-id="'.$row->game_id.'"
                                        data-game-name="'.$row->name.'"
                                        class="game-image-input">
                                </div>
                            </div>';
                })
                ->addColumn('provedor', function ($row) {
                    // Usar provider_name que já vem do join com providers via api_games_slugs
                    $providerName = $row->provider_name ?? 'Sem Provedor';
                    return '<span class="badge badge-light-info mb-2 me-4 provider-badge"
                                data-game-id="'.$row->game_id.'"
                                data-game-name="'.$row->name.'"
                                data-provider="'.$providerName.'"
                                style="cursor:pointer">
                                '.$providerName.'
                            </span>';
                })
                ->addColumn('nome', function ($row) {
                    return '<div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span>' . $row->name . '</span>
                                    <br><small class="text-muted">Slug: ' . $row->slug . '</small>
                                </div>
                                <button type="button" 
                                    class="btn btn-sm btn-outline-primary ms-2" 
                                    title="Editar nome e slugs do jogo"
                                    onclick="openEditGameModal(\'' . $row->game_id . '\', \'' . addslashes($row->name) . '\')">
                                    <i class="fa fa-edit"></i>
                                </button>
                            </div>';
                })
                ->addColumn('distribuicao', function ($row) {
                    // Exibir a distribuição diretamente da games_api
                    $distribution = $row->distribution;
                    
                    if (empty($distribution)) {
                        return '<span class="badge badge-light-secondary">Sem Distribuição</span>';
                    }
                    
                    // Badge para a distribuição
                    $badgeClass = match($distribution) {
                        'Inove' => 'badge-light-info',
                        default => 'badge-light-secondary'
                    };
                    
                    return '<span class="badge '.$badgeClass.'">'.$distribution.'</span>';
                })
                ->addColumn('exibir_home', function ($row) {
                    return '<div class="form-check form-switch"><input class="form-check-input" type="checkbox" role="switch" id="flexSwitchShowHome'.$row->game_id.'" '.($row->show_home == 1 ? 'checked=""' : '').' onchange="confirmAndUpdateGameField(\''.$row->game_id.'\', \'show_home\', this.checked ? 1 : 0, this)"></div>';
                })
                ->addColumn('destaque', function ($row) {
                    return '<div class="form-check form-switch"><input class="form-check-input" type="checkbox" role="switch" id="flexSwitchDestaque'.$row->game_id.'" '.($row->destaque == 1 ? 'checked=""' : '').' onchange="confirmAndUpdateGameField(\''.$row->game_id.'\', \'destaque\', this.checked ? 1 : 0, this)"></div>';
                })
                ->addColumn('views', function ($row) {
                    return number_format($row->views);
                })
                ->addColumn('ativo', function ($row) {
                    return '<div class="form-check form-switch"><input class="form-check-input" type="checkbox" role="switch" id="flexSwitchGameActive'.$row->game_id.'" '.($row->status == 1 ? 'checked=""' : '').' onchange="confirmAndUpdateSlugField(\''.$row->game_id.'\', \'status\', this.checked ? 1 : 0, this)"></div>';
                })
                ->rawColumns(['id', 'capa', 'distribuicao', 'provedor', 'nome', 'exibir_home', 'destaque', 'ativo'])
                ->make(true);
                
        } catch (\Exception $e) {
            \Log::error('Erro ao processar DataTable Jogos', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Ocorreu um erro ao processar os dados.',
                'message' => $e->getMessage(),
                'data' => []
            ], 500);
        }
    }



    public function jogosPartidas(Request $request)
    {
        $nomeUsuario = $request->input('aff', '');
        $dataInicial = $request->input('di', Carbon::now()->subDays(7)->format('Y-m-d'));
        $dataFinal = $request->input('df', Carbon::now()->format('Y-m-d'));

        return view('admin.cassino.jogos_partidas', compact('nomeUsuario', 'dataInicial', 'dataFinal'));
    }

    /**
     * Fornece dados para a tabela de histórico de partidas
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function jogosPartidasData(Request $request)
    {
        $dataInicial = $request->input('dataInicial');
        $dataFinal = $request->input('dataFinal');
        $nomeUsuario = $request->input('nomeUsuario');

            $query = GameHistory::query()
            ->select(
                'games_history.*',
                'users.name as usuario_nome',
                'games_api.name as jogo_nome',
                'games_history.provider as provedor_nome'
            )
            ->leftJoin('users', 'games_history.user_id', '=', 'users.id')
            ->leftJoin('games_api', 'games_history.game', '=', 'games_api.slug');

        // Filtro de data inicial
        if ($dataInicial) {
            $query->whereDate('games_history.created_at', '>=', $dataInicial);
        }

        // Filtro de data final
        if ($dataFinal) {
            $query->whereDate('games_history.created_at', '<=', $dataFinal);
        }

        // Filtro por nome de usuário
        if ($nomeUsuario) {
            $query->where('users.name', 'like', '%' . $nomeUsuario . '%');
        }

        $query->orderBy('games_history.id', 'desc');

        return DataTables::of($query)
            ->addColumn('id_transacao', function ($row) {
                return '<strong>' . $row->id . '</strong>';
            })
            ->addColumn('usuario', function ($row) {
                // Buscar nome do usuário a partir do user_id
                return $row->usuario_nome ?? 'N/A';
            })
            ->addColumn('jogo', function ($row) {
                // Primeiro tenta usar o nome do jogo da tabela games_api
                if (!empty($row->jogo_nome)) {
                    return $row->jogo_nome;
                }

                // Se não encontrou o jogo pelo join, usar a função de busca robusta
                if (!empty($row->game)) {
                    $gameData = $this->findGameBySlug($row->game);
                    if ($gameData) {
                        return $gameData->name;
                    }
                    
                    // Busca mais agressiva antes de usar o slug como fallback
                    $gameDataFallback = \App\Models\GamesApi::select('games_api.name')
                        ->where(function($query) use ($row) {
                            $query->where('games_api.slug', 'LIKE', "%{$row->game}%")
                                  ->orWhere('games_api.name', 'LIKE', "%{$row->game}%");
                        })
                        ->first();
                    
                    if ($gameDataFallback) {
                        return $gameDataFallback->name;
                    }
                    
                    // Como último recurso, formatar o slug
                    return ucwords(str_replace(['-', '_'], ' ', $row->game));
                }

                return 'N/A';
            })
            ->addColumn('provedor', function ($row) {
                return $row->provedor_nome ?? 'N/A';
            })
            ->addColumn('resultado', function ($row) {
                // Formatar o resultado como badge (win verde ou loss vermelho)
                if ($row->action === 'win') {
                    return '<span class="badge badge-light-success mb-2 me-4">Ganhou</span>';
                } elseif ($row->action === 'loss') {
                    return '<span class="badge badge-light-danger mb-2 me-4">Perdeu</span>';
                } else {
                    return $row->action ?? 'N/A';
                }
            })
            ->addColumn('valor', function ($row) {
                return 'R$ ' . number_format($row->amount, 2, ',', '.');
            })
            ->addColumn('data', function ($row) {
                // Formatar a data para o formato brasileiro
                if ($row->created_at) {
                    return Carbon::parse($row->created_at)->format('d/m/Y H:i:s');
                }
                return 'N/A';
            })
            ->rawColumns(['id_transacao', 'resultado'])
            ->make(true);
    }

    /**
     * Função auxiliar para buscar dados do jogo quando o JOIN não funciona
     * Reutilizando a mesma lógica robusta do HomeController
     */
    private function findGameBySlug($gameSlug)
    {
        if (empty($gameSlug)) {
            return null;
        }

        // PRIORIDADE 1: Se for numérico, buscar por ID primeiro (nova abordagem)
        if (is_numeric($gameSlug)) {
            $game = GamesApi::select('id', 'name', 'image')
                ->where('id', $gameSlug)
                ->where('status', 1)
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
                $game = GamesApi::select('id', 'name', 'image')
                    ->where('id', $lastPart)
                    ->where('status', 1)
                    ->first();

                if ($game) {
                    return $game;
                }
            }

            // Tenta buscar pela última parte por slug (compatibilidade)
            $game = GamesApi::select('id', 'name', 'image')
                ->where('slug', 'LIKE', "%{$lastPart}")
                ->where('status', 1)
                ->first();

            if ($game) {
                return $game;
            }
        }

        // PRIORIDADE 3: Buscar jogo pelo slug diretamente na games_api (compatibilidade)
        $game = GamesApi::select('id', 'name', 'image')
            ->where('slug', $gameSlug)
            ->where('status', 1)
            ->first();

        if ($game) {
            return $game;
        }

        // PRIORIDADE 4: Busca mais flexível usando LIKE
        $game = GamesApi::select('id', 'name', 'image')
            ->where('slug', 'LIKE', "%{$gameSlug}%")
            ->where('status', 1)
            ->first();

        return $game;
    }

    public function atualizarProvider(Request $request)
    {
        try {
            $id = $request->input('id');
            $field = $request->input('field');
            $value = $request->input('value');
            $activateGames = $request->input('activate_games', 0);

            $provider = Providers::findOrFail($id);
            $provider->$field = $value;
            $provider->save();

        // Se estiver ativando um provedor ou tiver flag para ativar jogos
        if (($field === 'active' && $value == 1) || $activateGames == 1) {
            $providerId = $provider->id;
            
            // Verificar se o provedor contém "OFICIAL" ou "ORIGINAL" no nome
            $providerNameUpper = strtoupper($provider->name);
            $isOriginalProvider = (str_contains($providerNameUpper, 'OFICIAL') || str_contains($providerNameUpper, 'ORIGINAL'));
            
            // Preparar dados para atualização dos jogos
            $gameUpdateData = ['status' => 1];
            
            // Se for provedor OFICIAL/ORIGINAL, também marcar original = 1
            if ($isOriginalProvider) {
                $gameUpdateData['original'] = 1;
            }
            
            // Ativar os jogos do provedor
            $gamesActivated = \DB::table('games_api')
                ->where('provider_id', $providerId)
                ->update($gameUpdateData);
            
            $slugsActivated = $gamesActivated; // Para compatibilidade

            \Log::info('Slugs ativados automaticamente', [
                'providerId' => $providerId,
                'providerName' => $provider->name,
                'slugsAtivados' => $slugsActivated,
                'isOriginalProvider' => $isOriginalProvider,
                'gamesActivated' => $gamesActivated,
                'motivo' => ($field === 'active' && $value == 1) ? 'Ativação do provedor' : 'Flag activate_games'
            ]);

            $message = "Provedor atualizado com sucesso. {$slugsActivated} slugs foram ativados automaticamente.";
            if ($isOriginalProvider && $gamesActivated > 0) {
                $message .= " {$gamesActivated} jogos foram ativados e marcados como originais.";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'slugsActivated' => $slugsActivated,
                'gamesActivated' => $gamesActivated
            ]);
        }

        // Se estiver desativando um provedor (active = 0)
        if ($field === 'active' && $value == 0) {
            $providerId = $provider->id;

            // Desativar os jogos do provedor usando status
            $gamesDeactivated = \DB::table('games_api')
                ->where('provider_id', $providerId)
                ->update(['status' => 0]);

            \Log::info('Jogos desativados automaticamente', [
                'providerId' => $providerId,
                'providerName' => $provider->name,
                'jogosDesativados' => $gamesDeactivated,
                'motivo' => 'Desativação do provedor'
            ]);

            return response()->json([
                'success' => true,
                'message' => "Provedor atualizado com sucesso. {$gamesDeactivated} jogos foram desativados automaticamente.",
                'slugsDeactivated' => $gamesDeactivated,
                'gamesDeactivated' => $gamesDeactivated
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Provedor atualizado com sucesso!'
        ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar provedor', [
                'id' => $request->input('id'),
                'field' => $request->input('field'),
                'value' => $request->input('value'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar o provedor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getProviderImage(Request $request)
    {
        try {
            $providerId = $request->input('provider_id');
            
            // Buscar o provedor no banco de dados
            $provider = \App\Models\Admin\Providers::findOrFail($providerId);
            
            return response()->json([
                'success' => true,
                'provider' => [
                    'id' => $provider->id,
                    'name' => $provider->name,
                    'img' => $provider->img,
                    'img_url' => $provider->img ? asset($provider->img) : null
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar dados do provedor'
            ], 500);
        }
    }

    public function updateProviderImage(Request $request)
    {
        try {
            // Validar a requisição
            $request->validate([
                'image' => 'required|image|mimes:jpeg,jpg,png,gif,webp,avif|max:5120', // 5MB máximo
                'provider_id' => 'required|integer',
                'provider_name' => 'required|string'
            ]);

            $providerId = $request->input('provider_id');
            $providerName = $request->input('provider_name');
            $image = $request->file('image');

            // Buscar o provedor
            $provider = \App\Models\Admin\Providers::findOrFail($providerId);

            // Verificar se o diretório existe, se não, criar
            $uploadDir = public_path('uploads/providers');
            if (!file_exists($uploadDir)) {
                $created = mkdir($uploadDir, 0755, true);
                if (!$created) {
                    throw new \Exception('Falha ao criar diretório: ' . $uploadDir);
                }
            }
            
            // Verificar se o diretório é gravável
            if (!is_writable($uploadDir)) {
                throw new \Exception('Diretório não é gravável: ' . $uploadDir);
            }

            // Verificar se a imagem já é WebP
            $isWebP = strtolower($image->getClientOriginalExtension()) === 'webp';
            
            // Gerar nome único para a imagem
            $extension = $isWebP ? 'webp' : 'webp'; // Sempre salvar como WebP
            $imageName = 'provider_' . $providerId . '_' . time() . '.' . $extension;
            $imagePath = 'uploads/providers/' . $imageName;

            // Processar a imagem
            if ($isWebP) {
                // Se já é WebP, apenas mover o arquivo
                $moved = $image->move(public_path('uploads/providers'), $imageName);
                if (!$moved) {
                    throw new \Exception('Falha ao mover arquivo WebP');
                }
            } else {
                // Converter para WebP com fundo transparente
                $processedImage = $this->processProviderImageToWebP($image);
                
                // Verificar se a função imagewebp está disponível
                if (!function_exists('imagewebp')) {
                    throw new \Exception('Suporte WebP não disponível nesta versão do PHP');
                }
                
                // Salvar como WebP
                $saved = imagewebp($processedImage, public_path($imagePath), 90);
                if (!$saved) {
                    throw new \Exception('Falha ao salvar imagem WebP');
                }
                
                // Liberar memória
                imagedestroy($processedImage);
            }

            // Remover a imagem anterior se existir
            if ($provider->img && file_exists(public_path($provider->img))) {
                unlink(public_path($provider->img));
            }

            // Atualizar o caminho da imagem no banco de dados
            $provider->img = $imagePath;
            $provider->save();



            return response()->json([
                'success' => true,
                'message' => 'Imagem do provedor atualizada com sucesso!',
                'image_path' => asset($imagePath),
                'image_url' => asset($imagePath),
                'provider' => [
                    'id' => $provider->id,
                    'name' => $provider->name,
                    'img' => $imagePath,
                    'img_url' => asset($imagePath)
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Processa a imagem do provedor para converter para WebP mantendo transparência
     */
    private function processProviderImageToWebP($imageFile)
    {
        // Obter informações da imagem
        $imageInfo = getimagesize($imageFile->getPathname());
        $imageType = $imageInfo[2];

        // Criar a imagem baseada no tipo
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($imageFile->getPathname());
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($imageFile->getPathname());
                break;
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($imageFile->getPathname());
                break;
            case IMAGETYPE_WEBP:
                $image = imagecreatefromwebp($imageFile->getPathname());
                break;
            case IMAGETYPE_AVIF:
                // AVIF support (PHP 8.1+)
                if (function_exists('imagecreatefromavif')) {
                    $image = imagecreatefromavif($imageFile->getPathname());
                } else {
                    throw new \Exception('Suporte AVIF não disponível nesta versão do PHP');
                }
                break;
            default:
                throw new \Exception('Tipo de imagem não suportado');
        }

        if (!$image) {
            throw new \Exception('Falha ao processar a imagem');
        }

        // Obter dimensões da imagem
        $width = imagesx($image);
        $height = imagesy($image);

        // Criar uma nova imagem com suporte à transparência
        $newImage = imagecreatetruecolor($width, $height);
        
        // Habilitar transparência na nova imagem
        imagesavealpha($newImage, true);
        
        // Criar cor transparente
        $transparent = imagecolorallocatealpha($newImage, 0, 0, 0, 127);
        
        // Preencher com transparência
        imagefill($newImage, 0, 0, $transparent);
        
        // Para PNG, GIF, WEBP e AVIF, verificar se tem transparência
        if ($imageType === IMAGETYPE_PNG || $imageType === IMAGETYPE_GIF || $imageType === IMAGETYPE_WEBP || $imageType === IMAGETYPE_AVIF) {
            // Habilitar transparência na imagem original
            imagesavealpha($image, true);
        }

        // Copiar a imagem original para a nova imagem preservando transparência
        imagecopyresampled($newImage, $image, 0, 0, 0, 0, $width, $height, $width, $height);

        // Liberar memória da imagem original
        imagedestroy($image);

        // Retornar a imagem processada
        return $newImage;
    }

    /**
     * MÉTODO DEPRECIADO: Use updateGameField() ou updateSlugField()
     * 
     * Este método não deve ser usado para ativação/desativação de jogos.
     * Para ativar/desativar jogos específicos, use updateSlugField() que atualiza apenas o slug.
     * Para campos do jogo (show_home, destaque), use updateGameField().
     */
    public function atualizarJogo(Request $request)
    {
        $id = $request->input('id');
        $field = $request->input('field');
        $value = $request->input('value');

        // AVISO: Este método não deve ser usado para campo 'status' em jogos individuais
        if ($field === 'status' && $id != 0) {
            return response()->json([
                'success' => false,
                'message' => 'Para ativar/desativar jogos, use o switch específico do slug. Este método não altera o status de jogos individuais.'
            ]);
        }

        if ($id == 0 && $field == 'status') {
            // Atualizar status de todos os jogos (operação em massa)
            GamesApi::query()->update(['status' => $value]);
        } else {
            $game = GamesApi::findOrFail($id);
            $game->$field = $value;
            $game->save();
        }

        return response()->json(['success' => true]);
    }

    /**
     * Atualiza um campo específico do jogo (agora consolidado na games_api)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSlugField(Request $request)
    {
        try {
            $id = $request->input('id');
            $field = $request->input('field');
            $value = $request->input('value');

            // Validação dos dados
            if (!$id || !$field) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID do jogo e nome do campo são obrigatórios'
                ]);
            }

            // Permitir apenas campos específicos para atualização
            $allowedFields = ['status'];
            if (!in_array($field, $allowedFields)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Campo não permitido para atualização'
                ]);
            }

            // Buscar e atualizar o jogo na tabela consolidada games_api
            $game = GamesApi::findOrFail($id);
            $game->$field = $value;
            $game->save();

            return response()->json([
                'success' => true,
                'message' => 'Jogo atualizado com sucesso!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar campo do jogo: ' . $e->getMessage()
            ]);
        }
    }


    /**
     * Atualiza um campo específico do jogo
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateGameField(Request $request)
    {
        try {
            $id = $request->input('id');
            $field = $request->input('field');
            $value = $request->input('value');

            // Validação dos dados
            if (!$id || !$field) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID do jogo e nome do campo são obrigatórios'
                ]);
            }

            // Permitir apenas campos específicos para atualização (removido 'status' pois agora usamos updateSlugField)
            $allowedFields = ['show_home', 'destaque'];
            if (!in_array($field, $allowedFields)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Campo não permitido para atualização'
                ]);
            }

            // Buscar e atualizar o jogo
            $game = GamesApi::findOrFail($id);
            $game->$field = $value;
            $game->save();



            return response()->json([
                'success' => true
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar campo: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Atualiza múltiplos campos de um jogo (nome, slug, slug_playfiver)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateGameDetails(Request $request)
    {
        try {
            $id = $request->input('game_id');
            $name = $request->input('name');
            $slug = $request->input('slug', '');

            // Validação dos dados
            if (!$id || !$name) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID do jogo e nome são obrigatórios'
                ]);
            }

            // Buscar o jogo
            $game = GamesApi::findOrFail($id);

            // Atualizar o nome e slug diretamente na tabela consolidada games_api
            $game->name = $name;
            if (!empty($slug)) {
                $game->slug = $slug;
            }
            $game->save();

            return response()->json([
                'success' => true,
                'message' => 'Detalhes do jogo atualizados com sucesso!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar detalhes do jogo: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Busca os detalhes atualizados de um jogo específico
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGameDetails(Request $request)
    {
        try {
            $id = $request->input('game_id');

            // Validação dos dados
            if (!$id) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID do jogo é obrigatório'
                ]);
            }

            // Buscar o jogo com slug diretamente da tabela consolidada games_api
            $game = GamesApi::select('id', 'name', 'slug')->findOrFail($id);

            return response()->json([
                'success' => true,
                'game' => [
                    'id' => $game->id,
                    'name' => $game->name,
                    'slug' => $game->slug ?? ''
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar detalhes do jogo: ' . $e->getMessage()
            ]);
        }
    }

    public function jogosData(Request $request)
    {
        return DataTableService::criarDataTableJogos($request);
    }

    /**
     * Atualiza as configurações de visibilidade das seções da página inicial
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateHomeSectionSettings(Request $request)
    {
        try {
            $field = $request->input('field');
            $value = $request->input('value');

            // Validar o campo
            $validFields = [
                'show_live_casino', 'show_new_games', 'show_most_viewed_games', 
                'show_top_wins', 'show_last_bets', 'show_roulette', 
                'show_whatsapp_float', 'show_raspadinhas_home',
                'custom_title_live_casino', 'custom_title_new_games', 
                'custom_title_most_viewed_games', 'custom_title_top_wins',
                'custom_title_most_paid', 'custom_title_studios', 
                'custom_title_top_raspadinhas', 'custom_title_modo_surpresa',
                'custom_title_sports_icons', 'custom_title_last_bets'
            ];

            if (!in_array($field, $validFields)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Campo inválido.'
                ]);
            }

            // Obter as configurações atuais ou criar se não existirem
            $settings = \App\Models\HomeSectionsSettings::getSettings();

            // Atualizar o campo
            $settings->$field = $value;
            $settings->save();

            return response()->json([
                'success' => true,
                'message' => 'Configuração atualizada com sucesso.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar configuração: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Redefinir todos os títulos personalizados para os valores padrão
     */
    public function resetCustomTitles(Request $request)
    {
        try {
            $settings = \App\Models\HomeSectionsSettings::getSettings();
            
            // Lista de todos os campos de títulos personalizados
            $customTitleFields = [
                'custom_title_live_casino',
                'custom_title_new_games',
                'custom_title_most_viewed_games',
                'custom_title_top_wins',
                'custom_title_most_paid',
                'custom_title_studios',
                'custom_title_top_raspadinhas',
                'custom_title_modo_surpresa',
                'custom_title_sports_icons',
                'custom_title_last_bets'
            ];
            
            // Limpar todos os títulos personalizados
            foreach ($customTitleFields as $field) {
                $settings->$field = null;
            }
            
            $settings->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Títulos personalizados redefinidos com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao redefinir títulos: ' . $e->getMessage()
            ]);
        }
    }


    public function getGameImage(Request $request)
    {
        try {
            $gameId = $request->input('game_id');
            
            // Buscar o jogo no banco de dados
            $game = \App\Models\GamesApi::findOrFail($gameId);
            
            return response()->json([
                'success' => true,
                'game' => [
                    'id' => $game->id,
                    'name' => $game->name,
                    'image' => $game->image,
                    'image_url' => $game->image ? (strpos($game->image, 'http') === 0 ? $game->image : asset($game->image)) : null
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar dados do jogo'
            ], 500);
        }
    }

    public function updateGameImage(Request $request)
    {
        try {
            // Validar a requisição
            $validator = \Validator::make($request->all(), [
                'image' => 'required|file|mimes:jpeg,jpg,png,gif,webp,avif|max:5120', // 5MB máximo - usando 'file' em vez de 'image' para AVIF
                'game_id' => 'required|integer',
                'game_name' => 'required|string'
            ]);
            
            if ($validator->fails()) {
                // Verificação adicional para arquivos AVIF
                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $extension = strtolower($file->getClientOriginalExtension());
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'];
                    
                    if (in_array($extension, $allowedExtensions)) {
                        // Se a extensão é válida, prosseguir mesmo com erro de MIME
                        if ($extension === 'avif') {
                            // Continuar com o processamento
                        } else {
                            return response()->json([
                                'success' => false,
                                'message' => 'Erro de validação: ' . implode(', ', $validator->errors()->all())
                            ], 422);
                        }
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'Erro de validação: ' . implode(', ', $validator->errors()->all())
                        ], 422);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erro de validação: ' . implode(', ', $validator->errors()->all())
                    ], 422);
                }
            }

            $gameId = $request->input('game_id');
            $gameName = $request->input('game_name');
            $image = $request->file('image');

            // Buscar o jogo
            $game = \App\Models\GamesApi::findOrFail($gameId);

            // Verificar se o diretório existe, se não, criar
            $uploadDir = public_path('uploads/games');
            if (!file_exists($uploadDir)) {
                $created = mkdir($uploadDir, 0755, true);
                if (!$created) {
                    throw new \Exception('Falha ao criar diretório: ' . $uploadDir);
                }
            }
            
            // Verificar se o diretório é gravável
            if (!is_writable($uploadDir)) {
                throw new \Exception('Diretório não é gravável: ' . $uploadDir);
            }

            // Verificar se a imagem já é WebP
            $isWebP = strtolower($image->getClientOriginalExtension()) === 'webp';
            
            // Gerar nome único para a imagem
            $extension = $isWebP ? 'webp' : 'webp'; // Sempre salvar como WebP
            $imageName = 'game_' . $gameId . '_' . time() . '.' . $extension;
            $imagePath = 'uploads/games/' . $imageName;

            // Processar a imagem
            if ($isWebP) {
                // Se já é WebP, apenas mover o arquivo
                $moved = $image->move(public_path('uploads/games'), $imageName);
                if (!$moved) {
                    throw new \Exception('Falha ao mover arquivo WebP');
                }
            } else {
                // Converter para WebP com fundo transparente
                $processedImage = $this->processGameImageToWebP($image);
                
                // Verificar se a função imagewebp está disponível
                if (!function_exists('imagewebp')) {
                    throw new \Exception('Suporte WebP não disponível nesta versão do PHP');
                }
                
                // Salvar como WebP
                $saved = imagewebp($processedImage, public_path($imagePath), 90);
                if (!$saved) {
                    throw new \Exception('Falha ao salvar imagem WebP');
                }
                
                // Liberar memória
                imagedestroy($processedImage);
            }

            // Remover a imagem anterior se existir e for local
            if ($game->image && strpos($game->image, 'http') !== 0 && file_exists(public_path($game->image))) {
                unlink(public_path($game->image));
            }

            // Atualizar o caminho da imagem no banco de dados
            $game->image = $imagePath;
            $game->save();



            return response()->json([
                'success' => true,
                'message' => 'Imagem do jogo atualizada com sucesso!',
                'image_path' => asset($imagePath),
                'image_url' => asset($imagePath),
                'game' => [
                    'id' => $game->id,
                    'name' => $game->name,
                    'image' => $imagePath,
                    'image_url' => asset($imagePath)
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Processa a imagem do jogo para converter para WebP mantendo transparência
     */
    private function processGameImageToWebP($imageFile)
    {
        // Obter informações da imagem
        $imageInfo = getimagesize($imageFile->getPathname());
        
        // Verificar se getimagesize retornou dados válidos
        if ($imageInfo === false) {
            // Se getimagesize falhar, tentar determinar o tipo pela extensão
            $extension = strtolower($imageFile->getClientOriginalExtension());
            
            switch ($extension) {
                case 'avif':
                    if (function_exists('imagecreatefromavif')) {
                        $image = imagecreatefromavif($imageFile->getPathname());
                        $imageType = IMAGETYPE_AVIF;
                    } else {
                        throw new \Exception('Suporte AVIF não disponível nesta versão do PHP');
                    }
                    break;
                case 'webp':
                    $image = imagecreatefromwebp($imageFile->getPathname());
                    $imageType = IMAGETYPE_WEBP;
                    break;
                case 'png':
                    $image = imagecreatefrompng($imageFile->getPathname());
                    $imageType = IMAGETYPE_PNG;
                    break;
                case 'jpg':
                case 'jpeg':
                    $image = imagecreatefromjpeg($imageFile->getPathname());
                    $imageType = IMAGETYPE_JPEG;
                    break;
                case 'gif':
                    $image = imagecreatefromgif($imageFile->getPathname());
                    $imageType = IMAGETYPE_GIF;
                    break;
                default:
                    throw new \Exception('Tipo de imagem não suportado ou arquivo corrompido');
            }
        } else {
            // Se getimagesize funcionou, usar o tipo detectado
            $imageType = $imageInfo[2];
            
            // Criar a imagem baseada no tipo
            switch ($imageType) {
                case IMAGETYPE_JPEG:
                    $image = imagecreatefromjpeg($imageFile->getPathname());
                    break;
                case IMAGETYPE_PNG:
                    $image = imagecreatefrompng($imageFile->getPathname());
                    break;
                case IMAGETYPE_GIF:
                    $image = imagecreatefromgif($imageFile->getPathname());
                    break;
                case IMAGETYPE_WEBP:
                    $image = imagecreatefromwebp($imageFile->getPathname());
                    break;
                case IMAGETYPE_AVIF:
                    // AVIF support (PHP 8.1+)
                    if (function_exists('imagecreatefromavif')) {
                        $image = imagecreatefromavif($imageFile->getPathname());
                    } else {
                        throw new \Exception('Suporte AVIF não disponível nesta versão do PHP');
                    }
                    break;
                default:
                    throw new \Exception('Tipo de imagem não suportado');
            }
        }

        if (!$image) {
            throw new \Exception('Falha ao processar a imagem. Verifique se o arquivo está corrompido ou se o formato é suportado.');
        }

        // Obter dimensões da imagem
        $width = imagesx($image);
        $height = imagesy($image);

        // Criar uma nova imagem com suporte à transparência
        $newImage = imagecreatetruecolor($width, $height);
        
        // Habilitar transparência na nova imagem
        imagesavealpha($newImage, true);
        
        // Criar cor transparente
        $transparent = imagecolorallocatealpha($newImage, 0, 0, 0, 127);
        
        // Preencher com transparência
        imagefill($newImage, 0, 0, $transparent);
        
        // Para PNG, GIF, WEBP e AVIF, verificar se tem transparência
        if ($imageType === IMAGETYPE_PNG || $imageType === IMAGETYPE_GIF || $imageType === IMAGETYPE_WEBP || $imageType === IMAGETYPE_AVIF) {
            // Habilitar transparência na imagem original
            imagesavealpha($image, true);
        }

        // Copiar a imagem original para a nova imagem preservando transparência
        imagecopyresampled($newImage, $image, 0, 0, 0, 0, $width, $height, $width, $height);

        // Liberar memória da imagem original
        imagedestroy($image);

        // Retornar a imagem processada
        return $newImage;
    }

    public function getProviders()
    {
        // Código existente...
    }

    /**
     * Retorna a lista de provedores filtrados por status
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProvidersByStatus(Request $request)
    {
        try {
            $status = $request->input('status', 'all');

            // Buscar todos os provedores que têm jogos, independente do status se for 'all'
            if ($status === 'all') {
                // Buscar todos os provedores que têm jogos ativos
                $providers = Providers::select('providers.name')
                    ->whereExists(function($query) {
                        $query->select(DB::raw(1))
                              ->from('games_api')
                              ->whereColumn('games_api.provider_id', 'providers.id')
                              ->where('games_api.status', 1);
                    })
                    ->distinct()
                    ->orderBy('providers.name')
                    ->pluck('providers.name')
                    ->toArray();
            } else {
                // Buscar apenas provedores que têm jogos com o status específico
                $providers = Providers::select('providers.name')
                    ->whereExists(function($query) use ($status) {
                        $query->select(DB::raw(1))
                              ->from('games_api')
                              ->whereColumn('games_api.provider_id', 'providers.id')
                              ->where('games_api.status', $status);
                    })
                    ->distinct()
                    ->orderBy('providers.name')
                    ->pluck('providers.name')
                    ->toArray();
            }

            return response()->json([
                'success' => true,
                'providers' => $providers
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar provedores: ' . $e->getMessage(),
                'providers' => []
            ]);
        }
    }

    /**
     * Buscar provedores por distribuição
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProvidersByDistribution(Request $request)
    {
        try {
            $distribution = $request->input('distribution', 'all');

            // Buscar todos os provedores que têm jogos, independente da distribuição se for 'all'
            if ($distribution === 'all') {
                // Buscar todos os provedores que têm jogos ativos
                $providers = Providers::select('providers.name')
                    ->whereExists(function($query) {
                        $query->select(DB::raw(1))
                              ->from('games_api')
                              ->whereColumn('games_api.provider_id', 'providers.id')
                              ->where('games_api.status', 1);
                    })
                    ->distinct()
                    ->orderBy('providers.name')
                    ->pluck('providers.name')
                    ->toArray();
            } else {
                // Buscar apenas provedores que têm jogos com a distribuição específica
                $providers = Providers::select('providers.name')
                    ->whereExists(function($query) use ($distribution) {
                        $query->select(DB::raw(1))
                              ->from('games_api')
                              ->whereColumn('games_api.provider_id', 'providers.id')
                              ->where('games_api.distribution', $distribution)
                              ->where('games_api.status', 1);
                    })
                    ->distinct()
                    ->orderBy('providers.name')
                    ->pluck('providers.name')
                    ->toArray();
            }

            return response()->json([
                'success' => true,
                'providers' => $providers
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar provedores por distribuição: ' . $e->getMessage(),
                'providers' => []
            ]);
        }
    }

    public function getProvidersByWallet(Request $request)
    {
        try {
            $wallet = $request->input('wallet');
            $distribution = $request->input('distribution', 'all');
            $status = $request->input('status', 'all');

            // Construir query base - wallets (plural) está na tabela providers
            $query = Providers::select('providers.name')
                ->where('providers.wallets', $wallet)
                ->whereExists(function($subQuery) use ($distribution, $status) {
                    $subQuery->select(DB::raw(1))
                        ->from('games_api')
                        ->whereColumn('games_api.provider_id', 'providers.id');
                    
                    // Adicionar filtro de distribuição se não for 'all'
                    if ($distribution !== 'all') {
                        $subQuery->where('games_api.distribution', $distribution);
                    }
                    
                    // Adicionar filtro de status se não for 'all'
                    if ($status !== 'all') {
                        $subQuery->where('games_api.status', $status);
                    }
                })
                ->distinct()
                ->orderBy('providers.name');

            $providers = $query->pluck('providers.name')->toArray();

            return response()->json([
                'success' => true,
                'providers' => $providers,
                'wallet' => $wallet,
                'distribution' => $distribution,
                'status' => $status,
                'count' => count($providers)
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro em getProvidersByWallet: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar provedores por carteira: ' . $e->getMessage(),
                'providers' => []
            ]);
        }
    }

    /**
     * Verificar se há provedores com wallets baseado nos filtros
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkWalletsAvailability(Request $request)
    {
        try {
            $distribution = $request->input('distribution', 'all');
            $provider = $request->input('provider', 'all');
            $status = $request->input('status', 'all'); // Adicionar parâmetro de status

            $query = Providers::select('providers.id', 'providers.name', 'providers.wallets')
                ->whereExists(function($subquery) use ($distribution, $status) {
                    $subquery->select(DB::raw(1))
                          ->from('games_api')
                          ->whereColumn('games_api.provider_id', 'providers.id');
                    
                    // Aplicar filtro de status nos jogos apenas se especificado
                    if ($status !== 'all') {
                        $subquery->where('games_api.status', $status);
                    }
                    
                    if ($distribution !== 'all') {
                        $subquery->where('games_api.distribution', $distribution);
                    }
                })
                ->whereNotNull('providers.wallets')
                ->where('providers.wallets', '!=', '')
                ->where('providers.wallets', '!=', '[]');

            if ($provider !== 'all') {
                $query->where('providers.name', $provider);
            }

            $providers = $query->get();
            $hasWallets = $providers->count() > 0;
            
            // Se há provedores com wallets, buscar todas as wallets disponíveis
            $wallets = [];
            if ($hasWallets) {
                foreach ($providers as $providerData) {
                    $walletJson = $providerData->wallets;
                    
                    if ($walletJson) {
                        // Tentar diferentes formatos de dados
                        $decodedWallets = null;
                        
                        // Primeiro, tentar como JSON
                        if (is_string($walletJson)) {
                            $decodedWallets = json_decode($walletJson, true);
                        }
                        
                        // Se não funcionou, talvez já seja um array
                        if (!is_array($decodedWallets) && is_array($walletJson)) {
                            $decodedWallets = $walletJson;
                        }
                        
                        // Se ainda não funcionou, talvez seja uma string simples com vírgulas
                        if (!is_array($decodedWallets) && is_string($walletJson)) {
                            if (strpos($walletJson, ',') !== false) {
                                $decodedWallets = explode(',', $walletJson);
                                $decodedWallets = array_map('trim', $decodedWallets);
                            } else if (!empty(trim($walletJson)) && $walletJson !== '[]') {
                                $decodedWallets = [trim($walletJson)];
                            }
                        }
                        
                        if (is_array($decodedWallets) && !empty($decodedWallets)) {
                            // Filtrar valores vazios
                            $validWallets = array_filter($decodedWallets, function($wallet) {
                                return !empty(trim($wallet));
                            });
                            $wallets = array_merge($wallets, $validWallets);
                        }
                    }
                }
                $wallets = array_unique($wallets);
                sort($wallets);
            }

            return response()->json([
                'success' => true,
                'hasWallets' => $hasWallets,
                'wallets' => $wallets
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao verificar disponibilidade de wallets: ' . $e->getMessage(),
                'hasWallets' => false,
                'wallets' => []
            ]);
        }
    }

    /**
     * Listar todos os campos personalizados
     */
    public function getCustomFields()
    {
        try {
            $fields = HomeCustomField::orderBy('position')->get();
            return response()->json([
                'success' => true,
                'fields' => $fields
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar campos: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Criar um novo campo personalizado
     */
    public function createCustomField(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
            ]);

            $maxPosition = HomeCustomField::max('position') ?? 0;

            $field = HomeCustomField::create([
                'title' => $request->title,
                'is_active' => true,
                'position' => $maxPosition + 1,
            ]);

            // Criar entrada na tabela home_sections_order para controlar a ordem
            $maxSectionPosition = \App\Models\HomeSectionOrder::max('position') ?? 0;
            \App\Models\HomeSectionOrder::create([
                'section_key' => 'custom_field_' . $field->id,
                'section_name' => $field->title,
                'position' => $maxSectionPosition + 1,
                'is_active' => true,
            ]);

            HomeCustomField::clearCache();
            \App\Models\HomeSectionOrder::clearCache();

            return response()->json([
                'success' => true,
                'message' => 'Campo personalizado criado com sucesso!',
                'field' => $field
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar campo: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Atualizar um campo personalizado
     */
    public function updateCustomField(Request $request, $id)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
            ]);
            
            // Validar is_active separadamente para aceitar boolean ou string
            if ($request->has('is_active')) {
                $isActive = $request->is_active;
                if (!is_bool($isActive) && !in_array($isActive, ['true', 'false', '1', '0', 1, 0, 'yes', 'no', 'on', 'off'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'O campo is_active deve ser true ou false.'
                    ], 422);
                }
            }

            $field = HomeCustomField::findOrFail($id);
            $field->title = $request->title;
            
            // Converter is_active para boolean corretamente
            if ($request->has('is_active')) {
                $isActive = $request->is_active;
                if (is_string($isActive)) {
                    $isActive = in_array(strtolower($isActive), ['true', '1', 'yes', 'on']);
                }
                $field->is_active = (bool) $isActive;
            }
            $field->save();

            // Atualizar nome na tabela home_sections_order se existir
            $sectionOrder = \App\Models\HomeSectionOrder::where('section_key', 'custom_field_' . $id)->first();
            if ($sectionOrder) {
                $sectionOrder->section_name = $field->title;
                if ($request->has('is_active')) {
                    $isActive = $request->is_active;
                    if (is_string($isActive)) {
                        $isActive = in_array(strtolower($isActive), ['true', '1', 'yes', 'on']);
                    }
                    $sectionOrder->is_active = (bool) $isActive;
                }
                $sectionOrder->save();
            }

            HomeCustomField::clearCache();
            \App\Models\HomeSectionOrder::clearCache();

            return response()->json([
                'success' => true,
                'message' => 'Campo atualizado com sucesso!',
                'field' => $field
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar campo: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Deletar um campo personalizado
     */
    public function deleteCustomField($id)
    {
        try {
            $field = HomeCustomField::findOrFail($id);
            
            // Remover da tabela home_sections_order
            \App\Models\HomeSectionOrder::where('section_key', 'custom_field_' . $id)->delete();
            
            // Deletar jogos associados
            HomeCustomFieldGame::where('custom_field_id', $id)->delete();
            
            // Deletar o campo
            $field->delete();

            HomeCustomField::clearCache();
            \App\Models\HomeSectionOrder::clearCache();

            return response()->json([
                'success' => true,
                'message' => 'Campo deletado com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar campo: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Sincronizar custom fields existentes com home_sections_order
     */
    public function syncCustomFieldsToSections()
    {
        try {
            $customFields = HomeCustomField::where('is_active', true)->get();
            $maxSectionPosition = \App\Models\HomeSectionOrder::max('position') ?? 0;
            
            foreach ($customFields as $index => $field) {
                $sectionKey = 'custom_field_' . $field->id;
                $existing = \App\Models\HomeSectionOrder::where('section_key', $sectionKey)->first();
                
                if (!$existing) {
                    \App\Models\HomeSectionOrder::create([
                        'section_key' => $sectionKey,
                        'section_name' => $field->title,
                        'position' => $maxSectionPosition + $index + 1,
                        'is_active' => $field->is_active,
                    ]);
                }
            }
            
            \App\Models\HomeSectionOrder::clearCache();
            
            return response()->json([
                'success' => true,
                'message' => 'Custom fields sincronizados com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao sincronizar: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Buscar jogos para seleção
     */
    public function getGamesForSelection(Request $request)
    {
        try {
            $search = $request->input('search', '');
            $limit = $request->input('limit', 50);

            $query = DB::table('games_api')
                ->join('providers', 'games_api.provider_id', '=', 'providers.id')
                ->where('games_api.status', 1)
                ->select(
                    'games_api.id',
                    'games_api.name',
                    'games_api.image',
                    'providers.name as provider_name'
                )
                ->groupBy('games_api.id', 'games_api.name', 'games_api.image', 'providers.name');

            if ($search) {
                $query->where('games_api.name', 'like', "%{$search}%");
            }

            $games = $query->limit($limit)->get();

            // Aplicar função completeGameImageUrl se existir
            if (function_exists('completeGameImageUrl')) {
                $games = completeGameImageUrl($games);
            } else {
                // Fallback se a função não existir
                $games = $games->map(function ($game) {
                    if (isset($game->image) && !empty($game->image)) {
                        if (strpos($game->image, 'http') === 0) {
                            $game->image_url = $game->image;
                        } else {
                            $game->image_url = asset($game->image);
                        }
                    } else {
                        $game->image_url = null;
                    }
                    return $game;
                });
            }

            return response()->json([
                'success' => true,
                'games' => $games
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar jogos: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Adicionar jogos a um campo personalizado
     */
    public function addGamesToCustomField(Request $request, $id)
    {
        try {
            $request->validate([
                'game_ids' => 'required|array',
                'game_ids.*' => 'required|integer|exists:games_api,id',
            ]);

            $field = HomeCustomField::findOrFail($id);

            // Remover jogos duplicados
            $existingGameIds = HomeCustomFieldGame::where('custom_field_id', $id)
                ->pluck('game_id')
                ->toArray();

            $newGameIds = array_diff($request->game_ids, $existingGameIds);

            if (empty($newGameIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Todos os jogos selecionados já estão adicionados a este campo.'
                ]);
            }

            $maxPosition = HomeCustomFieldGame::where('custom_field_id', $id)
                ->max('position') ?? 0;

            $gamesToAdd = [];
            foreach ($newGameIds as $index => $gameId) {
                $gamesToAdd[] = [
                    'custom_field_id' => $id,
                    'game_id' => $gameId,
                    'position' => $maxPosition + $index + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            HomeCustomFieldGame::insert($gamesToAdd);

            HomeCustomField::clearCache();

            return response()->json([
                'success' => true,
                'message' => count($newGameIds) . ' jogo(s) adicionado(s) com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao adicionar jogos: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Remover jogo de um campo personalizado
     */
    public function removeGameFromCustomField($fieldId, $gameId)
    {
        try {
            HomeCustomFieldGame::where('custom_field_id', $fieldId)
                ->where('game_id', $gameId)
                ->delete();

            HomeCustomField::clearCache();

            return response()->json([
                'success' => true,
                'message' => 'Jogo removido com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao remover jogo: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obter jogos de um campo personalizado
     */
    public function getCustomFieldGames($id)
    {
        try {
            $field = HomeCustomField::findOrFail($id);
            $games = $field->getGamesWithDetails();

            return response()->json([
                'success' => true,
                'games' => $games
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar jogos: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Atualizar ordem dos jogos em um campo personalizado
     */
    public function updateCustomFieldGamesOrder(Request $request, $id)
    {
        try {
            $request->validate([
                'game_ids' => 'required|array',
                'game_ids.*' => 'required|integer',
            ]);

            foreach ($request->game_ids as $position => $gameId) {
                HomeCustomFieldGame::where('custom_field_id', $id)
                    ->where('game_id', $gameId)
                    ->update(['position' => $position + 1]);
            }

            HomeCustomField::clearCache();

            return response()->json([
                'success' => true,
                'message' => 'Ordem atualizada com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar ordem: ' . $e->getMessage()
            ]);
        }
    }
}
