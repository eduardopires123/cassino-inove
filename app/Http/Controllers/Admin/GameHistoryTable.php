<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GameHistory;
use App\Models\GamesApi;
use App\Models\User;
use App\Models\Transactions;
use App\Models\SportBetSummary;
use App\Models\AffiliatesHistory;
use App\Models\CouponRedemption;
use App\Models\MissionCompletion;
use App\Models\VipReward;

use Illuminate\Http\Request;
use Carbon\Carbon;

use Yajra\DataTables\DataTables;
use Illuminate\Support\Collection;

class GameHistoryTable extends Controller
{
    public function index4(Request $request)
    {
        if ($request->ajax()) {
            $data = GamesApi::with(['provider', 'activeSlugs'])
                ->select('id', 'image', 'provider_id', 'name', 'show_home', 'destaque', 'views', 'status', 'created_at')
                ->orderBy('id', 'desc')
                ->get();

            return DataTables::of($data)
                ->addColumn('capa', function ($row) {
                    return '<div class="usr-img-frame me-2"><img alt="avatar" class="img-fluid" src="'.$row->image.'"></div>';
                })
                ->addColumn('provedor', function ($row) {
                    $providerName = $row->provider ? $row->provider->name : 'N/A';
                    return '<span class="badge badge-light-info mb-2 me-4 provider-badge"
                                                    data-game-id="'.$row->id.'"
                                                    data-game-name="'.$row->name.'"
                                                    data-provider="'.$providerName.'"
                                                    style="cursor:pointer">
                                                    '.$providerName.'
                                                </span>';
                })
                ->addColumn('nome', function ($row) {
                    return $row->name;
                })
                ->addColumn('distribuicao', function ($row) {
                    $slugs = $row->activeSlugs->pluck('distribution')->unique()->implode(', ');
                    return $slugs ?: 'N/A';
                })
                ->addColumn('exibir_home', function ($row) {
                    return '<div class="form-check form-switch"><input class="form-check-input" type="checkbox" role="switch" id="show_home'.$row->id.'" ' . ($row->show_home == 1 ? "checked" : "") . ' onchange="confirmAndUpdateGameField(\''.$row->id.'\', \'show_home\', this.checked ? 1 : 0, this)"></div>';
                })
                ->addColumn('destaque', function ($row) {
                    return '<div class="form-check form-switch"><input class="form-check-input" type="checkbox" role="switch" id="destaque'.$row->id.'" ' . ($row->destaque == 1 ? "checked" : "") . ' onchange="confirmAndUpdateGameField(\''.$row->id.'\', \'destaque\', this.checked ? 1 : 0, this)"></div>';
                })
                ->addColumn('views', function ($row) {
                    return $row->views;
                })
                ->addColumn('ativo', function ($row) {
                    return '<div class="form-check form-switch"><input class="form-check-input" type="checkbox" role="switch" id="status'.$row->id.'" ' . ($row->status == 1 ? "checked" : "") . ' onchange="confirmAndUpdateGameField(\''.$row->id.'\', \'status\', this.checked ? 1 : 0, this)"></div>';
                })
                ->rawColumns(['capa', 'distribuicao', 'provedor', 'exibir_home', 'destaque', 'ativo'])
                ->make(true);
        }

        return 1;
    }



    public function index(Request $request)
    {
        if ($request->ajax()) {
            $a = $request->input('a');
            $b = $request->input('b');
            $c = $request->input('c');

            $data = GameHistory::with('user')->select('game', 'user_id', 'amount', 'action', 'games_history.updated_at')->where('amount', '>', 0)->OrderBy('games_history.updated_at', 'desc');

            if ($a != "") {
                /*$Jogo = GamesApi::where('name', $a)->first();

                if ($Jogo) {
                    $parte = explode('/', $Jogo->slug);
                    $data->where('game', $parte[2]);
                }*/

                $User = User::where('name', $a)->first();

                if ($User) {
                    $data->where('user_id', $User->id);
                }
            }

            if (isset($b)) {$bb = Carbon::parse($b)->startOfDay();}else{$bb = "";}
            if (isset($c)) {$cc = Carbon::parse($c)->endOfDay();}else{$cc = "";}

            if ($b && $c) {
                $data->whereBetween('games_history.updated_at', [$bb, $cc]);
            } elseif ($b && !$c) {
                $data->whereBetween('games_history.updated_at', [$bb, Carbon::now()]);
            }

            return DataTables::of($data)
                ->addColumn('user_name', function ($row) {
                    return "<a href=\"javascript:void(0);\" onclick=\"LoadAgent('".$row->User->id."');\" data-bs-toggle=\"modal\" data-bs-target=\"#tabsModal\" class=\"bs-tooltip\" title=\"Visualizar Usuário\" data-original-title=\"Visualizar Usuário\">".$row->user->name."</a>";
                })
                ->addColumn('game_name', function ($row) {
                    $gameName = GamesApi::where('slug', 'LIKE', '%' . $row->game . '%')->first();
                    return ($gameName->name ?? 'N/A');
                })
                ->addColumn('action', function ($row) {
                    return ($row->action == 'loss' ? "<span class=\"badge badge-light-danger mb-2 me-4\">Loss</span>" : "<span class=\"badge badge-light-success mb-2 me-4\">Win</span>");
                })
                ->editColumn('amount', function ($row) {
                    return 'R$ ' . number_format((float)$row->amount, 2, ',', '.');
                })
                ->editColumn('updated_at', function ($row) {
                    return $row->updated_at->format('d/m/Y H:i:s');
                })
                ->rawColumns(['user_name', 'action'])
                ->make(true);
        }

        return 1;
    }

    public function index2(Request $request)
    {
        if ($request->ajax()) {
            if ($request->input('tab') == 'transacoes') {
                $a = $request->input('a');
                $b = $request->input('b');
                $c = $request->input('c');
                $d = $request->input('d');

                $data = Transactions::with('user')->select('type', 'user_id', 'amount', 'gateway', 'status', 'transactions.updated_at')->where('user_id', $d)->OrderBy('transactions.updated_at', 'desc');

                if ($a != "") {
                    if ($a == "deposito") {
                        $data->where('type', 0);
                    } elseif ($a == "saque") {
                        $data->where('type', 1);
                    } elseif ($a == "bonus") {
                        $data->where('type', 2);
                    }
                }

                if (isset($b)) {
                    $bb = Carbon::parse($b)->startOfDay();
                } else {
                    $bb = "";
                }
                if (isset($c)) {
                    $cc = Carbon::parse($c)->endOfDay();
                } else {
                    $cc = "";
                }

                if ($b && $c) {
                    if (!($a == "manual") && !($a == "cupom") && !($a == "missao") && !($a == "vip")) {
                        $data->whereBetween('transactions.updated_at', [$bb, $cc]);
                    }
                } elseif ($b && !$c) {
                    if (!($a == "manual") && !($a == "cupom") && !($a == "missao") && !($a == "vip")) {
                        $data->whereBetween('transactions.updated_at', [$bb, Carbon::now()]);
                    }
                }

                // Buscar adições manuais de saldo da tabela logs
                $logsData = \App\Models\Admin\Logs::where('user_id', $d)
                    ->whereIn('field_name', ['Adição de Saldo', 'Remoção de Saldo'])
                    ->select(
                        \DB::raw("'manual' as type"),
                        'user_id',
                        \DB::raw("CASE 
                            WHEN old_value IS NOT NULL AND new_value IS NOT NULL 
                            THEN CAST(REPLACE(REPLACE(REPLACE(new_value, '.', ''), ',', '.'), ' ', '') AS DECIMAL(10,2)) - CAST(REPLACE(REPLACE(REPLACE(old_value, '.', ''), ',', '.'), ' ', '') AS DECIMAL(10,2))
                            ELSE 0 
                        END as amount"),
                        \DB::raw("CASE 
                            WHEN old_value IS NOT NULL AND new_value IS NOT NULL 
                            THEN CASE 
                                WHEN CAST(REPLACE(REPLACE(REPLACE(new_value, '.', ''), ',', '.'), ' ', '') AS DECIMAL(10,2)) - CAST(REPLACE(REPLACE(REPLACE(old_value, '.', ''), ',', '.'), ' ', '') AS DECIMAL(10,2)) >= 0 
                                THEN 'Adição Manual' 
                                ELSE 'Remoção Manual' 
                            END
                            ELSE 'Adição Manual'
                        END as gateway"),
                        \DB::raw("1 as status"),
                        'created_at as updated_at',
                        'field_name'
                    );

                // Aplicar filtro de data aos logs, se necessário
                if ($b && $c) {
                    $logsData->whereBetween('created_at', [$bb, $cc]);
                } elseif ($b && !$c) {
                    $logsData->whereBetween('created_at', [$bb, Carbon::now()]);
                }

                // Buscar resgates de cupom
                $couponData = \App\Models\CouponRedemption::with('coupon')->where('user_id', $d)
                    ->select(
                        \DB::raw("'cupom' as type"),
                        'user_id',
                        'amount',
                        \DB::raw("CONCAT('Cupom: ', (SELECT code FROM coupons WHERE coupons.id = coupon_redemptions.coupon_id LIMIT 1)) as gateway"),
                        \DB::raw("1 as status"),
                        'redeemed_at as updated_at'
                    );

                // Aplicar filtro de data aos resgates de cupom, se necessário
                if ($b && $c) {
                    $couponData->whereBetween('redeemed_at', [$bb, $cc]);
                } elseif ($b && !$c) {
                    $couponData->whereBetween('redeemed_at', [$bb, Carbon::now()]);
                }

                // Buscar resgates de missões
                $missionData = \App\Models\MissionCompletion::with('mission')->where('user_id', $d)
                    ->where('reward_claimed', true)
                    ->select(
                        \DB::raw("'missao' as type"),
                        'user_id',
                        \DB::raw("COALESCE((SELECT reward_balance + reward_balance_bonus + reward_koins FROM missions WHERE missions.id = mission_completions.mission_id), 0) as amount"),
                        \DB::raw("CONCAT('Missão: ', (SELECT title FROM missions WHERE missions.id = mission_completions.mission_id LIMIT 1)) as gateway"),
                        \DB::raw("1 as status"),
                        'claimed_at as updated_at'
                    );

                // Aplicar filtro de data aos resgates de missão, se necessário
                if ($b && $c) {
                    $missionData->whereBetween('claimed_at', [$bb, $cc]);
                } elseif ($b && !$c) {
                    $missionData->whereBetween('claimed_at', [$bb, Carbon::now()]);
                }

                // Buscar resgates de VIP levels
                $vipData = \App\Models\VipReward::with('vipLevel')->where('user_id', $d)
                    ->where('is_claimed', true)
                    ->select(
                        \DB::raw("'vip' as type"),
                        'user_id',
                        \DB::raw("COALESCE(balance_rewarded + balance_bonus_rewarded, 0) as amount"),
                        \DB::raw("CONCAT('VIP Level: ', (SELECT name FROM vip_levels WHERE vip_levels.id = vip_rewards.vip_level_id LIMIT 1)) as gateway"),
                        \DB::raw("1 as status"),
                        'claimed_at as updated_at'
                    );

                // Aplicar filtro de data aos resgates de VIP, se necessário
                if ($b && $c) {
                    $vipData->whereBetween('claimed_at', [$bb, $cc]);
                } elseif ($b && !$c) {
                    $vipData->whereBetween('claimed_at', [$bb, Carbon::now()]);
                }

                // Obter dados conforme o filtro
                if ($a == "manual") {
                    // Se o filtro for "manual", mostrar apenas adições manuais
                    $combinedData = $logsData->get()->sortByDesc('updated_at');
                } else if ($a == "cupom") {
                    // Se o filtro for "cupom", mostrar apenas resgates de cupom
                    $combinedData = $couponData->get()->sortByDesc('updated_at');
                } else if ($a == "missao") {
                    // Se o filtro for "missao", mostrar apenas resgates de missão
                    $combinedData = $missionData->get()->sortByDesc('updated_at');
                } else if ($a == "vip") {
                    // Se o filtro for "vip", mostrar apenas resgates de VIP
                    $combinedData = $vipData->get()->sortByDesc('updated_at');
                } else if ($a == "deposito" || $a == "saque" || $a == "bonus") {
                    // Para filtros específicos de transações, mostrar apenas as transações filtradas
                    $combinedData = $data->get();
                } else {
                    $dataCollection = $data instanceof \Illuminate\Support\Collection ? $data : $data->get();
                    $logsCollection = $logsData->get();
                    $couponCollection = $couponData->get();
                    $missionCollection = $missionData->get();
                    $vipCollection = $vipData->get();

                    $combinedData = $dataCollection->concat($logsCollection)->concat($couponCollection)->concat($missionCollection)->concat($vipCollection)->sortByDesc('updated_at')->values();
                }

                return DataTables::of($combinedData)
                    ->addColumn('type', function ($row) {
                        if ($row->type === 'manual') {
                            // Verificar se é adição ou remoção baseado no valor
                            if (isset($row->amount) && $row->amount < 0) {
                                return "Remoção Manual";
                            } else {
                                return "Adição Manual";
                            }
                        }
                        if ($row->type === 'cupom') {
                            return "Resgate de Cupom";
                        }
                        if ($row->type === 'missao') {
                            return "Resgate de Missão";
                        }
                        if ($row->type === 'vip') {
                            return "Resgate VIP Level";
                        }
                        return ($row->type == 0) ? "Depósito" : (($row->type == 1) ? "Saque" : "Saldo Bônus");
                    })
                    ->addColumn('amount', function ($row) {
                        // Para transações manuais, mostrar sempre o valor absoluto
                        if ($row->type === 'manual') {
                            return 'R$ ' . number_format(abs((float)$row->amount), 2, ',', '.');
                        }
                        return 'R$ ' . number_format((float)$row->amount, 2, ',', '.');
                    })
                    ->addColumn('gateway', function ($row) {
                        if ($row->type === 'manual') {
                            // Usar o gateway calculado na query ou verificar o valor
                            if (isset($row->gateway)) {
                                return $row->gateway;
                            } else if (isset($row->amount) && $row->amount < 0) {
                                return "Remoção Manual";
                            } else {
                                return "Adição Manual";
                            }
                        }
                        if ($row->type === 'cupom') {
                            return $row->gateway;
                        }
                        if ($row->type === 'missao') {
                            return $row->gateway;
                        }
                        if ($row->type === 'vip') {
                            return $row->gateway;
                        }
                        return ($row->type == 2) ? "" : $row->gateway;
                    })
                    ->addColumn('status', function ($row) {
                        if ($row->type === 'manual') {
                            return "<span class='badge badge-light-primary mb-2 me-4'>Processado</span>";
                        }
                        if ($row->type === 'cupom') {
                            return "<span class='badge badge-light-success mb-2 me-4'>Resgatado</span>";
                        }
                        if ($row->type === 'missao') {
                            return "<span class='badge badge-light-info mb-2 me-4'>Missão Concluída</span>";
                        }
                        if ($row->type === 'vip') {
                            return "<span class='badge badge-light-warning mb-2 me-4'>VIP Resgatado</span>";
                        }

                        $badge = '';
                        if ($row->status == 0) {
                            $badge = "<span class='badge badge-light-warning mb-2 me-4'>Pendente</span>";
                        } elseif ($row->status == 1) {
                            $badge = "<span class='badge badge-light-success mb-2 me-4'>Concluído</span>";
                        } elseif ($row->status == 2) {
                            $badge = "<span class='badge badge-light-danger mb-2 me-4'>Cancelado</span>";
                        } else {
                            $badge = "<span class='badge badge-light-dark mb-2 me-4'>Desconhecido</span>";
                        }
                        return $badge;
                    })
                    ->editColumn('updated_at', function ($row) {
                        return Carbon::parse($row->updated_at)->format('d/m/Y H:i:s');
                    })
                    ->rawColumns(['user_name', 'status'])
                    ->make(true);
            } else if ($request->input('tab') == 'historico_jogos') {
                $a = $request->input('a');
                $b = $request->input('b');
                $c = $request->input('c');
                $d = $request->input('d');

                $data = GameHistory::with('user')->select('id', 'game', 'user_id', 'amount', 'action', 'games_history.created_at', 'json')->where('user_id', $d)->where('amount', '>', 0);

                $cassino = true;
                $sports = true;

                $sportsGames = null;
                $games = null;

                if ($a != "") {
                    if ($a == "casino") {
                        $sports = false;

                    }
                    if ($a == "sports") {
                        $cassino = false;
                    }
                }

                if ($cassino){
                    if (isset($b)) {$bb = Carbon::parse($b)->startOfDay();}else{$bb = "";}
                    if (isset($c)) {$cc = Carbon::parse($c)->endOfDay();}else{$cc = "";}

                    if ($b && $c) {
                        $data->whereBetween('games_history.updated_at', [$bb, $cc]);
                    } elseif ($b && !$c) {
                        $data->whereBetween('games_history.updated_at', [$bb, Carbon::now()]);
                    }

                    $games = $data->orderBy('id', 'desc')->get()->map(function ($row) {
                        return [
                            'type' => 'casino',
                            'game' => $row->game,
                            'user_id' => $row->user_id,
                            'amount' => $row->amount,
                            'action' => $row->action,
                            'json' => $row->json ?? "",
                            'updated_at' => $row->created_at,
                        ];
                    });
                }

                if ($sports){
                    $sportsQuery = \App\Models\SportBetSummary::where('user_id', $d)->where('amount', '>', 0)
                        ->select('id', 'user_id', 'transactionId', 'operation', 'amount', 'amount_win', 'status', 'provider', 'betslip', 'created_at');

                    if (isset($b)) {$bb = Carbon::parse($b)->startOfDay();}else{$bb = "";}
                    if (isset($c)) {$cc = Carbon::parse($c)->endOfDay();}else{$cc = "";}

                    if ($b && $c) {
                        $sportsQuery->whereBetween('created_at', [$bb, $cc]);
                    } elseif ($b && !$c) {
                        $sportsQuery->whereBetween('created_at', [$bb, Carbon::now()]);
                    }

                    $sportsGames = $sportsQuery
                        ->orderBy('created_at', 'desc')
                        ->limit(15)
                        ->get()
                        ->map(function ($row) {
                            // Para Betby, usar competitor_name do betslip
                            $gameName = 'Aposta Esportiva (' . $row->transactionId . ')';
                            
                            if ($row->provider === 'betby' && $row->betslip) {
                                try {
                                    $betslipData = json_decode($row->betslip, true);
                                    
                                    // Tentar diferentes estruturas de JSON da Betby
                                    $competitors = null;
                                    
                                    // Estrutura 1: betslip.bets[0].competitor_name
                                    if (isset($betslipData['betslip']['bets']) && is_array($betslipData['betslip']['bets'])) {
                                        $bets = $betslipData['betslip']['bets'];
                                        if (count($bets) > 0 && isset($bets[0]['competitor_name'])) {
                                            $competitors = $bets[0]['competitor_name'];
                                        }
                                    }
                                    // Estrutura 2: bets[0].competitor_name (direto no root)
                                    elseif (isset($betslipData['bets']) && is_array($betslipData['bets'])) {
                                        $bets = $betslipData['bets'];
                                        if (count($bets) > 0 && isset($bets[0]['competitor_name'])) {
                                            $competitors = $bets[0]['competitor_name'];
                                        }
                                    }
                                    // Estrutura 3: competitor_name direto no root
                                    elseif (isset($betslipData['competitor_name'])) {
                                        $competitors = $betslipData['competitor_name'];
                                    }
                                    
                                    // Se encontrou competitors, formatar
                                    if ($competitors && is_array($competitors) && count($competitors) >= 2) {
                                        $gameName = implode(' vs ', $competitors);
                                    }
                                    
                                } catch (\Exception $e) {
                                    // Manter o nome padrão se houver erro
                                }
                            }
                            
                            return [
                                'type' => 'sports',
                                'game' => $gameName,
                                'user_id' => $row->user_id,
                                'amount' => $row->amount,
                                'amount_win' => $row->amount_win ?? 0,
                                'action' => $row->operation,
                                'status' => $row->status ?? null,
                                'provider' => $row->provider ?? 'digitain',
                                'json' => '',
                                'updated_at' => $row->created_at,
                            ];
                        });
                }

                if ($a == "") {
                    $merged = collect($games ?? [])->merge($sportsGames ?? [])->sortByDesc('updated_at')->values();
                }elseif ($cassino){
                    $merged = $games->sortByDesc('updated_at')->values();
                }elseif ($sports){
                    $merged = $sportsGames->sortByDesc('updated_at')->values();
                }

                return DataTables::of(collect($merged))
                    ->addColumn('game_name', function ($row) {
                        // Para apostas esportivas, usar o nome já formatado do campo 'game'
                        if ($row['type'] === 'sports') {
                            return $row['game'];
                        }
                        
                        // Para cassino, buscar na tabela GamesApi
                        // Primeiro tentar buscar por ID se o game for numérico
                        if (is_numeric($row['game'])) {
                            $gameInfo = \App\Models\GamesApi::select('games_api.name')
                                ->where('games_api.id', $row['game'])
                                ->where('games_api.status', 1)
                                ->first();
                            
                            if ($gameInfo) {
                                return $gameInfo->name;
                            }
                        }
                        
                        // Se não encontrou por ID, buscar por slug
                        $gameInfo = \App\Models\GamesApi::select('games_api.name')
                            ->where('games_api.slug', $row['game'])
                            ->where('games_api.status', 1)
                            ->first();

                        return $gameInfo ? $gameInfo->name : 'N/A';
                    })
                    ->addColumn('action', function ($row) {
                        $infosjson = "";

                        if ($row['json'] != "") {
                            $json = json_decode($row['json'], true);

                            if ($json && is_array($json)) {
                                if (isset($json['slot'])) {
                                    $data = $json['slot'];
                                } elseif (isset($json['live'])) {
                                    $data = $json['live'];
                                } else {
                                    $data = null;
                                }
                            } else {
                                $data = null;
                            }

                            if ($data && is_array($data)) {
                                $saldoantes = isset($data['user_before_balance']) ? $data['user_before_balance'] : 'N/A';
                                $saldodepois = isset($data['user_after_balance']) ? $data['user_after_balance'] : 'N/A';

                                $infosjson = "<span class=\"badge badge-light-info mb-2 me-4\">Saldo Antes Jogo: {$saldoantes} / Saldo Pós Jogo: {$saldodepois}</span>";
                            }
                        }

                        // Para Betby, usar o status da coluna status ao invés de action/operation
                        if (isset($row['provider']) && $row['provider'] === 'betby' && isset($row['status'])) {
                            switch (strtolower($row['status'])) {
                                case 'pending':
                                    return "<span class=\"badge badge-light-warning mb-2 me-4\">Pendente</span>" . $infosjson;
                                case 'lost':
                                    return "<span class=\"badge badge-light-danger mb-2 me-4\">Perdeu</span>" . $infosjson;
                                case 'win':
                                    return "<span class=\"badge badge-light-success mb-2 me-4\">Ganhou</span>" . $infosjson;
                                case 'discard':
                                    return "<span class=\"badge badge-light-dark mb-2 me-4\">Rejeitado</span>" . $infosjson;
                                default:
                                    return "<span class=\"badge badge-light-dark mb-2 me-4\">" . ucfirst($row['status']) . "</span>" . $infosjson;
                            }
                        }

                        // Para Digitain, manter a lógica original
                        if ($row['action'] == 'loss'){return "<span class=\"badge badge-light-danger mb-2 me-4\">Perdeu</span>" . $infosjson; };
                        if ($row['action'] == 'win'){return "<span class=\"badge badge-light-success mb-2 me-4\">Ganhou</span>" . $infosjson; };

                        if ($row['action'] == 'debit'){return "<span class=\"badge badge-light-danger mb-2 me-4\">Perdeu</span>" . $infosjson; }
                        if ($row['action'] == 'credit'){return "<span class=\"badge badge-light-success mb-2 me-4\">Ganhou</span>" . $infosjson; }

                        if ($row['action'] == 'cancel_debit'){return "<span class=\"badge badge-light-danger mb-2 me-4\">Cancelamento (Perdeu)</span>" . $infosjson; }
                        if ($row['action'] == 'cancel_credit'){return "<span class=\"badge badge-light-success mb-2 me-4\">Cancelamento (Ganhou)</span>" . $infosjson; }
                    })
                    ->editColumn('amount', function ($row) {
                        // Para Betby, exibir valor da aposta e valor ganho se ganhou
                        if (isset($row['provider']) && $row['provider'] === 'betby') {
                            // Betby armazena valores em centavos, dividir por 100
                            $valorApostaCentavos = (float)$row['amount'] / 100;
                            $valorAposta = 'R$ ' . number_format($valorApostaCentavos, 2, ',', '.');
                            
                            // Se ganhou, mostrar também o valor ganho
                            if (isset($row['status']) && strtolower($row['status']) === 'win' && isset($row['amount_win']) && $row['amount_win'] > 0) {
                                $valorGanhoCentavos = (float)$row['amount_win'] / 100;
                                $valorGanho = 'R$ ' . number_format($valorGanhoCentavos, 2, ',', '.');
                                return $valorAposta . '<br><small class="text-success">Ganhou: ' . $valorGanho . '</small>';
                            }
                            
                            return $valorAposta;
                        }
                        
                        // Para Digitain, manter a lógica original
                        return 'R$ ' . number_format((float)$row['amount'], 2, ',', '.');
                    })
                    ->editColumn('updated_at', function ($row) {
                        return \Carbon\Carbon::parse($row['updated_at'])->format('d/m/Y H:i:s');
                    })
                    ->rawColumns(['action', 'amount'])
                    ->make(true);
            } else if ($request->input('tab') == 'afiliacao_agente') {
                $a = $request->input('a');
                $b = $request->input('b');
                $c = $request->input('c');
                $d = $request->input('d');

                $data = User::select('id', 'name', 'email', 'updated_at')->where('inviter', $d)->OrderBy('updated_at', 'desc');

                if (isset($b)) {$bb = Carbon::parse($b)->startOfDay();}else{$bb = "";}
                if (isset($c)) {$cc = Carbon::parse($c)->endOfDay();}else{$cc = "";}

                if ($b && $c) {
                    $data->whereBetween('users.updated_at', [$bb, $cc]);
                } elseif ($b && !$c) {
                    $data->whereBetween('users.updated_at', [$bb, Carbon::now()]);
                }

                return DataTables::of($data)
                    ->addColumn('name', function ($row) {
                        return $row->name;
                    })
                    ->addColumn('email', function ($row) {
                        return $row->email;
                    })
                    ->addColumn('amount', function ($row) use ($d, $bb, $cc, $b, $c) {
                        $afiliadosIds = $row->id;

                        $SomaRev = AffiliatesHistory::where('user_id', $afiliadosIds)->where('game', '!=', 'CPA');
                        $SomaCPA = AffiliatesHistory::where('user_id', $afiliadosIds)->where('game', 'CPA');

                        if ($b && $c) {
                            $SomaRev = AffiliatesHistory::where('user_id', $afiliadosIds)->where('game', '!=', 'CPA')->whereBetween('updated_at', [$bb, $cc]);
                            $SomaCPA = AffiliatesHistory::where('user_id', $afiliadosIds)->where('game', 'CPA')->whereBetween('updated_at', [$bb, $cc]);

                        } elseif ($b && !$c) {
                            $SomaRev = AffiliatesHistory::where('user_id', $afiliadosIds)->where('game', '!=', 'CPA')->whereBetween('updated_at', [$bb, Carbon::now()]);
                            $SomaCPA = AffiliatesHistory::where('user_id', $afiliadosIds)->where('game', 'CPA')->whereBetween('updated_at', [$bb, Carbon::now()]);
                        }

                        $SomaRev = $SomaRev->sum('amount');
                        $SomaCPA = $SomaCPA->sum('amount');

                        $TotalLiqDS = $SomaRev + $SomaCPA;

                        if ($TotalLiqDS > 0){
                            $Arrecadacao = "<span class=\"badge badge-light-success mb-2 me-4\">".sprintf("R$ %.2f", $TotalLiqDS)."</span>";
                        }else{
                            $Arrecadacao = "<span class=\"badge badge-light-danger mb-2 me-4\">".sprintf("R$ %.2f", $TotalLiqDS)."</span>";
                        }

                        return $Arrecadacao;
                    })
                    ->editColumn('updated_at', function ($row) use ($bb, $cc, $b, $c) {
                        $agr = Carbon::now();
                        $data = $row->updated_at->format('d/m/Y H:i:s');

                        if ($b && $c) {
                            $bb = $bb->format('d/m/Y H:i:s');
                            $cc = $cc->format('d/m/Y H:i:s');

                            $data = $bb . " - " . $cc;
                        } elseif ($b && !$c) {
                            $bb = $bb->format('d/m/Y H:i:s');
                            $agr = $agr->format('d/m/Y H:i:s');

                            $data = $bb . " - " . $agr;
                        }

                        return $data;
                    })
                    ->rawColumns(['amount'])
                    ->make(true);
            }
        }

        return 1;
    }

    public function index3(Request $request) {
        $dataInicial = $request->a;
        $dataFinal = $request->b;

        function FormataNumero($valor) {
            return number_format((float)$valor, 2, ',', '.');
        }

        $dataInicial = $request->a;
        $dataFinal = $request->b;
        $userId = $request->id;

        function aplicaFiltroData($query, $dataInicial, $dataFinal) {
            if ($dataInicial && $dataFinal) {
                $query->whereBetween('created_at', [Carbon::parse($dataInicial)->startOfDay(), Carbon::parse($dataFinal)->endOfDay()]);
            } elseif ($dataInicial) {
                $query->where('created_at', '>=', Carbon::parse($dataInicial)->startOfDay());
            } elseif ($dataFinal) {
                $query->where('created_at', '<=', Carbon::parse($dataFinal)->endOfDay());
            }
            return $query;
        }

        // Total de depósitos
        $depositosQuery = Transactions::where('user_id', $userId)->where('type', 0)->where('status', 1);
        $totalDepositos = aplicaFiltroData($depositosQuery, $dataInicial, $dataFinal)->sum('amount');

        // Total de saques
        $saquesQuery = Transactions::where('user_id', $userId)->where('type', 1)->where('status', 1);
        $totalSaques = aplicaFiltroData($saquesQuery, $dataInicial, $dataFinal)->sum('amount');

        // Total de bônus
        $bonusQuery = Transactions::where('user_id', $userId)->where('with_type', 'bonus')->where('status', 1);
        $totalBonus = aplicaFiltroData($bonusQuery, $dataInicial, $dataFinal)->sum('amount');

        // Total de apostas em esportes
        $apostasEsportesQuery = SportBetSummary::where('user_id', $userId)->where('operation', 'debit');
        $totalApostasEsportes = aplicaFiltroData($apostasEsportesQuery, $dataInicial, $dataFinal)->sum('amount');

        // Total de ganhos em esportes
        $ganhosEsportesQuery = SportBetSummary::where('user_id', $userId)->where('operation', 'credit');
        $totalGanhosEsportes = aplicaFiltroData($ganhosEsportesQuery, $dataInicial, $dataFinal)->sum('amount');

        // Total de apostas em cassino
        $apostasCassinoQuery = GameHistory::where('user_id', $userId);
        $totalApostasCassino = aplicaFiltroData($apostasCassinoQuery, $dataInicial, $dataFinal)->sum('amount');

        // Total de ganhos em cassino
        $ganhosCassinoQuery = GameHistory::where('user_id', $userId)->where('action', 'win');
        $totalGanhosCassino = aplicaFiltroData($ganhosCassinoQuery, $dataInicial, $dataFinal)->sum('amount');


        // Total de prêmios (ganhos em esportes + ganhos em cassino)
        $totalPremios = $totalGanhosEsportes + $totalGanhosCassino;

        // Calcular valor líquido (depósitos - saques)
        $valorLiquido = $totalDepositos - $totalSaques;

        // Calcular percentuais para os progress bars
        $totalMovimentacao = $totalDepositos + $totalSaques;
        $percentualDeposito = ($totalMovimentacao > 0) ? ($totalDepositos / $totalMovimentacao) * 100 : 0;
        $percentualSaque = ($totalMovimentacao > 0) ? ($totalSaques / $totalMovimentacao) * 100 : 0;

        // Formatar números para exibição

        $view = view('admin.includes.usuarios_stats', ['totalDepositos' => FormataNumero($totalDepositos), 'totalBonus' => FormataNumero($totalBonus), 'totalPremios' => FormataNumero($totalPremios),
            'totalSaques' => FormataNumero($totalSaques), 'totalApostasEsportes' => FormataNumero($totalApostasEsportes), 'totalApostasCassino' => FormataNumero($totalApostasCassino),
            'percentualDeposito' => $percentualDeposito, 'percentualSaque' => $percentualSaque, 'valorLiquido' => FormataNumero($valorLiquido)])->render();

        return response()->json(['status' => true, 'html' => $view]);
    }
}
