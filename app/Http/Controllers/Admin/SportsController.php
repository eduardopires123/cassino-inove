<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\CampeonatoOculto;
use App\Models\CategoriaOculta;
use Illuminate\Support\Facades\Schema;

use App\Models\Settings;

use App\Models\User;
use App\Models\SportBetSummary;

class SportsController extends Controller
{
    public function CancelSportBet(Request $request)
    {
        $settings = Settings::first();
        $aposta = $request->input('cid');

        $Checa = SportBetSummary::where('transactionId', $aposta)->whereIn('operation', ['credit', 'lose', 'cancel_debit', 'cancel_credit'])->first();

        if ($Checa) {
            return response()->json(['status' => false, 'message' => 'Essa aposta já foi atualizada!']);
        }

        $Info = SportBetSummary::where('transactionId', $aposta)->where('operation', 'debit')->first();

        if ($Info) {
            $Criou = SportBetSummary::create([
                'provider' => $settings->sports_api_provider,
                'user_id' => $Info->user_id,
                'transactionId' => $aposta,
                'operation' => 'lose',
                'dedn' => 0,
                'status' => 'Completed',
                'status_el' => 'Nil',
                'reason' => 'Nil',
                'amount' => 0.00,
                'amount_win' => 0.00,
                'stake' => 0.00,
                'transaction' => '{}',
                'betslip' => '{"is_cashout":false}'
            ]);

            if ($Criou) {
                return response()->json(['status' => true]);
            }
        }

        return response()->json(['status' => false, 'message' => 'Ocorreu um erro, tente novamente em alguns instantes.']);
    }
    /**
     * Exibe a página de apostas esportivas
     */
    public function sportsApostas(Request $request)
    {
        $a = $request->input('aff', '');
        $b = $request->input('di', Carbon::now()->format('Y-m-d')); // Sempre iniciar com hoje
        $c = $request->input('df', Carbon::now()->format('Y-m-d')); // Sempre iniciar com hoje

        return view('admin.sports.sports_apostas', compact('a', 'b', 'c'));
    }

    /**
     * Fornece dados para a tabela de apostas esportivas
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sportsApostasData(Request $request)
    {
        $dataInicial = $request->input('dataInicial', Carbon::now()->format('Y-m-d')); // Sempre iniciar com hoje
        $dataFinal = $request->input('dataFinal', Carbon::now()->format('Y-m-d')); // Sempre iniciar com hoje
        $nomeUsuario = $request->input('nomeUsuario', '');
        $statusFiltro = $request->input('statusFiltro', ''); // Novo filtro de status

        // Formatar datas para incluir horário
        $dataInicialFormatada = $dataInicial . ' 00:00:00';
        $dataFinalFormatada = $dataFinal . ' 23:59:59';

        $query = SportBetSummary::query();

        // Filtrar apenas apostas da Digitain
        $query->where('provider', 'digitain');

        if (!empty($dataInicial) && !empty($dataFinal)) {
            $query->whereBetween('created_at', [$dataInicialFormatada, $dataFinalFormatada]);
        } elseif (!empty($dataInicial)) {
            $query->where('created_at', '>=', $dataInicialFormatada);
        } elseif (!empty($dataFinal)) {
            $query->where('created_at', '<=', $dataFinalFormatada);
        }

        if (!empty($nomeUsuario)) {
            $usuariosIds = DB::table('users')
                ->where('name', 'like', '%' . $nomeUsuario . '%')
                ->pluck('id')
                ->toArray();

            if (!empty($usuariosIds)) {
                $query->whereIn('user_id', $usuariosIds);
            } else {
                return DataTables::of(collect([]))->make(true);
            }
        }

        if (!empty($statusFiltro)) {
            if ($statusFiltro === 'abertos') {
                $subQuery = clone $query;
                $transacoesComResultado = $subQuery
                    ->whereIn('operation', ['credit', 'lose', 'cancel_debit', 'cancel_credit'])
                    ->pluck('transactionId')
                    ->toArray();

                $query->where('operation', 'debit')
                    ->whereNotIn('transactionId', $transacoesComResultado);

            } elseif ($statusFiltro === 'finalizados') {
                $subQuery = clone $query;
                $transacoesFinalizadas = $subQuery
                    ->whereIn('operation', ['credit', 'lose', 'cancel_debit', 'cancel_credit'])
                    ->pluck('transactionId')
                    ->toArray();

                $query->whereIn('transactionId', $transacoesFinalizadas);
            }
        }

        $registros = $query->get();

        if ($registros->isEmpty()) {
            return DataTables::of(collect([]))->make(true);
        }

        $emaberto = 0;
        $finalizado = 0;
        $premmiado = 0;

        $registrosAgrupados = [];
        foreach ($registros as $item) {
            $transactionId = $item->transactionId;
            $userId = $item->user_id;

            if (empty($transactionId) || empty($userId)) {
                continue;
            }

            $chaveUnica = $transactionId . '_' . $userId;

            if (!isset($registrosAgrupados[$chaveUnica])) {
                $registrosAgrupados[$chaveUnica] = [
                    'user_id' => $userId,
                    'transactionId' => $transactionId,
                    'created_at' => $item->created_at,
                    'aposta' => null,
                    'resultado' => null
                ];
            }

            $operation = trim($item->operation);

            if ($operation === 'debit') {
                $registrosAgrupados[$chaveUnica]['aposta'] = $item;
            } else {
                $registrosAgrupados[$chaveUnica]['resultado'] = $item;
            }
        }

        foreach ($registrosAgrupados as &$registro) {
            if (!$registro['aposta']) {
                $Info = SportBetSummary::where('transactionId', $registro['transactionId'])
                    ->where('operation', 'debit')
                    ->where('provider', 'digitain')
                    ->first();

                $registro['aposta'] = $Info;
            }
        }
        unset($registro);

        $registrosFinal = array_values($registrosAgrupados);

        $start = intval($request->input('start', 0));
        $length = intval($request->input('length', 10));
        $draw = intval($request->input('draw', 1));

        $collection = collect($registrosFinal);
        $totalRecords = $collection->count();

        $sorted = collect($registrosFinal)->sortByDesc(function ($item) {
            return $item['created_at'] ?? null;
        });

        $dataPaginated = $sorted->slice($start, $length)->values();

        $mappedData = $dataPaginated->map(function ($row) {
            $idTransacao = '<span class="badge badge-light-info">' . ($row['transactionId'] ?? 'N/A') . '</span>';

            $usuario = \App\Models\User::find($row['user_id']);
            if ($usuario) {
                $ranking = $usuario->getRanking();
                $rankingHtml = '';
                if ($ranking && !empty($ranking['image'])) {
                    $rankingHtml = '<img src="' . asset($ranking['image']) . '" class="ranking-img me-2" width="25" height="25" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $ranking['name'] . '">';
                }
                $nomeHtml = '<a href="javascript:void(0);" onclick="LoadAgent(\'' . $usuario->id . '\');" data-bs-toggle="modal" data-bs-target="#tabsModal" class="bs-tooltip" title="Visualizar Usuário">' . $usuario->name . '</a>';
                $usuarioHtml = $rankingHtml . $nomeHtml;
            } else {
                $usuarioHtml = 'N/A';
            }

            // Status
            $aposta = $row['aposta'];
            $resultado = $row['resultado'];

            // Se não tem resultado, é um bilhete em aberto
            if (!$resultado) {
                $statusHtml = '<span class="badge badge-light-warning mb-2 me-4">Pendente</span>';
            }

            $status = strtolower($resultado->operation ?? '');

            $hasCashout = false;
            if ($status === 'credit') {
                if (isset($aposta->betslip)) {
                    $betslip = json_decode($resultado->betslip, true);
                    if (is_array($betslip) && isset($betslip['is_cashout'])) {
                        $hasCashout = $betslip['is_cashout'] === true;
                    }
                }

                if ($hasCashout) {
                    $statusHtml = '<span class="badge badge-light-primary mb-2 me-4">Cashout</span>';
                } else {
                    $statusHtml = '<span class="badge badge-light-success mb-2 me-4">Ganhou</span>';
                }
            } elseif ($status === 'cancel_debit') {
                $statusHtml = '<span class="badge badge-light-warning mb-2 me-4">Cancelamento de Aposta</span>';
            } elseif ($status === 'cancel_credit') {
                $statusHtml = '<span class="badge badge-light-warning mb-2 me-4">Cancelamento de Crédito</span>';
            } elseif ($status === 'lose') {
                $statusHtml = '<span class="badge badge-light-danger mb-2 me-4">Perdeu</span>';
            } else {
                $statusHtml = '<span class="badge badge-light-secondary mb-2 me-4">Pendente</span>';
            }

            // Odd
            $odd = 0;

            if (isset($aposta->betslip)) {
                $betslip = json_decode($aposta->betslip, true);

                if (isset($betslip['bet_stakes']['Factor'])) {
                    $odd = $betslip['bet_stakes']['Factor'];
                }
            }

            $oddHtml = '<span class="badge badge-light-success">' . number_format($odd, 2, '.', '') . '</span>';

            // Valor
            $aposta = $row['aposta'] ?? null;
            $resultado = $row['resultado'] ?? null;

            if ($hasCashout) {
                $valor = $resultado->amount;
            }else{
                $valor = ($aposta->amount ?? $resultado->amount);
            }

            $valorHtml = 'R$ ' . number_format($valor, 2, ',', '.');

            // Possivel Ganho
            $aposta = $row['aposta'] ?? null;
            $possivel_ganho = 0;

            if (isset($aposta->betslip)) {
                $betslip = json_decode($aposta->betslip, true);

                if (isset($betslip['bet_stakes']['MaxWinAmount'])) {
                    $possivel_ganho = $betslip['bet_stakes']['MaxWinAmount'];
                }
            }

            // Retornar o valor formatado com R$
            $possivelGanhoHtml = 'R$ ' . number_format($possivel_ganho, 2, ',', '.');

            $aposta = $row['aposta'] ?? null;
            $resultado = $row['resultado'] ?? null;

            if (!empty($aposta) || !empty($resultado)) {
                $betslip = $aposta ? $aposta->betslip : ($resultado ? $resultado->betslip : '');
                $operation = $resultado ? $resultado->operation : ($aposta ? $aposta->operation : '');
                $amount = $aposta ? $aposta->amount : 0;

                // Buscar nome do usuário
                $usuario = DB::table('users')->where('id', $row['user_id'])->first();
                $userName = $usuario ? $usuario->name : 'N/A';

                $acoesHtml = '<button type="button" class="btn btn-primary btn-sm ver-aposta"
                            data-bs-toggle="modal"
                            data-bs-target="#verApostaModal"
                            data-betslip="' . htmlspecialchars($betslip ?: '{}', ENT_QUOTES, 'UTF-8') . '"
                            data-operation="' . htmlspecialchars($operation, ENT_QUOTES, 'UTF-8') . '"
                            data-amount="' . $amount . '"
                            data-cashout="' . ($hasCashout ? '1' : '0') . '"
                            data-received-amount="' . ($resultado ? $resultado->amount : '0') . '"
                            data-user-id="' . $row['user_id'] . '"
                            data-user-name="' . htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') . '">
                            Ver Aposta
                        </button>

                        <button type="button" class="btn btn-danger btn-sm" onclick="CancelSportBet('.$row['transactionId'].')">Cancelar</button>';
            } else {
                $acoesHtml = '<span class="badge badge-light-dark">Sem dados</span>';
            }

            $dataFormatada = !empty($row['created_at']) ? Carbon::parse($row['created_at'])->format('d/m/Y H:i:s') : 'N/A';

            return [
                'id_transacao' => $idTransacao,
                'usuario' => $usuarioHtml,
                'status' => $statusHtml,
                'odd' => $oddHtml,
                'valor' => $valorHtml,
                'possivel_ganho' => $possivelGanhoHtml,
                'data' => $dataFormatada,
                'acoes' => $acoesHtml,
            ];
        });

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $mappedData->toArray(),
        ]);
    }

    /**
     * Exibe a página de estatísticas esportivas
     */
    public function sportsEstatisticas(Request $request)
    {
        $dataInicial = $request->input('dataInicial', Carbon::now()->format('Y-m-d')); // Hoje
        $dataFinal = $request->input('dataFinal', Carbon::now()->format('Y-m-d')); // Hoje

        // Processar estatísticas de forma otimizada
        $stats = $this->processarEstatisticasDigitain($dataInicial, $dataFinal);

        return view('admin.sports.sports_estatisticas', $stats);
    }

    /**
     * Processa estatísticas Digitain de forma otimizada (sem N+1 queries)
     */
    private function processarEstatisticasDigitain($dataInicial, $dataFinal)
    {
        $dataInicialFormatada = $dataInicial . ' 00:00:00';
        $dataFinalFormatada = $dataFinal . ' 23:59:59';

        // OTIMIZADO: Buscar transactionIds finalizados primeiro
        $transacoesFinalizadas = SportBetSummary::where('provider', 'digitain')
            ->whereBetween('created_at', [$dataInicialFormatada, $dataFinalFormatada])
            ->whereIn('operation', ['credit', 'lose', 'cancel_debit', 'cancel_credit'])
            ->pluck('transactionId')
            ->unique()
            ->toArray();

        // Total de apostas (debit)
        $totalApostasDireto = SportBetSummary::where('provider', 'digitain')
            ->whereBetween('created_at', [$dataInicialFormatada, $dataFinalFormatada])
            ->where('operation', 'debit')
            ->sum('amount');

        // Total de prêmios (credit)
        $totalpremiado = SportBetSummary::where('provider', 'digitain')
            ->whereBetween('created_at', [$dataInicialFormatada, $dataFinalFormatada])
            ->where('operation', 'credit')
            ->sum('amount');

        // Calcular líquido
        $totalLiquido = $totalApostasDireto - $totalpremiado;

        // Buscar todas as apostas (debit) para processar gráficos e esportes
        $apostas = SportBetSummary::where('provider', 'digitain')
            ->whereBetween('created_at', [$dataInicialFormatada, $dataFinalFormatada])
            ->where('operation', 'debit')
            ->get(['transactionId', 'user_id', 'amount', 'betslip', 'created_at']);

        // Buscar todos os resultados (credit/lose) de uma vez
        $resultados = SportBetSummary::where('provider', 'digitain')
            ->whereBetween('created_at', [$dataInicialFormatada, $dataFinalFormatada])
            ->whereIn('operation', ['credit', 'lose'])
            ->get(['transactionId', 'user_id', 'operation', 'amount'])
            ->groupBy(function($item) {
                return $item->transactionId . '_' . $item->user_id;
            });

        // Preparar arrays para gráficos
        $datas = [];
        $esportes = [];

        // Criar mapa de datas do período
        $period = Carbon::parse($dataInicial)->daysUntil(Carbon::parse($dataFinal)->addDay());
        $mapaDataParaIndice = [];
        $datasFormatadas = [];
        $indice = 0;

        foreach ($period as $date) {
            $dataKey = $date->format('Y-m-d');
            $mapaDataParaIndice[$dataKey] = $indice;
            $datasFormatadas[$indice] = $date->format('d/m/Y');
            $datas[$indice] = ['apostas' => 0, 'premios' => 0];
            $indice++;
        }

        // Processar apostas
        foreach ($apostas as $aposta) {
            $chaveUnica = $aposta->transactionId . '_' . $aposta->user_id;
            $dataKey = $aposta->created_at->format('Y-m-d');

            if (!isset($mapaDataParaIndice[$dataKey])) continue;

            $idx = $mapaDataParaIndice[$dataKey];

            // SEMPRE adicionar o valor da aposta (debit)
            $datas[$idx]['apostas'] += $aposta->amount;

            // Extrair esporte do betslip
            $betslip = json_decode($aposta->betslip, true);
            $esporte = "Outro";

            if (is_array($betslip) && isset($betslip['bet_stakes']['BetStakes'])) {
                foreach ($betslip['bet_stakes']['BetStakes'] as $stake) {
                    if (isset($stake['SportName'])) {
                        $sport = $stake['SportName'];
                        $category = $stake['CategoryName'] ?? '';
                        $esporte = $sport . ($category ? " ({$category})" : "");
                        break;
                    }
                }
            }

            if (!isset($esportes[$esporte])) {
                $esportes[$esporte] = ['apostas' => 0, 'premios' => 0, 'lucro' => 0, 'percentual' => 0];
            }

            // SEMPRE adicionar valor da aposta ao esporte
            $esportes[$esporte]['apostas'] += $aposta->amount;

            // Verificar se tem resultado (prêmio)
            if (isset($resultados[$chaveUnica])) {
                $resultado = $resultados[$chaveUnica]->first();

                if ($resultado->operation === 'credit') {
                    // Ganhou - adicionar prêmio
                    $datas[$idx]['premios'] += $resultado->amount;
                    $esportes[$esporte]['premios'] += $resultado->amount;
                }
                // Se for 'lose', não adiciona nada aos prêmios (já foi contabilizado nas apostas)
            }
            // Se não tem resultado (em aberto), também não adiciona nada aos prêmios
        }

        // Preparar séries para gráfico
        $teste = [];
        $teste2 = [];
        $teste3 = [];
        $labels = [];

        foreach ($datas as $idx => $valores) {
            $teste[$idx] = $valores['apostas'];
            $teste2[$idx] = $valores['premios'];
            $teste3[$idx] = $valores['apostas'] - $valores['premios'];
            $labels[$idx] = $datasFormatadas[$idx];
        }

        // Calcular lucro e percentual dos esportes
        foreach ($esportes as &$row) {
            $row['lucro'] = $row['apostas'] - $row['premios'];
            $row['percentual'] = $row['apostas'] > 0 ? round(($row['lucro'] / $row['apostas']) * 100, 2) : 0;
        }
        unset($row);

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

        $esporteLabels = json_encode(array_keys($esportes));
        $esporteData = json_encode(array_column($esportes, 'apostas'));

        return [
            'dataInicial' => $dataInicial,
            'dataFinal' => $dataFinal,
            'totalApostasDireto' => $totalApostasDireto,
            'totalPremios' => $totalpremiado,
            'totalLiquido' => $totalLiquido,
            'series' => $series,
            'labels' => array_values($labels),
            'esportes' => $esportes,
            'esporteLabels' => $esporteLabels,
            'esporteData' => $esporteData,
        ];
    }

    /**
     * Exibe a página de configurações de apostas esportivas
     */
    public function sportsConfiguracoes()
    {
        return view('admin.sports.sports_configuracoes');
    }

    /**
     * Salvar configurações de esportes
     */
    public function salvarConfiguracoes(Request $request)
    {
        // Implementação de salvamento de configurações de esportes
        return redirect()->back()->with('success', 'Configurações salvas com sucesso!');
    }

    /**
     * Exibe a página de campeonatos ocultos
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function campeonatosOcultos()
    {
        return view('admin.sports.sports_ocultos');
    }

    /**
     * Lista todos os campeonatos do sportsbook
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function listarCampeonatos(Request $request)
    {
        try {
            $tipoEsporte = $request->input('tipoEsporte', '');

            // Aqui você faria a busca real nos arquivos ou no iframe, mas para simplificar
            // vamos usar dados do arquivo de backup para extrair os campeonatos disponíveis
            $html = file_get_contents(resource_path('views/admin/sports/backup.php'));

            // Regex para encontrar os IDs e nomes dos campeonatos
            preg_match_all('/class=".*?champid(\d+).*?<span.*?title="([^"]+)"/', $html, $matches, PREG_SET_ORDER);

            $campeonatos = [];
            foreach ($matches as $match) {
                // Verifica se campeonato já está na lista para evitar duplicados
                $id = $match[1];
                $nome = $match[2];

                // Determina o tipo de esporte com base no nome
                $typeEsporte = $this->determinarTipoEsporte($nome);

                // Verifica se o filtro de tipo de esporte está sendo aplicado
                if (!empty($tipoEsporte) && $typeEsporte !== $tipoEsporte) {
                    continue;
                }

                // Verifica se já existe na lista
                $existe = false;
                foreach ($campeonatos as $camp) {
                    if ($camp['id'] == $id) {
                        $existe = true;
                        break;
                    }
                }

                if (!$existe) {
                    $campeonatos[] = [
                        'id' => $id,
                        'nome' => $nome,
                        'tipoEsporte' => $typeEsporte
                    ];
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Campeonatos carregados com sucesso',
                'campeonatos' => $campeonatos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao listar campeonatos: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Salva um campeonato na lista de ocultos
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function salvarCampeonatoOculto(Request $request)
    {
        try {
            $id = $request->input('id');
            $nome = $request->input('nome');
            $tipoEsporte = $request->input('tipoEsporte', '1');

            if (empty($id) || empty($nome)) {
                return response()->json([
                    'status' => false,
                    'message' => 'ID e nome do campeonato são obrigatórios'
                ]);
            }

            // Verifica se já existe no banco
            $campeonato = CampeonatoOculto::where('campeonato_id', $id)->first();

            if ($campeonato) {
                return response()->json([
                    'status' => false,
                    'message' => 'Este campeonato já está na lista de ocultos'
                ]);
            }

            // Salva no banco
            $campeonato = new CampeonatoOculto();
            $campeonato->campeonato_id = $id;
            $campeonato->nome = $nome;
            $campeonato->status = 'Oculto';
            $campeonato->tipo_esporte = $tipoEsporte;
            $campeonato->save();

            // Adiciona CSS para ocultar o campeonato em sports.css e mobile.css
            $this->atualizarArquivoCSS('sports.css', $id, true);
            $this->atualizarArquivoCSS('mobile.css', $id, true);

            return response()->json([
                'status' => true,
                'message' => 'Campeonato salvo com sucesso na lista de ocultos'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao salvar campeonato: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Muda o status de um campeonato (oculto/visível)
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function mudarStatusCampeonato(Request $request)
    {
        try {
            $id = $request->input('id');
            $status = $request->input('status');

            if (empty($id) || empty($status)) {
                return response()->json([
                    'status' => false,
                    'message' => 'ID e status são obrigatórios'
                ]);
            }

            // Verifica se existe no banco
            $campeonato = CampeonatoOculto::where('campeonato_id', $id)->first();

            if (!$campeonato) {
                return response()->json([
                    'status' => false,
                    'message' => 'Campeonato não encontrado'
                ]);
            }

            // Atualiza status
            $campeonato->status = $status;
            $campeonato->save();

            // Atualiza CSS para ocultar/mostrar o campeonato
            $ocultar = ($status === 'Oculto');
            $this->atualizarArquivoCSS('sports.css', $id, $ocultar);
            $this->atualizarArquivoCSS('mobile.css', $id, $ocultar);

            return response()->json([
                'status' => true,
                'message' => 'Status do campeonato atualizado com sucesso'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao atualizar status: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Remove um campeonato da lista de ocultos
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removerCampeonatoOculto(Request $request)
    {
        try {
            $id = $request->input('id');

            if (empty($id)) {
                return response()->json([
                    'status' => false,
                    'message' => 'ID do campeonato é obrigatório'
                ]);
            }

            // Verifica se existe no banco
            $campeonato = CampeonatoOculto::where('campeonato_id', $id)->first();

            if (!$campeonato) {
                return response()->json([
                    'status' => false,
                    'message' => 'Campeonato não encontrado'
                ]);
            }

            // Remove do banco
            $campeonato->delete();

            // Remove CSS para ocultar o campeonato
            $this->atualizarArquivoCSS('sports.css', $id, false);
            $this->atualizarArquivoCSS('mobile.css', $id, false);

            return response()->json([
                'status' => true,
                'message' => 'Campeonato removido com sucesso da lista de ocultos'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao remover campeonato: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtém a lista de campeonatos ocultos
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function obterCampeonatosOcultos()
    {
        try {
            $campeonatos = CampeonatoOculto::all()->map(function ($item) {
                return [
                    'id' => $item->campeonato_id,
                    'nome' => $item->nome,
                    'status' => $item->status,
                    'tipoEsporte' => $item->tipo_esporte
                ];
            });

            return response()->json([
                'status' => true,
                'message' => 'Campeonatos ocultos carregados com sucesso',
                'campeonatos' => $campeonatos
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao obter campeonatos ocultos: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Atualiza arquivo CSS adicionando ou removendo regra para ocultar campeonato
     *
     * @param string $arquivo Nome do arquivo CSS (sports.css ou mobile.css)
     * @param string $id ID do campeonato
     * @param bool $ocultar True para ocultar, False para mostrar
     * @return bool
     */
    private function atualizarArquivoCSS($arquivo, $id, $ocultar)
    {
        try {
            $caminho = public_path('css/' . $arquivo);
            $conteudo = file_get_contents($caminho);

            $regra = ".champid{$id}{display:none!important}";

            if ($ocultar) {
                // Se a regra já existe, não faz nada
                if (strpos($conteudo, $regra) === false) {
                    // Adiciona a regra no final do arquivo
                    $conteudo .= "\n" . $regra;
                    file_put_contents($caminho, $conteudo);
                }
            } else {
                // Remove a regra se existir
                $conteudo = str_replace($regra, '', $conteudo);
                $conteudo = str_replace("\n\n", "\n", $conteudo); // Remove linhas vazias duplicadas
                file_put_contents($caminho, $conteudo);
            }

            return true;
        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar arquivo CSS: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Determina o tipo de esporte com base no nome do campeonato
     *
     * @param string $nome Nome do campeonato
     * @return string Código do tipo de esporte
     */
    private function determinarTipoEsporte($nome)
    {
        $nome = strtolower($nome);

        // Futebol
        if (strpos($nome, 'futebol') !== false ||
            strpos($nome, 'campeonato') !== false ||
            strpos($nome, 'liga') !== false ||
            strpos($nome, 'copa') !== false ||
            strpos($nome, 'uefa') !== false ||
            strpos($nome, 'primera') !== false ||
            strpos($nome, 'serie') !== false ||
            strpos($nome, 'division') !== false ||
            strpos($nome, 'bundesliga') !== false ||
            strpos($nome, 'premier') !== false) {
            return '1'; // Futebol
        }

        // Basquete
        if (strpos($nome, 'basquete') !== false ||
            strpos($nome, 'basketball') !== false ||
            strpos($nome, 'nba') !== false ||
            strpos($nome, 'euroliga') !== false) {
            return '4'; // Basquete
        }

        // Tênis
        if (strpos($nome, 'tênis') !== false ||
            strpos($nome, 'tenis') !== false ||
            strpos($nome, 'atp') !== false ||
            strpos($nome, 'wta') !== false ||
            strpos($nome, 'grand slam') !== false ||
            strpos($nome, 'open') !== false) {
            return '3'; // Tênis
        }

        // Vôlei
        if (strpos($nome, 'vôlei') !== false ||
            strpos($nome, 'volei') !== false ||
            strpos($nome, 'volley') !== false) {
            return '12'; // Vôlei
        }

        // Hóquei no Gelo
        if (strpos($nome, 'hóquei') !== false ||
            strpos($nome, 'hockey') !== false ||
            strpos($nome, 'nhl') !== false ||
            strpos($nome, 'khl') !== false) {
            return '10'; // Hóquei no Gelo
        }

        // Beisebol
        if (strpos($nome, 'beisebol') !== false ||
            strpos($nome, 'baseball') !== false ||
            strpos($nome, 'mlb') !== false) {
            return '5'; // Beisebol
        }

        // Tênis de Mesa
        if (strpos($nome, 'tênis de mesa') !== false ||
            strpos($nome, 'tenis de mesa') !== false ||
            strpos($nome, 'ping pong') !== false ||
            strpos($nome, 'table tennis') !== false) {
            return '25'; // Tênis de Mesa
        }

        // eSports
        if (strpos($nome, 'esports') !== false ||
            strpos($nome, 'e-sports') !== false ||
            strpos($nome, 'league of legends') !== false ||
            strpos($nome, 'counter-strike') !== false ||
            strpos($nome, 'dota') !== false) {
            return '53'; // eSports
        }

        // Default para Futebol se não conseguir determinar
        return '1';
    }

    /**
     * Altera o status de múltiplos campeonatos simultaneamente
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function alterarStatusEmMassa(Request $request)
    {
        try {
            $ids = $request->input('ids', []);
            $status = $request->input('status');

            if (empty($ids) || !is_array($ids) || empty($status)) {
                return response()->json([
                    'status' => false,
                    'message' => 'IDs de campeonatos e status são obrigatórios'
                ]);
            }

            // Flag para verificar se está ocultando ou mostrando
            $ocultar = ($status === 'Oculto');

            // Atualizar status de todos os campeonatos
            foreach ($ids as $id) {
                $campeonato = CampeonatoOculto::where('campeonato_id', $id)->first();

                if ($campeonato) {
                    $campeonato->status = $status;
                    $campeonato->save();

                    // Atualiza CSS para cada campeonato
                    $this->atualizarArquivoCSS('sports.css', $id, $ocultar);
                    $this->atualizarArquivoCSS('mobile.css', $id, $ocultar);
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Status dos campeonatos alterados com sucesso'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao alterar status em massa: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Processa os dados para o DataTable de campeonatos ocultos
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function campeonatosOcultosData(Request $request)
    {
        $tipoEsporte = $request->input('tipoEsporte');
        $nomeCampeonato = $request->input('nomeCampeonato');
        $status = $request->input('status');

        $query = CampeonatoOculto::query();

        // Aplicar filtros
        if (!empty($tipoEsporte)) {
            $query->where('tipo_esporte', $tipoEsporte);
        }

        if (!empty($nomeCampeonato)) {
            $query->where('nome', 'like', '%' . $nomeCampeonato . '%');
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        return DataTables::of($query)
            ->addColumn('acoes', function ($row) {
                $statusBtnText = $row->status === 'Oculto' ? 'Mostrar' : 'Ocultar';
                $statusBtnClass = $row->status === 'Oculto' ? 'btn-light-success' : 'btn-light-danger';

                return '<button type="button" class="btn btn-sm ' . $statusBtnClass . ' mb-0 me-2 _effect--ripple waves-effect waves-light btn-mudar-status" data-id="' . $row->campeonato_id . '" data-status="' . $row->status . '">' . $statusBtnText . '</button>' .
                    '<button type="button" class="btn btn-sm btn-light-danger mb-0 me-2 _effect--ripple waves-effect waves-light btn-remover" data-id="' . $row->campeonato_id . '" data-nome="' . htmlspecialchars($row->nome, ENT_QUOTES, 'UTF-8') . '"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></button>';
            })
            ->rawColumns(['acoes'])
            ->make(true);
    }

    /**
     * Carrega todos os títulos de esportes disponíveis no backup
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function carregarTitulosEsportes()
    {
        try {
            $html = file_get_contents(resource_path('views/admin/sports/backup.php'));

            // Regex para extrair os títulos de esportes e seus IDs
            preg_match_all('/class="leftSLLi(?:\s+sp(\d+))?"><a href="javascript:dummyF\(\);" title="([^"]+)\s*\((\d+)\)".*?class="sport_front_icon-(\d+)/', $html, $matches, PREG_SET_ORDER);

            $esportes = [];
            foreach ($matches as $match) {
                $sportId = $match[4]; // ID do ícone do esporte (sport_front_icon-XX)
                $titulo = $match[2]; // Nome do esporte (Futebol, Basquete, etc)
                $quantidade = $match[3]; // Quantidade de campeonatos

                $esportes[] = [
                    'id' => $sportId,
                    'titulo' => $titulo,
                    'quantidade' => $quantidade
                ];
            }

            return response()->json([
                'status' => true,
                'message' => 'Títulos de esportes carregados com sucesso',
                'esportes' => $esportes
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao carregar títulos de esportes: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Salva uma categoria de esporte para ser oculta
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function salvarCategoriaOculta(Request $request)
    {
        try {
            $sportId = $request->input('sport_id');
            $titulo = $request->input('titulo');
            $status = $request->input('status', 'Oculto');

            if (empty($sportId) || empty($titulo)) {
                return response()->json([
                    'status' => false,
                    'message' => 'ID do esporte e título são obrigatórios'
                ]);
            }

            // Verifica se já existe no banco
            $categoria = CategoriaOculta::where('sport_id', $sportId)->first();

            if ($categoria) {
                return response()->json([
                    'status' => false,
                    'message' => 'Esta categoria já está na lista de ocultos'
                ]);
            }

            // Salva no banco
            $categoria = new CategoriaOculta();
            $categoria->sport_id = $sportId;
            $categoria->titulo = $titulo;
            $categoria->status = $status;
            $categoria->save();

            // Atualiza CSS somente se o status for Oculto
            if ($status === 'Oculto') {
                // Adiciona regra CSS para ocultar a categoria
                $this->atualizarArquivoCSSCategoria('sports.css', $sportId, true);
                $this->atualizarArquivoCSSCategoria('mobile.css', $sportId, true);
            }

            return response()->json([
                'status' => true,
                'message' => 'Categoria salva com sucesso'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao salvar categoria: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Atualiza arquivo CSS adicionando ou removendo regra para ocultar categoria de esporte
     *
     * @param string $arquivo Nome do arquivo CSS (sports.css ou mobile.css)
     * @param string $sportId ID da categoria de esporte
     * @param bool $ocultar True para ocultar, False para mostrar
     * @return bool
     */
    private function atualizarArquivoCSSCategoria($arquivo, $sportId, $ocultar)
    {
        try {
            $caminho = public_path('css/' . $arquivo);
            $conteudo = file_get_contents($caminho);

            // Buscar o título completo com quantidade no banco
            $categoria = CategoriaOculta::where('sport_id', $sportId)->first();
            if (!$categoria) {
                \Log::warning("Categoria com ID {$sportId} não encontrada para atualizar CSS");
                return false;
            }

            // Simplificar as regras CSS para aumentar a compatibilidade
            $regra = "a[title*=\"{$categoria->titulo}\"] { display: none!important; }";

            if ($ocultar) {
                // Se a regra já existe, não faz nada
                if (strpos($conteudo, $regra) === false) {
                    \Log::info("Adicionando regra CSS para ocultar categoria {$categoria->titulo}");
                    // Adiciona a regra no final do arquivo
                    $conteudo .= "\n" . $regra;
                    file_put_contents($caminho, $conteudo);
                }
            } else {
                // Log do conteúdo original
                \Log::info("Removendo regra CSS para mostrar categoria {$categoria->titulo}");

                // Verificar se a regra existe antes de tentar removê-la
                if (strpos($conteudo, $regra) !== false) {
                    // Remove a regra exata
                    $conteudo = str_replace($regra, '', $conteudo);
                    $conteudo = str_replace("\n\n", "\n", $conteudo); // Remove linhas vazias duplicadas

                    file_put_contents($caminho, $conteudo);
                } else {
                    // Tentar encontrar outras variações da regra
                    $padrao = '/a\[title\*="' . preg_quote($categoria->titulo, '/') . '"\]\s*\{\s*display\s*:\s*none[^}]*\}/i';
                    $conteudoAtualizado = preg_replace($padrao, '', $conteudo);

                    if ($conteudoAtualizado !== $conteudo) {
                        $conteudoAtualizado = str_replace("\n\n", "\n", $conteudoAtualizado); // Remove linhas vazias duplicadas
                        file_put_contents($caminho, $conteudoAtualizado);
                        \Log::info("Regra removida usando regex");
                    } else {
                        \Log::warning("Regra não encontrada para remoção");
                    }
                }
            }

            return true;
        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar arquivo CSS para categoria: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return false;
        }
    }

    /**
     * Lista todas as categorias de esportes ocultas
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function listarCategoriasOcultas()
    {
        try {
            // Forçar criação da tabela se ela não existir
            if (!Schema::hasTable('categorias_ocultas')) {
                \Log::warning('Tabela categorias_ocultas não existe. Executando migration...');

                Schema::create('categorias_ocultas', function ($table) {
                    $table->id();
                    $table->string('sport_id');
                    $table->string('titulo');
                    $table->enum('status', ['Oculto', 'Visível'])->default('Oculto');
                    $table->timestamps();
                });

                \Log::info('Tabela categorias_ocultas criada com sucesso');
            }

            $categorias = CategoriaOculta::all();

            \Log::info('Listando categorias ocultas: ' . count($categorias) . ' encontradas');

            // Se não houver categorias, vamos adicionar algumas categorias de exemplo
            if ($categorias->isEmpty()) {
                \Log::warning('Nenhuma categoria encontrada. Adicionando categorias de exemplo...');

                // Array de categorias de exemplo
                $exemplosCategorias = [
                    ['sport_id' => '1', 'titulo' => 'Futebol', 'status' => 'Visível'],
                    ['sport_id' => '4', 'titulo' => 'Basquete', 'status' => 'Visível'],
                    ['sport_id' => '3', 'titulo' => 'Tênis', 'status' => 'Visível'],
                    ['sport_id' => '53', 'titulo' => 'eSports', 'status' => 'Oculto']
                ];

                foreach ($exemplosCategorias as $cat) {
                    CategoriaOculta::create($cat);
                }

                $categorias = CategoriaOculta::all();
                \Log::info('Categorias de exemplo adicionadas: ' . count($categorias));
            }

            return response()->json([
                'status' => true,
                'message' => 'Categorias ocultas carregadas com sucesso',
                'categorias' => $categorias
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao listar categorias ocultas: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return response()->json([
                'status' => false,
                'message' => 'Erro ao listar categorias ocultas: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Processa os dados para o DataTable de categorias ocultas
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function categoriasOcultasData(Request $request)
    {
        $statusFiltro = $request->input('statusCategoria');
        $tituloCategoria = $request->input('tituloCategoria');

        $query = CategoriaOculta::query();

        // Aplicar filtros
        if (!empty($statusFiltro)) {
            $query->where('status', $statusFiltro);
        }

        if (!empty($tituloCategoria)) {
            $query->where('titulo', 'like', '%' . $tituloCategoria . '%');
        }

        return DataTables::of($query)
            ->addColumn('acoes', function ($row) {
                $statusBtnText = $row->status === 'Oculto' ? 'Mostrar' : 'Ocultar';
                $statusBtnClass = $row->status === 'Oculto' ? 'btn-light-success' : 'btn-light-danger';

                return '<button type="button" class="btn btn-sm ' . $statusBtnClass . ' mb-0 me-2 _effect--ripple waves-effect waves-light btn-mudar-status-categoria" data-id="' . $row->id . '" data-sport-id="' . $row->sport_id . '" data-status="' . $row->status . '">' . $statusBtnText . '</button>' .
                    '<button type="button" class="btn btn-sm btn-light-danger mb-0 me-2 _effect--ripple waves-effect waves-light btn-remover-categoria" data-id="' . $row->id . '" data-sport-id="' . $row->sport_id . '" data-titulo="' . htmlspecialchars($row->titulo, ENT_QUOTES, 'UTF-8') . '"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></button>';
            })
            ->rawColumns(['acoes'])
            ->make(true);
    }

    /**
     * Remove uma categoria de esporte da lista de ocultos
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removerCategoriaOculta(Request $request)
    {
        try {
            $sportId = $request->input('sport_id');

            if (empty($sportId)) {
                return response()->json([
                    'status' => false,
                    'message' => 'ID da categoria é obrigatório'
                ]);
            }

            // Verifica se existe no banco
            $categoria = CategoriaOculta::where('sport_id', $sportId)->first();

            if (!$categoria) {
                return response()->json([
                    'status' => false,
                    'message' => 'Categoria não encontrada'
                ]);
            }

            // Remove do banco
            $categoria->delete();

            // Remove CSS para mostrar a categoria
            $this->atualizarArquivoCSSCategoria('sports.css', $sportId, false);
            $this->atualizarArquivoCSSCategoria('mobile.css', $sportId, false);

            return response()->json([
                'status' => true,
                'message' => 'Categoria removida com sucesso da lista de ocultos'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao remover categoria: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Muda o status de uma categoria de esporte (oculto/visível)
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function mudarStatusCategoria(Request $request)
    {
        try {
            $id = $request->input('id');
            $sportId = $request->input('sport_id');
            $status = $request->input('status');

            if (empty($id) || empty($sportId) || empty($status)) {
                return response()->json([
                    'status' => false,
                    'message' => 'ID, Sport ID e status são obrigatórios'
                ]);
            }

            // Verifica se existe no banco
            $categoria = CategoriaOculta::findOrFail($id);

            // Atualiza status
            $categoria->status = $status;
            $categoria->save();

            // Atualiza CSS para ocultar/mostrar a categoria
            // Se for tornar visível, precisamos remover a regra CSS
            $ocultar = ($status === 'Oculto');
            if ($ocultar) {
                // Adiciona regra CSS para ocultar
                $this->atualizarArquivoCSSCategoria('sports.css', $sportId, true);
                $this->atualizarArquivoCSSCategoria('mobile.css', $sportId, true);
            } else {
                // Remove regra CSS para tornar visível
                $this->atualizarArquivoCSSCategoria('sports.css', $sportId, false);
                $this->atualizarArquivoCSSCategoria('mobile.css', $sportId, false);
            }

            return response()->json([
                'status' => true,
                'message' => 'Status da categoria atualizado com sucesso'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao atualizar status da categoria: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Encontra um valor em um array associativo
     *
     * @param array $array O array associativo
     * @param array $keys Os índices para procurar no array
     * @return mixed O valor encontrado ou null se não encontrado
     */
    private function findValueInArray($array, $keys)
    {
        // Verifica se o array é realmente um array
        if (!is_array($array)) {
            return null;
        }

        // Primeiro, verificar no nível atual
        foreach ($keys as $key) {
            if (isset($array[$key])) {
                return $array[$key];
            }
        }

        // Se não encontrou no nível atual, busca recursivamente nos subarrays
        foreach ($array as $value) {
            if (is_array($value)) {
                $found = $this->findValueInArray($value, $keys);
                if ($found !== null) {
                    return $found;
                }
            }
        }

        return null;
    }

    /**
     * Fornece estatísticas dos bilhetes esportivos
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sportsApostasStats(Request $request)
    {
        $dataInicial = $request->input('dataInicial', Carbon::now()->format('Y-m-d')); // Sempre iniciar com hoje
        $dataFinal = $request->input('dataFinal', Carbon::now()->format('Y-m-d')); // Sempre iniciar com hoje
        $nomeUsuario = $request->input('nomeUsuario', '');
        $statusFiltro = $request->input('statusFiltro', '');

        // Formatar datas para incluir horário
        $dataInicialFormatada = $dataInicial . ' 00:00:00';
        $dataFinalFormatada = $dataFinal . ' 23:59:59';

        // OTIMIZADO: Usar agregações SQL ao invés de loops PHP
        $baseQuery = SportBetSummary::where('provider', 'digitain')
            ->whereBetween('created_at', [$dataInicialFormatada, $dataFinalFormatada]);

        // Filtro de usuário
        if (!empty($nomeUsuario)) {
            $usuariosIds = DB::table('users')
                ->where('name', 'like', '%' . $nomeUsuario . '%')
                ->pluck('id')
                ->toArray();

            if (empty($usuariosIds)) {
                return response()->json([
                    'bilhetes_abertos' => 0,
                    'bilhetes_finalizados' => 0,
                    'bilhetes_premiados' => 0,
                    'valor_apostas_abertas' => '0,00',
                    'valor_apostas_finalizadas' => '0,00',
                    'valor_premios' => '0,00'
                ]);
            }

            $baseQuery->whereIn('user_id', $usuariosIds);
        }

        // Buscar transactionIds com resultado (finalizados)
        $transacoesFinalizadas = (clone $baseQuery)
            ->whereIn('operation', ['credit', 'lose', 'cancel_debit', 'cancel_credit'])
            ->pluck('transactionId')
            ->toArray();

        // BILHETES ABERTOS (sem resultado)
        $bilhetesAbertosQuery = (clone $baseQuery)
            ->where('operation', 'debit')
            ->whereNotIn('transactionId', $transacoesFinalizadas);

        $bilhetes_abertos = $bilhetesAbertosQuery->count();
        $valor_apostas_abertas = $bilhetesAbertosQuery->sum('amount');

        // BILHETES FINALIZADOS (com resultado)
        $bilhetesFinalizadosQuery = (clone $baseQuery)
            ->where('operation', 'debit')
            ->whereIn('transactionId', $transacoesFinalizadas);

        $bilhetes_finalizados = $bilhetesFinalizadosQuery->count();
        $valor_apostas_finalizadas = $bilhetesFinalizadosQuery->sum('amount');

        // BILHETES PREMIADOS (credit) - Apenas transactionIds únicos com credit
        $transacoesGanhadoras = (clone $baseQuery)
            ->where('operation', 'credit')
            ->pluck('transactionId')
            ->unique()
            ->toArray();

        $bilhetes_premiados = count($transacoesGanhadoras);

        $valor_premios = (clone $baseQuery)
            ->where('operation', 'credit')
            ->whereIn('transactionId', $transacoesGanhadoras)
            ->sum('amount');

        // Aplicar filtros de status se necessário
        if ($statusFiltro === 'abertos') {
            $bilhetes_finalizados = 0;
            $bilhetes_premiados = 0;
            $valor_apostas_finalizadas = 0;
            $valor_premios = 0;
        } elseif ($statusFiltro === 'finalizados') {
            $bilhetes_abertos = 0;
            $valor_apostas_abertas = 0;
        }

        return response()->json([
            'bilhetes_abertos' => $bilhetes_abertos,
            'bilhetes_finalizados' => $bilhetes_finalizados,
            'bilhetes_premiados' => $bilhetes_premiados,
            'valor_apostas_abertas' => number_format($valor_apostas_abertas, 2, ',', '.'),
            'valor_apostas_finalizadas' => number_format($valor_apostas_finalizadas, 2, ',', '.'),
            'valor_premios' => number_format($valor_premios, 2, ',', '.')
        ]);
    }

    /**
     * Exibe a página do mapa de apostas Digitain
     */
    public function mapaApostas(Request $request)
    {
        return view('admin.sports.mapa_apostas');
    }

    /**
     * Fornece dados para o mapa de apostas Digitain
     * Agrupa apostas pendentes por evento
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

        // Query para buscar apostas digitain pendentes (sem resultado)
        $subQuery = SportBetSummary::where('provider', 'digitain')
            ->whereIn('operation', ['credit', 'lose', 'cancel_debit', 'cancel_credit'])
            ->whereBetween('created_at', [$dataInicial, $dataFinal])
            ->pluck('transactionId')
            ->toArray();

        $query = DB::table('SportBetSummary')
            ->select([
                'id',
                'transactionId',
                'user_id',
                'provider',
                'amount',
                'betslip',
                'created_at'
            ])
            ->where('provider', 'digitain')
            ->where('operation', 'debit')
            ->whereNotIn('transactionId', $subQuery)
            ->whereBetween('created_at', [$dataInicial, $dataFinal])
            ->orderByDesc('id');

        $apostas = $query->get();

        // Agrupar apostas por EventId
        $eventosMap = [];

        foreach ($apostas as $aposta) {
            if (!$aposta->betslip) continue;

            try {
                $betslipData = json_decode($aposta->betslip, true);
                if (!is_array($betslipData)) continue;

                // Estrutura Digitain: bet_stakes.BetStakes[]
                $bets = null;
                if (isset($betslipData['bet_stakes']['BetStakes']) && is_array($betslipData['bet_stakes']['BetStakes'])) {
                    $bets = $betslipData['bet_stakes']['BetStakes'];
                }

                if (!$bets) continue;

                // Aplicar filtro de tipo de aposta
                if ($tipoAposta === 'simples' && count($bets) > 1) {
                    continue; // Pular apostas múltiplas
                } elseif ($tipoAposta === 'multiplas' && count($bets) <= 1) {
                    continue; // Pular apostas simples
                }

                // Coletar todos os eventos desta aposta antes de processar
                $eventosNaAposta = [];

                // Processar cada bet
                foreach ($bets as $bet) {
                    // EventId pode estar em diferentes formatos
                    $eventId = $bet['EventId'] ?? $bet['EventID'] ?? $bet['event_id'] ?? null;
                    if (!$eventId) continue;

                    $sport = $bet['SportName'] ?? 'N/A';
                    $eventName = $bet['EventName'] ?? $bet['EventNameOnly'] ?? 'N/A';

                    // Aplicar filtro de esporte
                    if (!empty($esporteFiltro) && strtolower($sport) !== strtolower($esporteFiltro)) {
                        continue;
                    }

                    // Formar data/hora do evento
                    $dataHoraEvento = 'N/A';
                    if (isset($bet['EventDate'])) {
                        try {
                            $dataHoraEvento = Carbon::parse($bet['EventDate'])->format('d/m/Y H:i');
                        } catch (\Exception $e) {
                            $dataHoraEvento = $bet['EventDate'];
                        }
                    }

                    // Adicionar evento à lista
                    $eventosNaAposta[] = [
                        'event_id' => $eventId,
                        'partida' => $eventName,
                        'esporte' => $sport,
                        'data_hora' => $dataHoraEvento
                    ];
                }

                // Se não encontrou nenhum evento válido, pular
                if (empty($eventosNaAposta)) {
                    continue;
                }

                // Processar cada evento encontrado
                foreach ($eventosNaAposta as $eventoInfo) {
                    $eventId = $eventoInfo['event_id'];

                    // Agrupar por EventId
                    if (!isset($eventosMap[$eventId])) {
                        $eventosMap[$eventId] = [
                            'event_id' => $eventId,
                            'partida' => $eventoInfo['partida'],
                            'esporte' => $eventoInfo['esporte'],
                            'data_hora' => $eventoInfo['data_hora'],
                            'valor_acumulado' => 0,
                            'quantidade_apostas' => 0,
                            'detalhes' => [],
                            'transacoes_contabilizadas' => []
                        ];
                    }

                    // Verificar se esta transação já foi contabilizada para este evento
                    if (!in_array($aposta->transactionId, $eventosMap[$eventId]['transacoes_contabilizadas'])) {
                        // Somar valores apenas uma vez por transação
                        $eventosMap[$eventId]['valor_acumulado'] += (float) $aposta->amount;
                        $eventosMap[$eventId]['quantidade_apostas']++;

                        // Marcar transação como contabilizada
                        $eventosMap[$eventId]['transacoes_contabilizadas'][] = $aposta->transactionId;

                        // Armazenar detalhes
                        $eventosMap[$eventId]['detalhes'][] = [
                            'transaction_id' => $aposta->transactionId,
                            'user_id' => $aposta->user_id,
                            'amount' => $aposta->amount,
                            'created_at' => $aposta->created_at
                        ];
                    }
                }

            } catch (\Exception $e) {
                continue;
            }
        }

        // Converter para array e ordenar por valor acumulado
        $eventos = array_values($eventosMap);

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
        // Buscar informações do evento
        $eventoInfo = $this->getEventoInfo($event_id);

        return view('admin.sports.mapa_apostas_detalhes', compact('event_id', 'eventoInfo'));
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

        // Buscar apostas pendentes
        $subQuery = SportBetSummary::where('provider', 'digitain')
            ->whereIn('operation', ['credit', 'lose', 'cancel_debit', 'cancel_credit'])
            ->whereBetween('created_at', [$dataInicial, $dataFinal])
            ->pluck('transactionId')
            ->toArray();

        $query = DB::table('SportBetSummary')
            ->select([
                'id',
                'transactionId',
                'user_id',
                'amount',
                'betslip',
                'created_at'
            ])
            ->where('provider', 'digitain')
            ->where('operation', 'debit')
            ->whereNotIn('transactionId', $subQuery)
            ->whereBetween('created_at', [$dataInicial, $dataFinal])
            ->orderByDesc('id');

        $apostas = $query->get();

        // Agrupar por mercado
        $mercadosMap = [];

        foreach ($apostas as $aposta) {
            if (!$aposta->betslip) continue;

            try {
                $betslipData = json_decode($aposta->betslip, true);
                if (!is_array($betslipData)) continue;

                $bets = null;
                if (isset($betslipData['bet_stakes']['BetStakes']) && is_array($betslipData['bet_stakes']['BetStakes'])) {
                    $bets = $betslipData['bet_stakes']['BetStakes'];
                }

                if (!$bets) continue;

                // Coletar mercados do evento correto
                $mercadosNaAposta = [];

                // Processar cada bet
                foreach ($bets as $bet) {
                    $eventId = $bet['EventId'] ?? $bet['EventID'] ?? $bet['event_id'] ?? null;

                    // Verificar se é do evento correto
                    if ($eventId != $event_id) continue;

                    $marketName = $bet['MarketName'] ?? 'N/A';
                    $stakeName = $bet['StakeName'] ?? 'N/A';

                    // Chave única
                    $chaveGrupo = $marketName . '|' . $stakeName;

                    // Adicionar à lista (evitar duplicatas na mesma aposta)
                    if (!isset($mercadosNaAposta[$chaveGrupo])) {
                        $mercadosNaAposta[$chaveGrupo] = [
                            'market' => $marketName,
                            'stake' => $stakeName
                        ];
                    }
                }

                // Se não encontrou nenhum mercado válido, pular
                if (empty($mercadosNaAposta)) {
                    continue;
                }

                // Processar cada mercado encontrado (apenas uma vez por transação)
                foreach ($mercadosNaAposta as $chaveGrupo => $mercadoInfo) {
                    if (!isset($mercadosMap[$chaveGrupo])) {
                        $mercadosMap[$chaveGrupo] = [
                            'tipo_aposta' => $mercadoInfo['market'],
                            'opcao' => $mercadoInfo['stake'],
                            'quantidade' => 0,
                            'valor_apostado' => 0,
                            'transacoes_contabilizadas' => []
                        ];
                    }

                    // Verificar se esta transação já foi contabilizada para este mercado
                    if (!in_array($aposta->transactionId, $mercadosMap[$chaveGrupo]['transacoes_contabilizadas'])) {
                        $mercadosMap[$chaveGrupo]['quantidade']++;
                        $mercadosMap[$chaveGrupo]['valor_apostado'] += (float) $aposta->amount;
                        $mercadosMap[$chaveGrupo]['transacoes_contabilizadas'][] = $aposta->transactionId;
                    }
                }

            } catch (\Exception $e) {
                continue;
            }
        }

        $mercados = array_values($mercadosMap);

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
     * Busca informações do evento
     */
    private function getEventoInfo($event_id)
    {
        $dataInicial = Carbon::now()->subDays(7)->format('Y-m-d H:i:s');
        $dataFinal = Carbon::now()->format('Y-m-d H:i:s');

        $subQuery = SportBetSummary::where('provider', 'digitain')
            ->whereIn('operation', ['credit', 'lose', 'cancel_debit', 'cancel_credit'])
            ->whereBetween('created_at', [$dataInicial, $dataFinal])
            ->pluck('transactionId')
            ->toArray();

        $query = DB::table('SportBetSummary')
            ->select(['betslip', 'amount'])
            ->where('provider', 'digitain')
            ->where('operation', 'debit')
            ->whereNotIn('transactionId', $subQuery)
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

                $bets = null;
                if (isset($betslipData['bet_stakes']['BetStakes']) && is_array($betslipData['bet_stakes']['BetStakes'])) {
                    $bets = $betslipData['bet_stakes']['BetStakes'];
                }

                if (!$bets) continue;

                $contemEvento = false;
                foreach ($bets as $bet) {
                    $eventId = $bet['EventId'] ?? $bet['EventID'] ?? $bet['event_id'] ?? null;

                    if ($eventId == $event_id) {
                        $contemEvento = true;

                        if ($eventoInfo['partida'] === 'N/A') {
                            $eventoInfo['partida'] = $bet['EventName'] ?? $bet['EventNameOnly'] ?? 'N/A';

                            if (isset($bet['EventDate'])) {
                                try {
                                    $eventoInfo['data'] = Carbon::parse($bet['EventDate'])->format('d/m/Y H:i');
                                } catch (\Exception $e) {
                                    $eventoInfo['data'] = $bet['EventDate'];
                                }
                            }
                        }
                        break;
                    }
                }

                if ($contemEvento) {
                    $totalApostado += (float) $aposta->amount;
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
        return view('admin.sports.gerenciamento_riscos');
    }

    /**
     * Fornece dados para o gerenciamento de riscos Digitain
     */
    public function gerenciamentoRiscosData(Request $request)
    {
        $ordenarPor = $request->input('ordenarPor', 'possivel_retorno');
        $nomeUsuario = $request->input('nomeUsuario', '');

        // Buscar apostas digitain pendentes
        $subQuery = SportBetSummary::where('provider', 'digitain')
            ->whereIn('operation', ['credit', 'lose', 'cancel_debit', 'cancel_credit'])
            ->pluck('transactionId')
            ->toArray();

        $query = DB::table('SportBetSummary as s1')
            ->select([
                's1.id',
                's1.transactionId',
                's1.user_id',
                's1.provider',
                's1.amount',
                's1.betslip',
                's1.created_at'
            ])
            ->where('s1.provider', 'digitain')
            ->where('s1.operation', 'debit')
            ->whereNotIn('s1.transactionId', $subQuery);

        // Aplicar ordenação
        switch ($ordenarPor) {
            case 'possivel_retorno':
                $query->orderByRaw("
                    COALESCE(
                        CAST(JSON_EXTRACT(betslip, '$.bet_stakes.MaxWinAmount') AS DECIMAL(10,2)),
                        0
                    ) DESC
                ");
                break;
            case 'quantidade_apostas_bilhete':
                $query->orderByRaw("
                    COALESCE(
                        JSON_LENGTH(JSON_EXTRACT(betslip, '$.bet_stakes.BetStakes')),
                        0
                    ) DESC
                ");
                break;
            case 'valor_apostado':
                $query->orderBy('amount', 'DESC');
                break;
            case 'odds':
                $query->orderByRaw("
                    COALESCE(
                        CAST(JSON_EXTRACT(betslip, '$.bet_stakes.Factor') AS DECIMAL(10,2)),
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
                    $user = User::find($row->user_id);
                    $userName = $user ? $user->name : 'N/A';

                    return '<button type="button" class="btn btn-info btn-sm ver-aposta"
                                data-bs-toggle="modal"
                                data-bs-target="#verApostaModal"
                                data-betslip="' . htmlspecialchars($row->betslip ?: '{}', ENT_QUOTES, 'UTF-8') . '"
                                data-operation="debit"
                                data-amount="' . $row->amount . '"
                                data-cashout="0"
                                data-received-amount="0"
                                data-user-id="' . $row->user_id . '"
                                data-user-name="' . htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') . '">
                                Conferir Bilhetes
                            </button>';
                })
                ->addColumn('valor_apostado', function ($row) {
                    return 'R$ ' . number_format($row->amount, 2, ',', '.');
                })
                ->addColumn('possivel_retorno', function ($row) {
                    try {
                        if (!$row->betslip) return 'R$ 0,00';

                        $betslipData = json_decode($row->betslip, true);
                        if (!is_array($betslipData)) return 'R$ 0,00';

                        $maxWinAmount = $betslipData['bet_stakes']['MaxWinAmount'] ?? 0;
                        return 'R$ ' . number_format($maxWinAmount, 2, ',', '.');
                    } catch (\Exception $e) {
                        return 'R$ 0,00';
                    }
                })
                ->addColumn('apostas_em_aberto', function ($row) {
                    try {
                        if (!$row->betslip) return 0;

                        $betslipData = json_decode($row->betslip, true);
                        if (!is_array($betslipData)) return 0;

                        $bets = $betslipData['bet_stakes']['BetStakes'] ?? [];
                        return count($bets);
                    } catch (\Exception $e) {
                        return 0;
                    }
                })
                ->addColumn('quantidade_apostas_bilhete', function ($row) {
                    try {
                        if (!$row->betslip) return 0;

                        $betslipData = json_decode($row->betslip, true);
                        if (!is_array($betslipData)) return 0;

                        $bets = $betslipData['bet_stakes']['BetStakes'] ?? [];
                        return count($bets);
                    } catch (\Exception $e) {
                        return 0;
                    }
                })
                ->addColumn('odds', function ($row) {
                    try {
                        if (!$row->betslip) return '1,00';

                        $betslipData = json_decode($row->betslip, true);
                        if (!is_array($betslipData)) return '1,00';

                        $odds = $betslipData['bet_stakes']['Factor'] ?? 1.0;
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
     * Retorna bilhetes específicos do evento
     */
    private function getBilhetesEvento($event_id)
    {
        $dataInicial = Carbon::now()->subDays(7)->format('Y-m-d H:i:s');
        $dataFinal = Carbon::now()->format('Y-m-d H:i:s');

        // Buscar apostas pendentes
        $subQuery = SportBetSummary::where('provider', 'digitain')
            ->whereIn('operation', ['credit', 'lose', 'cancel_debit', 'cancel_credit'])
            ->whereBetween('created_at', [$dataInicial, $dataFinal])
            ->pluck('transactionId')
            ->toArray();

        $apostas = DB::table('SportBetSummary')
            ->select([
                'id',
                'transactionId',
                'user_id',
                'provider',
                'operation',
                'amount',
                'betslip',
                'created_at'
            ])
            ->where('provider', 'digitain')
            ->where('operation', 'debit')
            ->whereNotIn('transactionId', $subQuery)
            ->whereBetween('created_at', [$dataInicial, $dataFinal])
            ->orderByDesc('id')
            ->get();

        // Filtrar apostas que contêm o evento específico
        $apostasFiltradas = [];
        foreach ($apostas as $aposta) {
            if (!$aposta->betslip) continue;

            try {
                $betslipData = json_decode($aposta->betslip, true);
                if (!is_array($betslipData)) continue;

                $bets = $betslipData['bet_stakes']['BetStakes'] ?? [];
                if (empty($bets)) continue;

                // Verificar se contém o evento
                $contemEvento = false;
                foreach ($bets as $bet) {
                    $eventId = $bet['EventId'] ?? $bet['EventID'] ?? $bet['event_id'] ?? null;
                    if ($eventId == $event_id) {
                        $contemEvento = true;
                        break;
                    }
                }

                if ($contemEvento) {
                    $apostasFiltradas[] = $aposta;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        try {
            return datatables()
                ->of(collect($apostasFiltradas))
                ->addColumn('usuario', function ($row) {
                    static $userCache = [];
                    if (!isset($userCache[$row->user_id])) {
                        $user = User::find($row->user_id);
                        if ($user) {
                            $ranking = $user->getRanking();
                            $rankingHtml = '';
                            if ($ranking && !empty($ranking['image'])) {
                                $rankingHtml = '<img src="' . asset($ranking['image']) . '" class="ranking-img me-2" width="25" height="25">';
                            }
                            $nomeHtml = '<a href="javascript:void(0);" onclick="LoadAgent(\'' . $user->id . '\');">' . $user->name . '</a>';
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
                    return '<span class="badge bg-warning">Pendente</span>';
                })
                ->addColumn('odd', function ($row) {
                    try {
                        $betslipData = json_decode($row->betslip, true);
                        $odd = $betslipData['bet_stakes']['Factor'] ?? 1.0;
                        return number_format($odd, 2, ',', '.');
                    } catch (\Exception $e) {
                        return '1,00';
                    }
                })
                ->addColumn('valor', function ($row) {
                    return 'R$ ' . number_format($row->amount, 2, ',', '.');
                })
                ->addColumn('possivel_ganho', function ($row) {
                    try {
                        $betslipData = json_decode($row->betslip, true);
                        $maxWin = $betslipData['bet_stakes']['MaxWinAmount'] ?? 0;
                        return 'R$ ' . number_format($maxWin, 2, ',', '.');
                    } catch (\Exception $e) {
                        return 'R$ 0,00';
                    }
                })
                ->addColumn('data', function ($row) {
                    return Carbon::parse($row->created_at)->format('d/m/Y H:i:s');
                })
                ->addColumn('acoes', function ($row) {
                    $user = User::find($row->user_id);
                    $userName = $user ? $user->name : 'N/A';

                    return '<button type="button" class="btn btn-primary btn-sm ver-aposta"
                                data-bs-toggle="modal"
                                data-bs-target="#verApostaModal"
                                data-betslip="' . htmlspecialchars($row->betslip, ENT_QUOTES, 'UTF-8') . '"
                                data-operation="debit"
                                data-amount="' . $row->amount . '"
                                data-cashout="0"
                                data-received-amount="0"
                                data-user-id="' . $row->user_id . '"
                                data-user-name="' . htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') . '">
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
     * Retorna lista de esportes disponíveis
     */
    private function getEsportesDisponiveis()
    {
        $dataInicial = Carbon::now()->subDays(7)->format('Y-m-d H:i:s');
        $dataFinal = Carbon::now()->format('Y-m-d H:i:s');

        $subQuery = SportBetSummary::where('provider', 'digitain')
            ->whereIn('operation', ['credit', 'lose', 'cancel_debit', 'cancel_credit'])
            ->whereBetween('created_at', [$dataInicial, $dataFinal])
            ->pluck('transactionId')
            ->toArray();

        $query = DB::table('SportBetSummary')
            ->select('betslip')
            ->where('provider', 'digitain')
            ->where('operation', 'debit')
            ->whereNotIn('transactionId', $subQuery)
            ->whereBetween('created_at', [$dataInicial, $dataFinal])
            ->orderByDesc('id');

        $apostas = $query->get();
        $esportes = [];

        foreach ($apostas as $aposta) {
            if (!$aposta->betslip) continue;

            try {
                $betslipData = json_decode($aposta->betslip, true);
                if (!is_array($betslipData)) continue;

                $bets = null;
                if (isset($betslipData['bet_stakes']['BetStakes']) && is_array($betslipData['bet_stakes']['BetStakes'])) {
                    $bets = $betslipData['bet_stakes']['BetStakes'];
                }

                if (!$bets) continue;

                foreach ($bets as $bet) {
                    if (isset($bet['SportName'])) {
                        $sport = $bet['SportName'];
                        if (!in_array($sport, $esportes)) {
                            $esportes[] = $sport;
                        }
                    }
                }

            } catch (\Exception $e) {
                continue;
            }
        }

        sort($esportes);

        return response()->json(['esportes' => $esportes]);
    }
}
