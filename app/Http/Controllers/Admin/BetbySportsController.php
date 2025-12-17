<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SportBetSummary;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class BetbySportsController extends Controller
{
    /**
     * Exibe a página de apostas esportivas Betby
     */
    public function sportsApostas(Request $request)
    {
        $a = $request->input('aff', '');
        $b = $request->input('di', Carbon::now()->subDays(7)->format('Y-m-d'));
        $c = $request->input('df', Carbon::now()->format('Y-m-d'));
        $statusFiltro = $request->input('statusFiltro', '');

        return view('admin.sportsBetby.sports_apostas', compact('a', 'b', 'c', 'statusFiltro'));
    }

    /**
     * Fornece dados para a tabela de apostas esportivas Betby
     * Seguindo lógica otimizada do GameHistoryTable
     */
    public function sportsApostasData(Request $request)
    {
        $dataInicial = $request->input('dataInicial', Carbon::now()->subDays(7)->format('Y-m-d'));
        $dataFinal = $request->input('dataFinal', Carbon::now()->format('Y-m-d'));
        $nomeUsuario = $request->input('nomeUsuario', '');
        $statusFiltro = $request->input('statusFiltro', '');

        // Formatar datas para incluir horário
        $dataInicialFormatada = $dataInicial . ' 00:00:00';
        $dataFinalFormatada = $dataFinal . ' 23:59:59';

        // Query otimizada - buscar apenas dados necessários para primeira visualização
        $query = DB::table('SportBetSummary as s1')
            ->select([
                's1.id',
                's1.transactionId',
                's1.user_id',
                's1.provider',
                's1.operation',
                's1.status',
                's1.amount',
                's1.betslip',
                's1.amount_win',
                's1.created_at'
            ])
            ->where('s1.provider', 'betby') // Filtrar apenas apostas Betby
            ->orderByDesc('s1.id');

        // Aplicar filtros de data
        if (!empty($dataInicial) && !empty($dataFinal)) {
            $query->whereBetween('s1.created_at', [$dataInicialFormatada, $dataFinalFormatada]);
        } elseif (!empty($dataInicial)) {
            $query->where('s1.created_at', '>=', $dataInicialFormatada);
        } elseif (!empty($dataFinal)) {
            $query->where('s1.created_at', '<=', $dataFinalFormatada);
        }

        // Filtro por nome do usuário
        if (!empty($nomeUsuario)) {
            $usuariosIds = User::where('name', 'like', '%' . $nomeUsuario . '%')
                ->pluck('id')
                ->toArray();

            if (!empty($usuariosIds)) {
                $query->whereIn('s1.user_id', $usuariosIds);
            } else {
                return DataTables::of(collect([]))->make(true);
            }
        }

        // Filtro por status específico da Betby
        if (!empty($statusFiltro)) {
            $query->where('s1.status', $statusFiltro);
        }

        try {
            return datatables()
                ->query($query)
                ->addColumn('usuario', function ($row) {
                    static $userCache = [];
                    if (!isset($userCache[$row->user_id])) {
                        $user = User::find($row->user_id);
                        if ($user) {
                            $ranking = $user->getRanking();
                            $rankingHtml = '';
                            if ($ranking && !empty($ranking['image'])) {
                                $rankingHtml = '<img src="' . asset($ranking['image']) . '" class="ranking-img me-2" width="25" height="25" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $ranking['name'] . '">';
                            }
                            $nomeHtml = '<a href="javascript:void(0);" onclick="LoadAgent(\'' . $user->id . '\');" data-bs-toggle="modal" data-bs-target="#tabsModal" class="bs-tooltip" title="Visualizar Usuário">' . $user->name . '</a>';
                            $userCache[$row->user_id] = $rankingHtml . $nomeHtml;
                        } else {
                            $userCache[$row->user_id] = 'N/A';
                        }
                    }
                    return $userCache[$row->user_id];
                })
                ->addColumn('id_transacao', function ($row) {
                    return '<span class="badge badge-light-info">' . ($row->transactionId ?? 'N/A') . '</span>';
                })
                ->addColumn('status', function ($row) {
                    // Para Betby, usar o status diretamente do registro
                    $finalStatus = strtolower($row->status ?? 'pending');

                    // Verificar cashout - priorizar coluna cashout da tabela sportbetsummary
                    $hasCashout = false;
                    
                    // Primeiro, verificar a coluna cashout da tabela
                    if (isset($row->cashout) && ($row->cashout === 1 || $row->cashout === '1')) {
                        $hasCashout = true;
                    }
                    // Se não tiver na coluna, tentar no JSON betslip
                    elseif ($row->betslip) {
                        try {
                            $betslipData = json_decode($row->betslip, true);
                            if (is_array($betslipData) && isset($betslipData['is_cashout'])) {
                                $hasCashout = $betslipData['is_cashout'] === "1" || $betslipData['is_cashout'] === 1;
                            }
                        } catch (\Exception $e) {
                            // Continua sem cashout
                        }
                    }

                    switch ($finalStatus) {
                        case 'pending':
                            return '<span class="badge badge-light-warning">Pendente</span>';
                        case 'win':
                            if ($hasCashout) {
                                return '<span class="badge badge-light-primary">Cashout</span>';
                            } else {
                                return '<span class="badge badge-light-success">Ganhou</span>';
                            }
                        case 'lost':
                            return '<span class="badge badge-light-danger">Perdeu</span>';
                        case 'discard':
                            return '<span class="badge badge-dark">Rejeitada</span>';
                        default:
                            return '<span class="badge badge-light-dark">Status: ' . $finalStatus . '</span>';
                    }
                })
                ->addColumn('odd', function ($row) {
                    $odd = $this->calculateOdd($row);

                    // Log temporário para debug (apenas para algumas transações)
                    if (rand(1, 20) === 1) { // Log aleatório de 5% das transações
                        \Log::info('Debug Odd Calculation', [
                            'transactionId' => $row->transactionId,
                            'calculated_odd' => $odd,
                            'betslip_exists' => !empty($row->betslip),
                            'betslip_sample' => substr($row->betslip, 0, 200) . '...'
                        ]);
                    }

                    // Formatar odd com 2 casas decimais (formato padrão: 10.00)
                    $oddFormatted = number_format($odd, 2, '.', '');

                    // Aplicar classe de cor baseada no valor da odd (mesmo critério do JS)
                    $badgeClass = 'badge-secondary'; // Padrão
                    if ($odd >= 5.0) {
                        $badgeClass = 'badge-danger';
                    } elseif ($odd >= 3.0) {
                        $badgeClass = 'badge-warning';
                    } elseif ($odd >= 2.0) {
                        $badgeClass = 'badge-info';
                    } elseif ($odd > 1.0) {
                        $badgeClass = 'badge-success';
                    }

                    return '<span class="badge ' . $badgeClass . '">' . $oddFormatted . '</span>';
                })
                ->addColumn('valor', function ($row) {
                    // Betby: valor vem em centavos (100 = R$ 1,00)
                    $valor = ($row->amount ?? 0) / 100;
                    return 'R$ ' . number_format($valor, 2, ',', '.');
                })
                ->addColumn('possivel_ganho', function ($row) {
                    $finalStatus = strtolower($row->status ?? 'pending');

                    // Se perdeu ou foi rejeitada, sempre mostrar R$ 0,00
                    if (in_array($finalStatus, ['lost', 'discard'])) {
                        return 'R$ 0,00';
                    }

                    // Se ganhou, mostrar o valor real recebido (amount_win)
                    if ($finalStatus === 'win') {
                        $valorRecebido = ($row->amount_win ?? 0) / 100; // Converter de centavos
                        return 'R$ ' . number_format($valorRecebido, 2, ',', '.');
                    }

                    // Para status pending, buscar potential_win no betslip
                    $possibleWin = $this->calculatePossibleWinFromBetslip($row);

                    // Betby: valor vem em centavos (dividir por 100)
                    $valor = $possibleWin / 100;

                    if ($valor > 0) {
                        return 'R$ ' . number_format($valor, 2, ',', '.');
                    } else {
                        return 'R$ 0,00';
                    }
                })
                ->addColumn('data', function ($row) {
                    return Carbon::parse($row->created_at)->format('d/m/Y H:i:s');
                })
                ->addColumn('acoes', function ($row) {
                    // Dados para o modal
                    $betslip = $row->betslip;

                    // Para Betby, usar status diretamente do registro
                    $operation = $row->status ?? 'pending';

                    $amount = $row->amount ?? 0;
                    $receivedAmount = $row->amount_win ?? 0;

                    // Verificar cashout - priorizar coluna cashout da tabela sportbetsummary
                    $hasCashout = false;
                    
                    // Primeiro, verificar a coluna cashout da tabela
                    if (isset($row->cashout) && ($row->cashout === 1 || $row->cashout === '1')) {
                        $hasCashout = true;
                    }
                    // Se não tiver na coluna, tentar no JSON betslip
                    elseif ($row->betslip) {
                        try {
                            $betslipData = json_decode($row->betslip, true);
                            if (is_array($betslipData) && isset($betslipData['is_cashout'])) {
                                $hasCashout = $betslipData['is_cashout'] === "1" || $betslipData['is_cashout'] === 1;
                            }
                        } catch (\Exception $e) {
                            // Continua sem cashout
                        }
                    }

                    $user = User::find($row->user_id);
                    $userName = $user ? $user->name : 'N/A';

                    // Buscar todas as operações desta transação para o histórico
                    $operacoes = DB::table('SportBetSummary')
                        ->where('transactionId', $row->transactionId)
                        ->where('provider', 'betby')
                        ->orderBy('created_at', 'desc')
                        ->limit(20)
                        ->get();

                    return '<button type="button" class="btn btn-primary btn-sm ver-aposta-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#verApostaModal"
                                data-betslip="' . htmlspecialchars($betslip ?: '{}', ENT_QUOTES, 'UTF-8') . '"
                                data-operation="' . htmlspecialchars($operation, ENT_QUOTES, 'UTF-8') . '"
                                data-amount="' . $amount . '"
                                data-cashout="' . ($row->cashout ?? 0) . '"
                                data-received-amount="' . $receivedAmount . '"
                                data-user-id="' . $row->user_id . '"
                                data-user-name="' . htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') . '"
                                data-provider="Betby"
                                data-operacoes-base64="' . base64_encode(json_encode($operacoes)) . '">
                            Ver Aposta
                            </button>';
                })
                ->rawColumns(['id_transacao', 'usuario', 'status', 'odd', 'acoes'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao carregar dados',
                'message' => $e->getMessage(),
                'draw' => $request->get('draw', 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ], 500);
        }
    }

    /**
     * Fornece estatísticas das apostas
     */
    public function sportsApostasStats(Request $request)
    {
        $dataInicial = $request->input('dataInicial', Carbon::now()->subDays(7)->format('Y-m-d'));
        $dataFinal = $request->input('dataFinal', Carbon::now()->format('Y-m-d'));
        $nomeUsuario = $request->input('nomeUsuario', '');
        $statusFiltro = $request->input('statusFiltro', '');

        // Formatar datas para incluir horário
        $dataInicialFormatada = $dataInicial . ' 00:00:00';
        $dataFinalFormatada = $dataFinal . ' 23:59:59';

        $query = SportBetSummary::query();

        // Filtrar apenas apostas da Betby
        $query->where('provider', 'betby');

        // Aplicar filtros de data
        if (!empty($dataInicial) && !empty($dataFinal)) {
            $query->whereBetween('created_at', [$dataInicialFormatada, $dataFinalFormatada]);
        }

        // Filtro por nome do usuário
        if (!empty($nomeUsuario)) {
            $usuariosIds = User::where('name', 'like', '%' . $nomeUsuario . '%')->pluck('id')->toArray();
            if (!empty($usuariosIds)) {
                $query->whereIn('user_id', $usuariosIds);
            }
        }

        // Filtro por status (se fornecido)
        if (!empty($statusFiltro)) {
            $query->where('status', $statusFiltro);
        }

        $registros = $query->get();

        if ($registros->isEmpty()) {
            return response()->json([
                'bilhetes_abertos' => 0,
                'bilhetes_finalizados' => 0,
                'bilhetes_premiados' => 0,
                'valor_apostas_abertas' => number_format(0, 2, ',', '.'),
                'valor_apostas_finalizadas' => number_format(0, 2, ',', '.'),
                'valor_premios' => number_format(0, 2, ',', '.')
            ]);
        }

        $pendente = 0;
        $totalpendente = 0;
        $finalizado = 0;
        $totalfinalizado = 0;
        $premiado = 0;
        $totalpremiado = 0;

        foreach ($registros as $aposta) {
            $status = strtolower($aposta->status ?? 'pending');
            $valorAposta = ($aposta->amount / 100); // Converter de centavos

            // Desconsiderar apostas rejeitadas (discard) na contabilização
            if ($status === 'discard') {
                continue;
            }

            if ($status === 'pending') {
                // Bilhetes pendentes
                $pendente++;
                $totalpendente += $valorAposta;
            } elseif ($status === 'win') {
                // Bilhetes premiados (ganharam)
                $premiado++;
                $finalizado++;
                $totalpremiado += ($aposta->amount_win / 100); // Valor do prêmio
                $totalfinalizado += $valorAposta; // Valor apostado
            } elseif ($status === 'lost') {
                // Bilhetes perdidos
                $finalizado++;
                $totalfinalizado += $valorAposta; // Valor apostado
            }
        }

        return response()->json([
            'bilhetes_abertos' => $pendente,
            'bilhetes_finalizados' => $finalizado,
            'bilhetes_premiados' => $premiado,
            'valor_apostas_abertas' => number_format($totalpendente, 2, ',', '.'),
            'valor_apostas_finalizadas' => number_format($totalfinalizado, 2, ',', '.'),
            'valor_premios' => number_format($totalpremiado, 2, ',', '.')
        ]);
    }


    /**
     * Obtém lista de esportes únicos do betslip
     */
    private function getEsportesFromBetslip()
    {
        $esportes = SportBetSummary::where('provider', 'betby')
            ->where('operation', 'make')
            ->whereNotNull('betslip')
            ->get()
            ->flatMap(function ($bet) {
                $betslipData = $bet->betslip ?? [];
                $esportesEncontrados = [];

                // Estrutura real: betslip.bets[].sport_name
                if (isset($betslipData['betslip']['bets']) && is_array($betslipData['betslip']['bets'])) {
                    foreach ($betslipData['betslip']['bets'] as $betItem) {
                        if (isset($betItem['sport_name']) && !empty($betItem['sport_name'])) {
                            $esportesEncontrados[] = $betItem['sport_name'];
                        }
                    }
                }

                return $esportesEncontrados;
            })
            ->unique()
            ->values();

        return response()->json(['esportes' => $esportes]);
    }

    /**
     * Obtém lista de países por esporte
     */
    private function getPaisesFromBetslip($esporte)
    {
        $paises = SportBetSummary::where('provider', 'betby')
            ->where('operation', 'make')
            ->whereNotNull('betslip')
            ->get()
            ->flatMap(function ($bet) use ($esporte) {
                $betslipData = $bet->betslip ?? [];
                $paisesEncontrados = [];

                // Estrutura real: betslip.bets[].category_name
                if (isset($betslipData['betslip']['bets']) && is_array($betslipData['betslip']['bets'])) {
                    foreach ($betslipData['betslip']['bets'] as $betItem) {
                        $sportName = $betItem['sport_name'] ?? '';
                        $categoryName = $betItem['category_name'] ?? '';

                        if ($sportName === $esporte && !empty($categoryName)) {
                            $paisesEncontrados[] = $categoryName;
                        }
                    }
                }

                return $paisesEncontrados;
            })
            ->unique()
            ->values();

        return response()->json(['paises' => $paises]);
    }

    /**
     * Obtém lista de campeonatos por esporte e país
     */
    private function getCampeonatosFromBetslip($esporte, $pais)
    {
        $campeonatos = SportBetSummary::where('provider', 'betby')
            ->where('operation', 'make')
            ->whereNotNull('betslip')
            ->get()
            ->flatMap(function ($bet) use ($esporte, $pais) {
                $betslipData = $bet->betslip ?? [];
                $campeonatosEncontrados = [];

                // Estrutura real: betslip.bets[].tournament_name
                if (isset($betslipData['betslip']['bets']) && is_array($betslipData['betslip']['bets'])) {
                    foreach ($betslipData['betslip']['bets'] as $betItem) {
                        $sportName = $betItem['sport_name'] ?? '';
                        $categoryName = $betItem['category_name'] ?? '';
                        $tournamentName = $betItem['tournament_name'] ?? '';

                        if ($sportName === $esporte && $categoryName === $pais && !empty($tournamentName)) {
                            $campeonatosEncontrados[] = $tournamentName;
                        }
                    }
                }

                return $campeonatosEncontrados;
            })
            ->unique()
            ->values();

        return response()->json(['campeonatos' => $campeonatos]);
    }

    /**
     * Exibe a página de estatísticas esportivas Betby
     */
    public function sportsEstatisticas(Request $request)
    {
        // Verificar se é uma requisição AJAX para retornar apenas dados JSON
        if ($request->input('ajax')) {
            $data = $this->sportsEstatisticasData($request);

            // Formatar valores para exibição
            $data['totalApostasFormatado'] = number_format($data['totalApostas'], 2, ',', '.');
            $data['totalApostasAbertasFormatado'] = number_format($data['totalApostasAbertas'], 2, ',', '.');
            $data['totalPremiosFormatado'] = number_format($data['totalPremios'], 2, ',', '.');
            $data['totalLiquidoFormatado'] = number_format($data['totalLiquido'], 2, ',', '.');

            return response()->json($data);
        }

        // Obter dados processados para a view
        $data = $this->sportsEstatisticasData($request);

        return view('admin.sportsBetby.sports_estatisticas', $data);
    }

    /**
     * Processa dados das estatísticas esportivas Betby
     */
    public function sportsEstatisticasData(Request $request)
    {
        // Verificar se é filtro de data predefinida ou data customizada
        $filtroDataPredefinida = $request->input('filtroDataPredefinida', '');

        // Se filtro predefinido for "nenhum" ou vazio, usar datas customizadas ou padrão
        if ($filtroDataPredefinida && $filtroDataPredefinida !== 'nenhum') {
            $diasAtras = (int) $filtroDataPredefinida;
            $dataInicial = Carbon::now()->subDays($diasAtras - 1)->format('Y-m-d'); // -1 para incluir hoje
            $dataFinal = Carbon::now()->format('Y-m-d');
        } else {
            // Usar datas customizadas ou padrão (hoje)
            $dataInicial = $request->input('dataInicial');
            $dataFinal = $request->input('dataFinal');

            // Se nenhuma data foi fornecida, usar hoje como padrão
            if (empty($dataInicial) && empty($dataFinal)) {
                $dataInicial = Carbon::now()->format('Y-m-d');
                $dataFinal = Carbon::now()->format('Y-m-d');
            } elseif (empty($dataInicial)) {
                $dataInicial = $dataFinal;
            } elseif (empty($dataFinal)) {
                $dataFinal = $dataInicial;
            }
        }

        $dataInicialFormatada = $dataInicial . ' 00:00:00';
        $dataFinalFormatada = $dataFinal . ' 23:59:59';

        // Query principal para SportBetSummary da Betby - GARANTINDO APENAS PROVIDER BETBY
        $query = SportBetSummary::with(['user'])
            ->where('provider', 'betby') // Garantindo apenas Betby
            ->where('operation', 'make'); // BetBy sempre usa 'make'

        if (!empty($dataInicial) && !empty($dataFinal)) {
            $query->whereBetween('created_at', [$dataInicialFormatada, $dataFinalFormatada]);
        } elseif (!empty($dataInicial)) {
            $query->where('created_at', '>=', $dataInicialFormatada);
        } elseif (!empty($dataFinal)) {
            $query->where('created_at', '<=', $dataFinalFormatada);
        }

        $registros = $query->get();

        $pendente = 0;
        $totalpendente = 0;
        $finalizado = 0;
        $totalfinalizado = 0;
        $premiado = 0;
        $totalpremiado = 0;

        $dates = collect();
        $esportes = [];
        $datas = [];

        // Processar período para gráfico
        $period = Carbon::parse($dataInicial)->daysUntil(Carbon::parse($dataFinal));

        foreach ($period as $date) {
            $dia = $date->format('Y-m-d');
            $dates->push($date->format('d/m'));

            $datas[$dia] = true;
        }

        $datasOrdenadas = array_keys($datas);
        sort($datasOrdenadas);

        $mapaDataParaIndice = [];
        foreach ($datasOrdenadas as $index => $data) {
            $mapaDataParaIndice[$data] = $index;
        }

        $teste = [];
        $teste2 = [];
        $teste3 = [];
        $teste4 = [];

        foreach ($registros as $bet) {
            $valorAposta = $bet->amount / 100; // Converter de centavos para reais
            $status = $bet->status;

            $dataFormatada = $bet->created_at->format('Y-m-d');
            $dataFormatada2 = $bet->created_at->format('d/m/Y');

            $indice = $mapaDataParaIndice[$dataFormatada] ?? 0;

            if (!isset($teste[$indice])) {
                $teste[$indice] = 0.00;
            }

            if (!isset($teste2[$indice])) {
                $teste2[$indice] = 0.00;
            }

            if (!isset($teste3[$indice])) {
                $teste3[$indice] = 0.00;
            }

            if (!isset($teste4[$indice])) {
                $teste4[$indice] = $dataFormatada2;
            }

            /* Gráfico Pizza esportes */
            $betslipData = $bet->betslip ?? [];
            $esporte = "Outro";

            if (!empty($betslipData) && is_array($betslipData)) {
                // Estrutura real da Betby: betslip.bets[].sport_name
                if (isset($betslipData['betslip']['bets']) && is_array($betslipData['betslip']['bets'])) {
                    $firstBet = $betslipData['betslip']['bets'][0] ?? [];
                    if (isset($firstBet['sport_name'])) {
                        $sport = $firstBet['sport_name'];
                        $category = $firstBet['category_name'] ?? '';
                        $esporte = $sport . (!empty($category) ? " ($category)" : "");
                    }
                }
                // Fallback para outras estruturas
                elseif (isset($betslipData[0]['sport_name'])) {
                    $sport = $betslipData[0]['sport_name'];
                    $category = $betslipData[0]['category_name'] ?? '';
                    $esporte = $sport . (!empty($category) ? " ($category)" : "");
                } elseif (isset($betslipData['sport_name'])) {
                    $sport = $betslipData['sport_name'];
                    $category = $betslipData['category_name'] ?? '';
                    $esporte = $sport . (!empty($category) ? " ($category)" : "");
                }
            }

            if (!isset($esportes[$esporte])) {
                $esportes[$esporte] = ['apostas' => 0, 'premios' => 0, 'lucro' => 0, 'percentual' => 0];
            }

            if ($status === 'win') {
                $premiado++;
                $finalizado++;
                $valorPremio = ($bet->amount_win ?? 0) / 100; // Converter de centavos para reais
                $totalpremiado += $valorPremio;
                $totalfinalizado += $valorAposta;

                $teste[$indice] += $valorAposta;
                $teste2[$indice] += $valorPremio;

                $esportes[$esporte]['apostas'] += $valorAposta;
                $esportes[$esporte]['premios'] += $valorPremio;
            } elseif ($status === 'lost') {
                $finalizado++;
                $totalfinalizado += $valorAposta;

                $teste[$indice] += $valorAposta;

                $esportes[$esporte]['apostas'] += $valorAposta;
            } elseif ($status === 'rollback') {
                $finalizado++;
                $totalfinalizado += $valorAposta;

                $teste[$indice] += $valorAposta;
            } elseif ($status === 'Pending') {
                // Pendente ou em aberto (status = 'pending')
                $pendente++;
                $totalpendente += $valorAposta;

                $teste[$indice] += $valorAposta;

                $esportes[$esporte]['apostas'] += $valorAposta;
            }
        }

        // Ordenar arrays
        ksort($teste);
        ksort($teste2);
        ksort($teste3);
        ksort($teste4);

        // Calcular lucro
        foreach ($teste as $i => $apostas) {
            $premios = $teste2[$i] ?? 0;
            $teste3[$i] = $apostas - $premios;
        }

        foreach ($esportes as &$row) {
            $row['lucro'] = $row['apostas'] - $row['premios'];
            $row['percentual'] = $row['apostas'] > 0 ? round(($row['lucro'] / $row['apostas']) * 100, 2) : 0;
        }
        unset($row);

        /* Gráfico Onda */
        $series = [
            [
                'name' => 'Apostas',
                'data' => array_values($teste)
            ],
            [
                'name' => 'Prêmios',
                'data' => array_values($teste2)
            ],
            [
                'name' => 'Lucro',
                'data' => array_values($teste3)
            ]
        ];

        $labels = array_values($teste4);
        $esporteLabels = json_encode(array_keys($esportes));
        $esporteData = json_encode(array_column($esportes, 'apostas'));

        // Cálculos finais corrigidos
        $totalApostasDireto = $totalpendente + $totalfinalizado;
        $totalApostasAbertas = $totalpendente; // Apostas em aberto
        $totalApostas = $totalApostasDireto; // Total de apostas
        $totalPremios = $totalpremiado; // Total de prêmios (apenas status 'win')
        $totalLiquido = $totalApostas - $totalPremios; // Líquido = Total apostas - Prêmios

        return [
            'dataInicial' => $dataInicial,
            'dataFinal' => $dataFinal,
            'totalApostas' => $totalApostas,
            'totalApostasAbertas' => $totalApostasAbertas,
            'totalPremios' => $totalPremios,
            'totalLiquido' => $totalLiquido,
            'esportes' => $esportes,
            'series' => $series,
            'labels' => $labels,
            'esporteLabels' => $esporteLabels,
            'esporteData' => $esporteData,
        ];
    }

    /**
     * Fornece dados para o DataTable de estatísticas por esporte
     */
    public function sportsEstatisticasTable(Request $request)
    {
        // Se está solicitando esportes
        if ($request->input('getEsportes')) {
            return $this->getEsportesFromBetslip();
        }

        // Se está solicitando países
        if ($request->input('getPaises')) {
            $esporte = $request->input('esporte');
            return $this->getPaisesFromBetslip($esporte);
        }

        // Se está solicitando campeonatos
        if ($request->input('getCampeonatos')) {
            $esporte = $request->input('esporte');
            $pais = $request->input('pais');
            return $this->getCampeonatosFromBetslip($esporte, $pais);
        }

        // Verificar se é filtro de data predefinida ou data customizada
        $filtroDataPredefinida = $request->input('filtroDataPredefinida', '');

        // Se filtro predefinido for "nenhum" ou vazio, usar datas customizadas
        if ($filtroDataPredefinida && $filtroDataPredefinida !== 'nenhum') {
            $diasAtras = (int) $filtroDataPredefinida;
            $dataInicial = Carbon::now()->subDays($diasAtras - 1)->format('Y-m-d'); // -1 para incluir hoje
            $dataFinal = Carbon::now()->format('Y-m-d');
        } else {
            $dataInicial = $request->input('dataInicial');
            $dataFinal = $request->input('dataFinal');

            // Se nenhuma data foi fornecida, usar hoje como padrão
            if (empty($dataInicial) && empty($dataFinal)) {
                $dataInicial = Carbon::now()->format('Y-m-d');
                $dataFinal = Carbon::now()->format('Y-m-d');
            } elseif (empty($dataInicial)) {
                $dataInicial = $dataFinal;
            } elseif (empty($dataFinal)) {
                $dataFinal = $dataInicial;
            }
        }

        $esporteFiltro = $request->input('esporteFiltro', '');
        $paisFiltro = $request->input('paisFiltro', '');
        $campeonatoFiltro = $request->input('campeonatoFiltro', '');

        $dataInicialFormatada = $dataInicial . ' 00:00:00';
        $dataFinalFormatada = $dataFinal . ' 23:59:59';

        // Query principal
        $query = SportBetSummary::where('provider', 'betby')
            ->where('operation', 'make')
            ->whereBetween('created_at', [$dataInicialFormatada, $dataFinalFormatada]);

        $registros = $query->get();
        $resultados = [];

        foreach ($registros as $bet) {
            $betslipData = $bet->betslip ?? [];

            if (empty($betslipData) || !is_array($betslipData)) {
                continue;
            }

            // Estrutura real: betslip.bets[] contém os dados dos jogos
            if (!isset($betslipData['betslip']['bets']) || !is_array($betslipData['betslip']['bets'])) {
                continue;
            }

            // Processar cada aposta no betslip (pode ter múltiplas apostas)
            foreach ($betslipData['betslip']['bets'] as $betItem) {
                $sportName = $betItem['sport_name'] ?? '';
                $categoryName = $betItem['category_name'] ?? '';
                $tournamentName = $betItem['tournament_name'] ?? '';

                // Pular se não conseguiu extrair o esporte mínimo
                if (empty($sportName)) {
                    continue;
                }

                // Aplicar filtros
                if ($esporteFiltro && $sportName !== $esporteFiltro) {
                    continue;
                }

                if ($paisFiltro && $categoryName !== $paisFiltro) {
                    continue;
                }

                if ($campeonatoFiltro && $tournamentName !== $campeonatoFiltro) {
                    continue;
                }

                // Criar chave única para agrupar
                $chave = $sportName . ' - ' . ($categoryName ?: 'N/A') . ' - ' . ($tournamentName ?: 'N/A');

                if (!isset($resultados[$chave])) {
                    $resultados[$chave] = [
                        'esporte' => $sportName,
                        'pais' => $categoryName ?: 'N/A',
                        'campeonato' => $tournamentName ?: 'N/A',
                        'apostas' => 0,
                        'premios' => 0,
                        'lucro' => 0
                    ];
                }

                // Para apostas múltiplas, dividir o valor proporcionalmente
                // Converter de centavos para reais (dividir por 100)
                $valorAposta = $bet->amount / 100; // Valor total da aposta em reais
                $numeroBets = count($betslipData['betslip']['bets']);
                $valorProporcional = $valorAposta / $numeroBets;

                // O valor da aposta é apenas o valor dividido pela quantidade
                $resultados[$chave]['apostas'] += $valorProporcional;

                // Calcular prêmios apenas para apostas ganhas (status = 'win')
                if ($bet->status === 'win') {
                    // Usar a coluna amount_win (valor real recebido) dividido pela quantidade de apostas
                    $amountWin = $bet->amount_win ?? 0; // Coluna da tabela SportBetSummary
                    $quantidadeApostas = $numeroBets;

                    // Extrair quantidade de apostas do campo "type" (ex: "2/2" = 2 apostas)
                    if (isset($betslipData['betslip']['type'])) {
                        $type = $betslipData['betslip']['type'];
                        if (preg_match('/^(\d+)\/\d+$/', $type, $matches)) {
                            $quantidadeApostas = (int) $matches[1];
                        }
                    } elseif (isset($betslipData['type'])) {
                        $type = $betslipData['type'];
                        if (preg_match('/^(\d+)\/\d+$/', $type, $matches)) {
                            $quantidadeApostas = (int) $matches[1];
                        }
                    }

                    // Calcular prêmio proporcional: amount_win / quantidade_apostas / 100 (converter de centavos)
                    if ($amountWin > 0 && $quantidadeApostas > 0) {
                        $valorPremioIndividual = ($amountWin / $quantidadeApostas) / 100;
                        $resultados[$chave]['premios'] += $valorPremioIndividual;
                    }
                }

                // Recalcular lucro
                $resultados[$chave]['lucro'] = $resultados[$chave]['apostas'] - $resultados[$chave]['premios'];
            }
        }

        return datatables()
            ->of(array_values($resultados))
            ->addColumn('esporte_pais_campeonato', function ($row) {
                return $row['esporte'] . ' - ' . $row['pais'] . ' - ' . $row['campeonato'];
            })
            ->addColumn('apostas_formatado', function ($row) {
                return 'R$ ' . number_format($row['apostas'], 2, ',', '.');
            })
            ->addColumn('premios_formatado', function ($row) {
                return 'R$ ' . number_format($row['premios'], 2, ',', '.');
            })
            ->addColumn('lucro_formatado', function ($row) {
                $cor = $row['lucro'] >= 0 ? 'text-success' : 'text-danger';
                return '<span class="' . $cor . '">R$ ' . number_format($row['lucro'], 2, ',', '.') . '</span>';
            })
            ->rawColumns(['lucro_formatado'])
            ->make(true);
    }

    /**
     * Exibe a página do mapa de apostas
     */
    public function mapaApostas(Request $request)
    {
        return view('admin.sportsBetby.mapa_apostas');
    }

    /**
     * Fornece dados para o mapa de apostas
     * Pega apostas dos últimos X dias agrupadas por event_id
     */
    public function mapaApostasData(Request $request)
    {
        // Se está solicitando apenas a lista de esportes
        if ($request->input('getEsportes')) {
            return $this->getEsportesDisponiveis();
        }

        // Parâmetros de filtro
        $tipoAposta = $request->input('tipoAposta', ''); // '', 'simples', 'multiplas'
        $esporteFiltro = $request->input('esporteFiltro', '');
        $filtroData = $request->input('filtroData', 7);

        // Calcular data baseada no filtro
        $diasAtras = (int) $filtroData;
        $dataInicial = Carbon::now()->subDays($diasAtras)->format('Y-m-d H:i:s');
        $dataFinal = Carbon::now()->format('Y-m-d H:i:s');

        // Query para buscar apostas betby pendentes dos últimos 7 dias
        $query = DB::table('SportBetSummary')
            ->select([
                'id',
                'transactionId',
                'user_id',
                'provider',
                'status',
                'amount',
                'betslip',
                'created_at'
            ])
            ->where('provider', 'betby')
            ->where('status', 'pending')
            ->whereBetween('created_at', [$dataInicial, $dataFinal])
            ->orderByDesc('id');

        $apostas = $query->get();

        // Agrupar apostas por event_id
        $eventosMap = [];

        foreach ($apostas as $aposta) {
            if (!$aposta->betslip) continue;

            try {
                $betslipData = json_decode($aposta->betslip, true);
                if (!is_array($betslipData)) continue;

                // Buscar bets no JSON
                $bets = null;
                if (isset($betslipData['betslip']['bets']) && is_array($betslipData['betslip']['bets'])) {
                    $bets = $betslipData['betslip']['bets'];
                } elseif (isset($betslipData['bets']) && is_array($betslipData['bets'])) {
                    $bets = $betslipData['bets'];
                } elseif (is_array($betslipData) && isset($betslipData[0])) {
                    $bets = $betslipData;
                }

                if (!$bets) continue;

                // Aplicar filtro de tipo de aposta
                if ($tipoAposta === 'simples' && count($bets) > 1) {
                    continue; // Pular apostas múltiplas se filtro é "simples"
                } elseif ($tipoAposta === 'multiplas' && count($bets) <= 1) {
                    continue; // Pular apostas simples se filtro é "multiplas"
                }

                // Processar cada bet
                foreach ($bets as $bet) {
                    if (!isset($bet['event_id'])) continue;

                    $eventId = $bet['event_id'];
                    $sport = $bet['sport_name'] ?? 'N/A';
                    $competitors = $bet['competitor_name'] ?? [];

                    // Aplicar filtro de esporte
                    if (!empty($esporteFiltro) && strtolower($sport) !== strtolower($esporteFiltro)) {
                        continue;
                    }

                    // Formar nome da partida
                    // Manter ordem original: primeiro time (casa) vs segundo time (visitante)
                    $partida = 'N/A';
                    if (is_array($competitors) && count($competitors) >= 2) {
                        $partida = implode(' vs ', $competitors);
                    } elseif (is_string($competitors)) {
                        $partida = $competitors;
                    }

                    // Formar data/hora do evento
                    $dataHoraEvento = 'N/A';
                    if (isset($bet['scheduled'])) {
                        $timestamp = (int) $bet['scheduled'];
                        $dataHoraEvento = date('d/m/Y H:i', $timestamp);
                    }

                    // Agrupar por event_id
                    if (!isset($eventosMap[$eventId])) {
                        $eventosMap[$eventId] = [
                            'event_id' => $eventId,
                            'partida' => $partida,
                            'esporte' => $sport,
                            'data_hora' => $dataHoraEvento,
                            'valor_acumulado' => 0,
                            'quantidade_apostas' => 0,
                            'detalhes' => []
                        ];
                    }

                    // Somar valores - dividir por 100 pois valores da Betby estão em centavos
                    $eventosMap[$eventId]['valor_acumulado'] += ((float) $aposta->amount / 100);
                    $eventosMap[$eventId]['quantidade_apostas']++;

                    // Armazenar detalhes para a página de detalhes (futura)
                    $eventosMap[$eventId]['detalhes'][] = [
                        'transaction_id' => $aposta->transactionId,
                        'user_id' => $aposta->user_id,
                        'amount' => ($aposta->amount / 100), // Converter de centavos para reais
                        'created_at' => $aposta->created_at
                    ];
                }

            } catch (\Exception $e) {
                // Log do erro se necessário, mas continua processando
                continue;
            }
        }

        // Converter para array e ordenar por valor acumulado (maior primeiro)
        $eventos = array_values($eventosMap);

        // Ordenar por valor acumulado descendente
        usort($eventos, function($a, $b) {
            return $b['valor_acumulado'] <=> $a['valor_acumulado'];
        });

        return datatables()
            ->of(collect($eventos))
            ->addColumn('partida', function ($evento) {
                return $evento['partida'];
            })
            ->addColumn('esporte', function ($evento) {
                return strtoupper($evento['esporte']);
            })
            ->addColumn('data_hora', function ($evento) {
                return $evento['data_hora'];
            })
            ->addColumn('valor_acumulado', function ($evento) {
                return 'R$ ' . number_format($evento['valor_acumulado'], 2, ',', '.');
            })
            ->addColumn('valor_acumulado_raw', function ($evento) {
                return $evento['valor_acumulado'];
            })
            ->addColumn('opcoes', function ($evento) {
                return '<button type="button" class="btn btn-warning btn-sm" onclick="verDetalhes(\'' . $evento['event_id'] . '\')">
                            Detalhes
                        </button>';
            })
            ->rawColumns(['opcoes'])
            ->make(true);
    }

    /**
     * Exibe a página de detalhes do mapa de apostas
     */
    public function mapaApostasDetalhes(Request $request, $event_id)
    {
        // Buscar informações do evento baseado no event_id
        $eventoInfo = $this->getEventoInfo($event_id);

        return view('admin.sportsBetby.mapa_apostas_detalhes', compact('event_id', 'eventoInfo'));
    }

    /**
     * Fornece dados para o DataTable da página de detalhes
     */
    public function mapaApostasDetalhesData(Request $request, $event_id)
    {
        // Se está solicitando bilhetes do evento
        if ($request->input('getBilhetes')) {
            return $this->getBilhetesEvento($event_id);
        }
        // Data dos últimos 7 dias
        $dataInicial = Carbon::now()->subDays(7)->format('Y-m-d H:i:s');
        $dataFinal = Carbon::now()->format('Y-m-d H:i:s');

        // Buscar todas as apostas do evento
        $query = DB::table('SportBetSummary')
            ->select([
                'id',
                'transactionId',
                'user_id',
                'provider',
                'status',
                'amount',
                'betslip',
                'created_at'
            ])
            ->where('provider', 'betby')
            ->where('status', 'pending')
            ->whereBetween('created_at', [$dataInicial, $dataFinal])
            ->orderByDesc('id');

        $apostas = $query->get();

        // Agrupar por mercado (market_name + outcome_name)
        $mercadosMap = [];

        foreach ($apostas as $aposta) {
            if (!$aposta->betslip) continue;

            try {
                $betslipData = json_decode($aposta->betslip, true);
                if (!is_array($betslipData)) continue;

                // Buscar bets no JSON
                $bets = null;
                if (isset($betslipData['betslip']['bets']) && is_array($betslipData['betslip']['bets'])) {
                    $bets = $betslipData['betslip']['bets'];
                } elseif (isset($betslipData['bets']) && is_array($betslipData['bets'])) {
                    $bets = $betslipData['bets'];
                } elseif (is_array($betslipData) && isset($betslipData[0])) {
                    $bets = $betslipData;
                }

                if (!$bets) continue;

                // Processar cada bet
                foreach ($bets as $bet) {
                    // Verificar se é do evento correto
                    if (!isset($bet['event_id']) || $bet['event_id'] != $event_id) continue;

                    $marketName = $bet['market_name'] ?? 'N/A';
                    $outcomeName = $bet['outcome_name'] ?? 'N/A';

                    // Chave única para agrupar
                    $chaveGrupo = $marketName . '|' . $outcomeName;

                    // Agrupar por mercado + resultado
                    if (!isset($mercadosMap[$chaveGrupo])) {
                        $mercadosMap[$chaveGrupo] = [
                            'tipo_aposta' => $marketName,
                            'opcao' => $outcomeName,
                            'quantidade' => 0,
                            'valor_apostado' => 0
                        ];
                    }

                    // Somar valores - dividir por 100 pois valores da Betby estão em centavos
                    $mercadosMap[$chaveGrupo]['quantidade']++;
                    $mercadosMap[$chaveGrupo]['valor_apostado'] += ((float) $aposta->amount / 100);
                }

            } catch (\Exception $e) {
                // Log do erro se necessário, mas continua processando
                continue;
            }
        }

        // Converter para array e ordenar por valor apostado (maior primeiro)
        $mercados = array_values($mercadosMap);

        // Ordenar por valor apostado descendente
        usort($mercados, function($a, $b) {
            return $b['valor_apostado'] <=> $a['valor_apostado'];
        });

        return datatables()
            ->of(collect($mercados))
            ->addColumn('tipo_aposta', function ($mercado) {
                return $mercado['tipo_aposta'];
            })
            ->addColumn('opcao', function ($mercado) {
                return $mercado['opcao'];
            })
            ->addColumn('quantidade', function ($mercado) {
                return $mercado['quantidade'];
            })
            ->addColumn('valor_apostado', function ($mercado) {
                return 'R$ ' . number_format($mercado['valor_apostado'], 2, ',', '.');
            })
            ->make(true);
    }

    /**
     * Busca o resultado de uma transação específica (lazy loading)
     * Para Betby: buscar registros com operation diferente de 'make'
     */
    private function getTransactionResult($transactionId)
    {
        static $resultCache = [];

        if (!isset($resultCache[$transactionId])) {
            $resultCache[$transactionId] = DB::table('SportBetSummary')
                ->where('transactionId', $transactionId)
                ->where('provider', 'betby')
                ->orderBy('created_at', 'desc')
                ->first();
        }

        return $resultCache[$transactionId];
    }

    /**
     * Calcula a odd baseada no JSON do betslip da Betby
     * Usa a mesma lógica do JavaScript betby-bet-view.js
     */
    private function calculateOdd($row)
    {
        try {
            if (!$row->betslip) {
                return 1.0;
            }

            $betslipData = json_decode($row->betslip, true);

            if (!is_array($betslipData)) {
                return 1.0;
            }

            // Prioridade 1: betslip.k (odd total já calculada pela Betby)
            if (isset($betslipData['betslip']['k'])) {
                return (float) $betslipData['betslip']['k'];
            }

            // Prioridade 2: k diretamente no root do objeto
            if (isset($betslipData['k'])) {
                return (float) $betslipData['k'];
            }

            // Prioridade 3: calcular multiplicando odds individuais das apostas
            $bets = null;

            // Buscar apostas em betslipData.betslip.bets
            if (isset($betslipData['betslip']['bets']) && is_array($betslipData['betslip']['bets'])) {
                $bets = $betslipData['betslip']['bets'];
            }
            // Fallback: buscar em betslipData.bets
            elseif (isset($betslipData['bets']) && is_array($betslipData['bets'])) {
                $bets = $betslipData['bets'];
            }
            // Se for array direto
            elseif (is_array($betslipData) && isset($betslipData[0]['odds'])) {
                $bets = $betslipData;
            }
            // Objeto único com odds
            elseif (isset($betslipData['odds'])) {
                return (float) $betslipData['odds'];
            }

            // Calcular odds total multiplicando todas as odds
            if ($bets && is_array($bets) && count($bets) > 0) {
                $totalOdd = 1.0;
                foreach ($bets as $bet) {
                    if (isset($bet['odds'])) {
                        $oddValue = (float) $bet['odds'];
                        if ($oddValue > 0) {
                            $totalOdd *= $oddValue;
                        }
                    }
                }
                return $totalOdd > 1.0 ? $totalOdd : 1.0;
            }

            return 1.0; // Fallback final
        } catch (\Exception $e) {
            \Log::error('Erro ao calcular odd Betby: ' . $e->getMessage());
            return 1.0;
        }
    }

    /**
     * Calcula o possível ganho baseado no JSON do betslip da Betby
     * Busca especificamente o campo "potential_win" no betslip
     */
    private function calculatePossibleWinFromBetslip($row)
    {
        try {
            if ($row->betslip) {
                $betslipData = json_decode($row->betslip, true);

                if (is_array($betslipData)) {
                    // Betby: buscar potential_win diretamente no JSON
                    if (isset($betslipData['potential_win'])) {
                        return (float) $betslipData['potential_win'];
                    }

                    // Buscar em estruturas aninhadas se necessário
                    if (isset($betslipData['betslip']['potential_win'])) {
                        return (float) $betslipData['betslip']['potential_win'];
                    }

                    // Outras possíveis estruturas
                    if (isset($betslipData['betslip']) && is_array($betslipData['betslip'])) {
                        foreach ($betslipData['betslip'] as $key => $value) {
                            if ($key === 'potential_win') {
                                return (float) $value;
                            }
                        }
                    }
                }
            }

            // Fallback: usar amount_win da tabela
            return (float) ($row->amount_win ?? 0);
        } catch (\Exception $e) {
            // Em caso de erro, usar amount_win da tabela
            return (float) ($row->amount_win ?? 0);
        }
    }

    /**
     * Calcula o possível ganho baseado no JSON do betslip da Betby (método legado)
     */
    private function calculatePossibleWin($row)
    {
        return $this->calculatePossibleWinFromBetslip($row);
    }

    /**
     * Busca informações do evento baseado no event_id
     */
    private function getEventoInfo($event_id)
    {
        // Data dos últimos 7 dias
        $dataInicial = Carbon::now()->subDays(7)->format('Y-m-d H:i:s');
        $dataFinal = Carbon::now()->format('Y-m-d H:i:s');

        // Buscar todas as apostas do evento
        $query = DB::table('SportBetSummary')
            ->select(['betslip', 'amount'])
            ->where('provider', 'betby')
            ->where('status', 'pending')
            ->whereBetween('created_at', [$dataInicial, $dataFinal])
            ->orderByDesc('id');

        $apostas = $query->get();

        $eventoInfo = [
            'partida' => 'N/A',
            'data' => 'N/A',
            'quantidade_apostas' => 0,
            'total_apostado' => 0
        ];

        $totalApostado = 0;
        $quantidadeTotal = 0;

        foreach ($apostas as $aposta) {
            if (!$aposta->betslip) continue;

            try {
                $betslipData = json_decode($aposta->betslip, true);
                if (!is_array($betslipData)) continue;

                // Buscar bets no JSON
                $bets = null;
                if (isset($betslipData['betslip']['bets']) && is_array($betslipData['betslip']['bets'])) {
                    $bets = $betslipData['betslip']['bets'];
                } elseif (isset($betslipData['bets']) && is_array($betslipData['bets'])) {
                    $bets = $betslipData['bets'];
                } elseif (is_array($betslipData) && isset($betslipData[0])) {
                    $bets = $betslipData;
                }

                if (!$bets) continue;

                // Verificar se esta aposta contém o evento
                $contemEvento = false;
                foreach ($bets as $bet) {
                    if (isset($bet['event_id']) && $bet['event_id'] == $event_id) {
                        $contemEvento = true;

                        // Primeira vez que encontramos o evento - extrair informações básicas
                        if ($eventoInfo['partida'] === 'N/A') {
                            $competitors = $bet['competitor_name'] ?? [];
                            if (is_array($competitors) && count($competitors) >= 2) {
                                // Manter ordem original: primeiro time (casa) vs segundo time (visitante)
                                $eventoInfo['partida'] = implode(' vs ', $competitors);
                            }

                            // Formatar data
                            if (isset($bet['scheduled'])) {
                                $timestamp = (int) $bet['scheduled'];
                                $eventoInfo['data'] = date('d/m/Y H:i', $timestamp);
                            }
                        }
                        break; // Encontrou o evento, não precisa continuar verificando as outras bets desta aposta
                    }
                }

                // Se esta aposta contém o evento, contar valor e quantidade
                if ($contemEvento) {
                    $totalApostado += ((float) $aposta->amount / 100); // Converter de centavos
                    $quantidadeTotal++;
                }

            } catch (\Exception $e) {
                continue;
            }
        }

        $eventoInfo['total_apostado'] = $totalApostado;
        $eventoInfo['quantidade_apostas'] = $quantidadeTotal;

        return $eventoInfo;
    }

    /**
     * Exibe a página de gerenciamento de riscos
     */
    public function gerenciamentoRiscos(Request $request)
    {
        return view('admin.sportsBetby.gerenciamento_riscos');
    }

    /**
     * Fornece dados para o gerenciamento de riscos
     */
    public function gerenciamentoRiscosData(Request $request)
    {
        // Parâmetros de filtro
        $ordenarPor = $request->input('ordenarPor', 'possivel_retorno'); // padrão
        $nomeUsuario = $request->input('nomeUsuario', '');

        // Log para debug
        \Log::info('Gerenciamento Riscos - Ordenação solicitada: ' . $ordenarPor);

        // Buscar apostas betby pendentes
        $query = DB::table('SportBetSummary as s1')
            ->select([
                's1.id',
                's1.transactionId',
                's1.user_id',
                's1.provider',
                's1.status',
                's1.amount',
                's1.betslip',
                's1.amount_win',
                's1.created_at'
            ])
            ->where('s1.provider', 'betby')
            ->where('s1.status', 'pending');

        // Aplicar ordenação baseada no filtro usando a estrutura real do betslip
        switch ($ordenarPor) {
            case 'possivel_retorno':
                // Ordenar por potential_win (root) ou calcular com betslip.k
                $query->orderByRaw("
                    COALESCE(
                        CAST(JSON_EXTRACT(betslip, '$.potential_win') AS UNSIGNED),
                        amount * CAST(JSON_EXTRACT(betslip, '$.betslip.k') AS DECIMAL(10,2)),
                        0
                    ) DESC
                ");
                break;
            case 'quantidade_bilhete':
            case 'quantidade_apostas_bilhete':
                // Ordenar por quantidade de bets em betslip.bets
                $query->orderByRaw("
                    COALESCE(
                        JSON_LENGTH(JSON_EXTRACT(betslip, '$.betslip.bets')),
                        0
                    ) DESC
                ");
                break;
            case 'valor_apostado':
                $query->orderBy('amount', 'DESC');
                break;
            case 'quantidade_apostas_aberto':
                // Ordenar por tipo de aposta em betslip.type
                $query->orderByRaw("
                    CAST(SUBSTRING_INDEX(
                        COALESCE(JSON_EXTRACT(betslip, '$.betslip.type'), '0/0'),
                        '/', 1
                    ) AS UNSIGNED) DESC
                ");
                break;
            case 'odds':
                // Ordenar por odds em betslip.k
                $query->orderByRaw("
                    COALESCE(
                        CAST(JSON_EXTRACT(betslip, '$.betslip.k') AS DECIMAL(10,2)),
                        1.0
                    ) DESC
                ");
                break;
            default:
                $query->orderBy('id', 'DESC');
        }

        // Filtro por nome do usuário
        if (!empty($nomeUsuario)) {
            $usuariosIds = User::where('name', 'like', '%' . $nomeUsuario . '%')
                ->pluck('id')
                ->toArray();

            if (!empty($usuariosIds)) {
                $query->whereIn('s1.user_id', $usuariosIds);
            } else {
                return datatables()->of(collect([]))->make(true);
            }
        }

        try {
            return datatables()
                ->query($query)
                ->addColumn('usuario', function ($row) {
                    static $userCache = [];
                    if (!isset($userCache[$row->user_id])) {
                        $user = User::find($row->user_id);
                        if ($user) {
                            $ranking = $user->getRanking();
                            $rankingHtml = '';
                            if ($ranking && !empty($ranking['image'])) {
                                $rankingHtml = '<img src="' . asset($ranking['image']) . '" class="ranking-img me-2" width="25" height="25" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $ranking['name'] . '">';
                            }
                            $nomeHtml = '<a href="javascript:void(0);" onclick="LoadAgent(\'' . $user->id . '\');" data-bs-toggle="modal" data-bs-target="#tabsModal" class="bs-tooltip" title="Visualizar Usuário">' . $user->name . '</a>';
                            $userCache[$row->user_id] = $rankingHtml . $nomeHtml;
                        } else {
                            $userCache[$row->user_id] = 'Usuário não encontrado';
                        }
                    }
                    return $userCache[$row->user_id];
                })
                ->addColumn('conferir_bilhetes', function ($row) {
                    return '<button type="button" class="btn btn-info btn-sm ver-aposta-btn"
                                data-transaction-id="' . $row->transactionId . '"
                                data-betslip=\'' . htmlspecialchars($row->betslip, ENT_QUOTES, 'UTF-8') . '\'
                                data-operation="' . ($row->status ?? 'pending') . '"
                                data-amount="' . $row->amount . '"
                                data-received-amount="' . ($row->amount_win ?? 0) . '"
                                data-user-name="' . (User::find($row->user_id)->name ?? 'N/A') . '"
                                data-user-id="' . $row->user_id . '"
                                data-has-cashout="0">
                                Conferir Bilhetes
                            </button>';
                })
                ->addColumn('valor_apostado', function ($row) {
                    $valorAposta = ($row->amount / 100); // Converter de centavos
                    return 'R$ ' . number_format($valorAposta, 2, ',', '.');
                })
                ->addColumn('possivel_retorno', function ($row) {
                    try {
                        if (!$row->betslip) return 'R$ 0,00';

                        $betslipData = json_decode($row->betslip, true);
                        if (!is_array($betslipData)) return 'R$ 0,00';

                        // Buscar potential_win no JSON baseado na estrutura real
                        $potentialWin = 0;

                        // Prioridade 1: potential_win diretamente no root (estrutura correta da Betby)
                        if (isset($betslipData['potential_win']) && is_numeric($betslipData['potential_win'])) {
                            $potentialWin = (float) $betslipData['potential_win'] / 100; // Converter de centavos
                        }
                        // Prioridade 2: calcular baseado no valor apostado e odds do betslip
                        elseif ($row->amount && isset($betslipData['betslip']['k']) && is_numeric($betslipData['betslip']['k'])) {
                            $potentialWin = ((float) $row->amount * (float) $betslipData['betslip']['k']) / 100; // Converter de centavos
                        }
                        // Prioridade 3: fallback para outras estruturas
                        elseif (isset($betslipData['betslip']['potential_win']) && is_numeric($betslipData['betslip']['potential_win'])) {
                            $potentialWin = (float) $betslipData['betslip']['potential_win'] / 100;
                        }

                        return 'R$ ' . number_format($potentialWin, 2, ',', '.');
                    } catch (\Exception $e) {
                        return 'R$ 0,00';
                    }
                })
                ->addColumn('apostas_em_aberto', function ($row) {
                    try {
                        if (!$row->betslip) return '0/0';

                        $betslipData = json_decode($row->betslip, true);
                        if (!is_array($betslipData)) return '0/0';

                        // Buscar type no JSON (ex: "1/1", "3/3") - está em betslip.type
                        $type = '0/0';
                        if (isset($betslipData['betslip']['type'])) {
                            $type = $betslipData['betslip']['type'];
                        } elseif (isset($betslipData['type'])) {
                            $type = $betslipData['type'];
                        }

                        return $type;
                    } catch (\Exception $e) {
                        return '0/0';
                    }
                })
                ->addColumn('quantidade_apostas_bilhete', function ($row) {
                    try {
                        if (!$row->betslip) return 0;

                        $betslipData = json_decode($row->betslip, true);
                        if (!is_array($betslipData)) return 0;

                        // Contar bets no JSON - estão em betslip.bets
                        $count = 0;
                        if (isset($betslipData['betslip']['bets']) && is_array($betslipData['betslip']['bets'])) {
                            $count = count($betslipData['betslip']['bets']);
                        } elseif (isset($betslipData['bets']) && is_array($betslipData['bets'])) {
                            $count = count($betslipData['bets']);
                        }

                        return $count;
                    } catch (\Exception $e) {
                        return 0;
                    }
                })
                ->addColumn('odds', function ($row) {
                    try {
                        if (!$row->betslip) return '1,00';

                        $betslipData = json_decode($row->betslip, true);
                        if (!is_array($betslipData)) return '1,00';

                        // Buscar k (odds total) no JSON - está em betslip.k
                        $odds = 1.0;

                        // Prioridade 1: betslip.k (estrutura correta da Betby)
                        if (isset($betslipData['betslip']['k']) && is_numeric($betslipData['betslip']['k'])) {
                            $odds = (float) $betslipData['betslip']['k'];
                        }
                        // Prioridade 2: k diretamente no root (fallback)
                        elseif (isset($betslipData['k']) && is_numeric($betslipData['k'])) {
                            $odds = (float) $betslipData['k'];
                        }
                        // Prioridade 3: multiplicar odds individuais das apostas
                        else {
                            if (isset($betslipData['betslip']['bets']) && is_array($betslipData['betslip']['bets'])) {
                                $calculatedOdds = 1.0;
                                foreach ($betslipData['betslip']['bets'] as $bet) {
                                    if (isset($bet['odds']) && is_numeric($bet['odds'])) {
                                        $calculatedOdds *= (float) $bet['odds'];
                                    }
                                }
                                if ($calculatedOdds > 1.0) {
                                    $odds = $calculatedOdds;
                                }
                            }
                        }

                        return number_format($odds, 2, ',', '.');
                    } catch (\Exception $e) {
                        return '1,00';
                    }
                })

                ->rawColumns(['usuario', 'conferir_bilhetes'])
                ->make(true);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao carregar dados: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Retorna bilhetes específicos do evento para o DataTable
     */
    private function getBilhetesEvento($event_id)
    {
        // Data dos últimos 7 dias
        $dataInicial = Carbon::now()->subDays(7)->format('Y-m-d H:i:s');
        $dataFinal = Carbon::now()->format('Y-m-d H:i:s');

        // Query otimizada - buscar apenas apostas que contém o evento específico
        $query = DB::table('SportBetSummary as s1')
            ->select([
                's1.id',
                's1.transactionId',
                's1.user_id',
                's1.provider',
                's1.operation',
                's1.status',
                's1.amount',
                's1.betslip',
                's1.amount_win',
                's1.created_at'
            ])
            ->where('s1.provider', 'betby') // Filtrar apenas apostas Betby
            ->where('s1.status', 'pending') // Apenas pendentes
            ->whereBetween('s1.created_at', [$dataInicial, $dataFinal])
            ->orderByDesc('s1.id');

        try {
            return datatables()
                ->query($query)
                ->filter(function ($query) use ($event_id) {
                    // Filtrar no servidor apenas apostas que contém o event_id
                    $query->whereRaw("JSON_EXTRACT(betslip, '$[*].event_id') LIKE '%{$event_id}%'");
                })
                ->addColumn('usuario', function ($row) {
                    static $userCache = [];
                    if (!isset($userCache[$row->user_id])) {
                        $user = User::find($row->user_id);
                        if ($user) {
                            $ranking = $user->getRanking();
                            $rankingHtml = '';
                            if ($ranking && !empty($ranking['image'])) {
                                $rankingHtml = '<img src="' . asset($ranking['image']) . '" class="ranking-img me-2" width="25" height="25" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $ranking['name'] . '">';
                            }
                            $nomeHtml = '<a href="javascript:void(0);" onclick="LoadAgent(\'' . $user->id . '\');" data-bs-toggle="modal" data-bs-target="#tabsModal" class="bs-tooltip" title="Visualizar Usuário">' . $user->name . '</a>';
                            $userCache[$row->user_id] = $rankingHtml . $nomeHtml;
                        } else {
                            $userCache[$row->user_id] = 'Usuário não encontrado';
                        }
                    }
                    return $userCache[$row->user_id];
                })
                ->addColumn('id_transacao', function ($row) {
                    return $row->transactionId;
                })
                ->addColumn('status', function ($row) {
                    $status = $row->status ?? 'pending';
                    $statusClass = match($status) {
                        'pending' => 'warning',
                        'win' => 'success',
                        'lost' => 'danger',
                        'debit' => 'info',
                        'credit' => 'success',
                        default => 'secondary'
                    };

                    $statusText = match($status) {
                        'pending' => 'Pendente',
                        'win' => 'Ganho',
                        'lost' => 'Perdido',
                        'debit' => 'Aposta',
                        'credit' => 'Pago',
                        default => ucfirst($status)
                    };

                    return '<span class="badge bg-' . $statusClass . '">' . $statusText . '</span>';
                })
                ->addColumn('odd', function ($row) {
                    return number_format($this->calculateOdd($row), 2, ',', '.');
                })
                ->addColumn('valor', function ($row) {
                    $valorAposta = ($row->amount / 100); // Converter de centavos
                    return 'R$ ' . number_format($valorAposta, 2, ',', '.');
                })
                ->addColumn('possivel_ganho', function ($row) {
                    return 'R$ ' . number_format($this->calculatePossibleWinFromBetslip($row), 2, ',', '.');
                })
                ->addColumn('data', function ($row) {
                    return Carbon::parse($row->created_at)->format('d/m/Y H:i:s');
                })
                ->addColumn('acoes', function ($row) {
                    // Dados para o modal (mesma lógica do sportsApostasData)
                    $betslip = $row->betslip;
                    $operation = $row->status ?? 'pending';
                    $amount = $row->amount ?? 0;
                    $receivedAmount = $row->amount_win ?? 0;

                    // Verificar cashout - priorizar coluna cashout da tabela sportbetsummary
                    $hasCashout = false;
                    
                    // Primeiro, verificar a coluna cashout da tabela
                    if (isset($row->cashout) && ($row->cashout === 1 || $row->cashout === '1')) {
                        $hasCashout = true;
                    }
                    // Se não tiver na coluna, tentar no JSON betslip
                    elseif ($row->betslip) {
                        try {
                            $betslipData = json_decode($row->betslip, true);
                            if (is_array($betslipData) && isset($betslipData['is_cashout'])) {
                                $hasCashout = $betslipData['is_cashout'] === "1" || $betslipData['is_cashout'] === 1;
                            }
                        } catch (\Exception $e) {
                            // Continua sem cashout
                        }
                    }

                    $user = User::find($row->user_id);
                    $userName = $user ? $user->name : 'N/A';

                    // Buscar todas as operações desta transação para o histórico
                    $operacoes = DB::table('SportBetSummary')
                        ->where('transactionId', $row->transactionId)
                        ->where('provider', 'betby')
                        ->orderBy('created_at', 'desc')
                        ->limit(20)
                        ->get();

                    return '<button type="button" class="btn btn-primary btn-sm ver-aposta-btn"
                                data-transaction-id="' . $row->transactionId . '"
                                data-betslip=\'' . htmlspecialchars($betslip, ENT_QUOTES, 'UTF-8') . '\'
                                data-operation="' . $operation . '"
                                data-amount="' . $amount . '"
                                data-received-amount="' . $receivedAmount . '"
                                data-user-name="' . $userName . '"
                                data-user-id="' . $row->user_id . '"
                                data-cashout="' . ($row->cashout ?? 0) . '"
                                data-operacoes=\'' . htmlspecialchars(json_encode($operacoes), ENT_QUOTES, 'UTF-8') . '\'>
                                Ver Aposta
                            </button>';
                })
                ->rawColumns(['usuario', 'status', 'acoes'])
                ->make(true);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao carregar dados: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Retorna lista de esportes disponíveis para o filtro
     */
    private function getEsportesDisponiveis()
    {
        // Data dos últimos 7 dias para buscar esportes
        $dataInicial = Carbon::now()->subDays(7)->format('Y-m-d H:i:s');
        $dataFinal = Carbon::now()->format('Y-m-d H:i:s');

        // Query para buscar apostas betby pendentes
        $query = DB::table('SportBetSummary')
            ->select('betslip')
            ->where('provider', 'betby')
            ->where('status', 'pending')
            ->whereBetween('created_at', [$dataInicial, $dataFinal])
            ->orderByDesc('id');

        $apostas = $query->get();
        $esportes = [];

        foreach ($apostas as $aposta) {
            if (!$aposta->betslip) continue;

            try {
                $betslipData = json_decode($aposta->betslip, true);
                if (!is_array($betslipData)) continue;

                // Buscar bets no JSON
                $bets = null;
                if (isset($betslipData['betslip']['bets']) && is_array($betslipData['betslip']['bets'])) {
                    $bets = $betslipData['betslip']['bets'];
                } elseif (isset($betslipData['bets']) && is_array($betslipData['bets'])) {
                    $bets = $betslipData['bets'];
                } elseif (is_array($betslipData) && isset($betslipData[0])) {
                    $bets = $betslipData;
                }

                if (!$bets) continue;

                // Coletar esportes únicos
                foreach ($bets as $bet) {
                    if (isset($bet['sport_name'])) {
                        $sport = $bet['sport_name'];
                        if (!in_array($sport, $esportes)) {
                            $esportes[] = $sport;
                        }
                    }
                }

            } catch (\Exception $e) {
                continue;
            }
        }

        // Ordenar esportes alfabeticamente
        sort($esportes);

        return response()->json(['esportes' => $esportes]);
    }
}
