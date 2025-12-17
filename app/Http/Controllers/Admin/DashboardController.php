<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Settings;
use App\Models\VisitLogs;
use App\Models\GameHistory;
use App\Models\SportBetSummary;
use App\Models\Transactions;
use App\Models\HomeSections;
use App\Models\HomeSectionsSettings;
use App\Helpers\Core as Helper;
use NumberFormatter;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Middleware aplicado nas rotas
    }

    /**
     * Exibe o dashboard principal do admin
     */
    public function index(Request $request)
    {
        // Verificar se o usuário está autenticado e é admin
        if (!auth()->check() || auth()->user()->is_admin == 0) {
            return redirect()->route('admin.login');
        }

        if (auth()->user()->role == 'affiliate') {
            return redirect()->route('admin.afiliacao.estatisticas.gerente');
        }

        // Obter dados para o dashboard
        $data = $this->getDashboardData($request);

        return view('admin.dash', $data);
    }

    /**
     * Retorna dados de Esportes via AJAX
     */
    public function getSportsDataAjax(Request $request)
    {
        try {
            $dateRange = $this->getDateRange($request);
            $sportsData = $this->getSportsData($dateRange);

            return response()->json([
                'success' => true,
                'data' => [
                    'apostas' => number_format($sportsData['sports_bets_today'], 2, ',', '.'),
                    'premios' => number_format($sportsData['sports_wins_today'], 2, ',', '.'),
                    'liquido' => number_format($sportsData['sports_total_today'], 2, ',', '.'),
                    'bilhetesAbertos' => number_format($sportsData['bilhetes_abertos_hoje']['valor'], 2, ',', '.')
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Sports AJAX error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar dados de esportes.'
            ], 500);
        }
    }

    /**
     * Retorna dados financeiros via AJAX
     */
    public function getFinancialDataAjax(Request $request)
    {
        try {
            $dateRange = $this->getDateRange($request);
            $financialData = $this->getFinancialData($dateRange);

            return response()->json([
                'success' => true,
                'data' => [
                    'depositos' => number_format($financialData['total_in_hoje'], 2, ',', '.'),
                    'depositosProcessando' => number_format($financialData['total_pix_hoje'], 2, ',', '.'),
                    'saques' => number_format($financialData['total_out_hoje'], 2, ',', '.'),
                    'saquesProcessando' => number_format($financialData['total_out_normal_hoje'], 2, ',', '.'),
                    'bonus' => number_format($financialData['total_out_afiliados_hoje'], 2, ',', '.'),
                    'cpaRewards' => number_format($financialData['total_manual_hoje'], 2, ',', '.')
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Financial AJAX error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar dados financeiros.'
            ], 500);
        }
    }

    /**
     * Retorna dados de cassino via AJAX
     */
    public function getCasinoDataAjax(Request $request)
    {
        try {
            $dateRange = $this->getDateRange($request);
            $casinoData = $this->getCasinoData($dateRange);

            return response()->json([
                'success' => true,
                'data' => [
                    'apostas' => number_format($casinoData['cassino_loss_today'], 2, ',', '.'),
                    'premios' => number_format($casinoData['cassino_win_today'], 2, ',', '.'),
                    'liquido' => number_format($casinoData['cassino_total_today'], 2, ',', '.')
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Casino AJAX error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar dados de cassino.'
            ], 500);
        }
    }

    /**
     * Retorna dados GGR via AJAX
     */
    public function getGgrDataAjax(Request $request)
    {
        try {
            $dateRange = $this->getDateRange($request);
            $ggrData = $this->getGGRData($dateRange, $request);

            return response()->json([
                'success' => true,
                'data' => [
                    'apostasPerdidasCassino' => number_format($ggrData['apostasPerdidasCassino'], 2, ',', '.'),
                    'apostasGanhadorasCassino' => number_format($ggrData['apostasGanhadorasCassino'], 2, ',', '.'),
                    'ggrConsumidoCassino' => number_format($ggrData['ggrConsumidoCassino'], 2, ',', '.'),
                    'apostasPerdidasSports' => number_format($ggrData['apostasPerdidasSports'], 2, ',', '.'),
                    'totalPremiosSports' => number_format($ggrData['totalPremiosSports'], 2, ',', '.'),
                    'ggrConsumidoSports' => number_format($ggrData['ggrConsumidoSports'], 2, ',', '.'),
                    'providerCassino' => $ggrData['providerCassino']
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('GGR AJAX error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar dados GGR.'
            ], 500);
        }
    }

    /**
     * Coleta todos os dados necessários para o dashboard
     */
    private function getDashboardData(Request $request)
    {
        // Definir período de filtro
        $dateRange = $this->getDateRange($request);

        return array_merge(
            $dateRange,
            $this->getVisitorStats(),
            $this->getUserStats(),
            $this->getGGRData($dateRange, $request),
            $this->getFinancialData($dateRange),
            $this->getCasinoData($dateRange),
            $this->getSportsData($dateRange),
            $this->getChartData(),
            $this->getGGRDisplaySettings()
        );
    }

    /**
     * Define o período de filtro baseado nos parâmetros da requisição
     */
    private function getDateRange(Request $request)
    {
        $dataInicial = $request->input('dataInicial')
            ? Carbon::parse($request->input('dataInicial'))->startOfDay()
            : Carbon::today()->startOfDay();

        $dataFinal = $request->input('dataFinal')
            ? Carbon::parse($request->input('dataFinal'))->endOfDay()
            : Carbon::today()->endOfDay();

        // Gerar label de período para exibição nos cards
        $periodoLabel = $this->getPeriodoLabel($dataInicial, $dataFinal);

        return [
            'dataInicial' => $dataInicial,
            'dataFinal' => $dataFinal,
            'periodoLabel' => $periodoLabel
        ];
    }

    /**
     * Gera label de período formatado (ex: "11/10 a 12/10")
     */
    private function getPeriodoLabel($dataInicial, $dataFinal)
    {
        // Se for o mesmo dia, mostrar apenas uma data
        if ($dataInicial->format('d/m/Y') === $dataFinal->format('d/m/Y')) {
            return $dataInicial->format('d/m');
        }

        // Se for intervalo, mostrar "DD/MM a DD/MM"
        return $dataInicial->format('d/m') . ' a ' . $dataFinal->format('d/m');
    }

    /**
     * Estatísticas de visitantes por dispositivo
     */
    private function getVisitorStats()
    {
        $Desktop = VisitLogs::where('Agent', 'Desktop')->count();
        $Android = VisitLogs::where('Agent', 'Android')->count();
        $IOS = VisitLogs::where('Agent', 'iOS')->count();

        $totalViews = $Desktop + $Android + $IOS;

        return [
            'Desktop' => $Desktop,
            'Android' => $Android,
            'IOS' => $IOS,
            'totalViews' => $totalViews,
            'iosPercentage' => round(($totalViews > 0) ? ($IOS / $totalViews) * 100 : 0, 2),
            'androidPercentage' => round(($totalViews > 0) ? ($Android / $totalViews) * 100 : 0, 2),
            'desktopPercentage' => round(($totalViews > 0) ? ($Desktop / $totalViews) * 100 : 0, 2)
        ];
    }

    /**
     * Estatísticas de usuários
     */
    private function getUserStats()
    {
        return [
            'TotalUsers' => User::count(),
            'Afiliados' => User::whereHas('affiliates')->count(),
            'DemoAgents' => User::where('is_demo_agent', 1)->count()
        ];
    }

    /**
     * Dados financeiros (depósitos/saques/saldo manual)
     * OTIMIZADO: Apenas calcula para o período filtrado
     */
    private function getFinancialData(array $dateRange)
    {
        $total_in_hoje = Transactions::where('type', 0)
            ->where('status', 1)
            ->whereBetween('created_at', [$dateRange['dataInicial'], $dateRange['dataFinal']])
            ->sum('amount');

        // Depósitos PIX específicos
        $total_pix_hoje = Transactions::where('type', 0)
            ->where('status', 1)
            ->whereIn('gateway', ['pix', 'PIX', 'Pix'])
            ->whereBetween('created_at', [$dateRange['dataInicial'], $dateRange['dataFinal']])
            ->sum('amount');

        $total_out_hoje = Transactions::where('type', 1)
            ->where('status', 1)
            ->whereBetween('created_at', [$dateRange['dataInicial'], $dateRange['dataFinal']])
            ->sum('amount');

        // Saques normais (excluindo afiliados)
        $total_out_normal_hoje = Transactions::where('type', 1)
            ->where('status', 1)
            ->where(function($query) {
                $query->where('isaf', '!=', 1)
                    ->orWhereNull('isaf');
            })
            ->whereBetween('created_at', [$dateRange['dataInicial'], $dateRange['dataFinal']])
            ->sum('amount');

        // Saques de afiliados
        $total_out_afiliados_hoje = Transactions::where('type', 1)
            ->where('status', 1)
            ->where('isaf', 1)
            ->whereBetween('created_at', [$dateRange['dataInicial'], $dateRange['dataFinal']])
            ->sum('amount');

        // Saldo manual adicionado (baseado nos logs de adição de saldo)
        $total_manual_hoje = \App\Models\Admin\Logs::where('field_name', 'Adição de Saldo')
            ->whereBetween('created_at', [$dateRange['dataInicial'], $dateRange['dataFinal']])
            ->get()
            ->sum(function($log) {
                // Extrair valor adicionado do log
                preg_match('/Saldo adicionado: R\$ ([\d.,]+)/', $log->log, $matches);
                if (isset($matches[1])) {
                    return floatval(str_replace(['.', ','], ['', '.'], $matches[1]));
                }
                return 0;
            });

        return [
            'total_in_hoje' => $total_in_hoje,
            'total_pix_hoje' => $total_pix_hoje,
            'total_out_hoje' => $total_out_hoje,
            'total_out_normal_hoje' => $total_out_normal_hoje,
            'total_out_afiliados_hoje' => $total_out_afiliados_hoje,
            'total_manual_hoje' => $total_manual_hoje,
            'total_hoje' => $total_in_hoje - $total_out_hoje
        ];
    }

    /**
     * Retorna dados de transações PIX via AJAX
     */
    public function getPixTransactions(Request $request)
    {
        try {
            // Verificar se há filtros de data customizados
            $period = $request->input('period');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            if ($period == "") {
                $dateRange['dataInicial'] = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay()->format('Y-m-d H:i:s');
                $dateRange['dataFinal'] = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay()->format('Y-m-d H:i:s');
            }elseif ($period == "hoje") {
                $dateRange['dataInicial'] = now()->startOfDay()->format('Y-m-d H:i:s');
                $dateRange['dataFinal'] = now()->endOfDay()->format('Y-m-d H:i:s');
            }elseif (($period = "7") or ($period = "15") or ($period = "30") or ($period = "geral")) {
                // Usar período padrão
                $dateRange = $this->getDateRange($request);
                $period = $request->input('period', 'hoje');

                // Definir período baseado no parâmetro
                switch($period) {
                    case '7':
                        $dateRange['dataInicial'] = Carbon::now()->subDays(7);
                        $dateRange['dataFinal'] = Carbon::now();
                        break;
                    case '15':
                        $dateRange['dataInicial'] = Carbon::now()->subDays(15);
                        $dateRange['dataFinal'] = Carbon::now();
                        break;
                    case '30':
                        $dateRange['dataInicial'] = Carbon::now()->subDays(30);
                        $dateRange['dataFinal'] = Carbon::now();
                        break;
                    case 'geral':
                        $dateRange['dataInicial'] = Carbon::create(2020, 1, 1);
                        $dateRange['dataFinal'] = Carbon::now();
                        break;
                }
            }

            $query = Transactions::with('user')
                ->where('type', 0)
                ->where('status', 1)
                ->whereBetween('created_at', [$dateRange['dataInicial'], $dateRange['dataFinal']])
                ->orderBy('created_at', 'desc');

            return \Yajra\DataTables\Facades\DataTables::of($query)
                ->addColumn('usuario', function ($row) {
                    return $row->user ? $row->user->name : 'Usuário não encontrado';
                })
                ->addColumn('valor', function ($row) {
                    return 'R$ ' . number_format($row->amount, 2, ',', '.');
                })
                ->addColumn('gateway', function ($row) {
                    return $row->gateway;
                })
                ->addColumn('status', function ($row) {
                    return $row->status == 1 ? '<span class="badge badge-success">Aprovado</span>' : '<span class="badge badge-danger">Pendente</span>';
                })
                ->addColumn('data', function ($row) {
                    return $row->created_at->format('d/m/Y H:i:s');
                })
                ->rawColumns(['status'])
                ->make(true);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao carregar transações PIX'], 500);
        }
    }

    /**
     * Retorna dados de saldo manual via AJAX
     */
    public function getManualTransactions(Request $request)
    {
        try {
            // Verificar se há filtros de data customizados
            $period = $request->input('period');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            if ($period == "") {
                $dateRange['dataInicial'] = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay()->format('Y-m-d H:i:s');
                $dateRange['dataFinal'] = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay()->format('Y-m-d H:i:s');
            }elseif ($period == "hoje") {
                $dateRange['dataInicial'] = now()->startOfDay()->format('Y-m-d H:i:s');
                $dateRange['dataFinal'] = now()->endOfDay()->format('Y-m-d H:i:s');
            }elseif (($period = "7") or ($period = "15") or ($period = "30") or ($period = "geral")) {
                // Usar período padrão
                $dateRange = $this->getDateRange($request);
                $period = $request->input('period', 'hoje');

                // Definir período baseado no parâmetro
                switch($period) {
                    case '7':
                        $dateRange['dataInicial'] = Carbon::now()->subDays(7);
                        $dateRange['dataFinal'] = Carbon::now();
                        break;
                    case '15':
                        $dateRange['dataInicial'] = Carbon::now()->subDays(15);
                        $dateRange['dataFinal'] = Carbon::now();
                        break;
                    case '30':
                        $dateRange['dataInicial'] = Carbon::now()->subDays(30);
                        $dateRange['dataFinal'] = Carbon::now();
                        break;
                    case 'geral':
                        $dateRange['dataInicial'] = Carbon::create(2020, 1, 1);
                        $dateRange['dataFinal'] = Carbon::now();
                        break;
                }
            }

            $query = \App\Models\Admin\Logs::with('user')
                ->where('field_name', 'Adição de Saldo')
                ->whereBetween('created_at', [$dateRange['dataInicial'], $dateRange['dataFinal']])
                ->orderBy('created_at', 'desc');

            return \Yajra\DataTables\Facades\DataTables::of($query)
                ->addColumn('usuario', function ($row) {
                    return $row->user ? $row->user->name : 'Usuário não encontrado';
                })
                ->addColumn('valor', function ($row) {
                    preg_match('/Saldo adicionado: R\$ ([\d.,]+)/', $row->log, $matches);
                    $valor = isset($matches[1]) ? $matches[1] : '0,00';
                    return 'R$ ' . $valor;
                })
                ->addColumn('admin', function ($row) {
                    return $row->admin_name ?? 'Sistema';
                })
                ->addColumn('observacao', function ($row) {
                    return $row->log;
                })
                ->addColumn('data', function ($row) {
                    return $row->created_at->format('d/m/Y H:i:s');
                })
                ->make(true);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao carregar saldo manual'], 500);
        }
    }

    /**
     * Retorna dados de saques normais via AJAX
     */
    public function getNormalWithdrawals(Request $request)
    {
        try {
            // Verificar se há filtros de data customizados
            $period = $request->input('period');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            if ($period == "") {
                $dateRange['dataInicial'] = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay()->format('Y-m-d H:i:s');
                $dateRange['dataFinal'] = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay()->format('Y-m-d H:i:s');
            }elseif ($period == "hoje") {
                $dateRange['dataInicial'] = now()->startOfDay()->format('Y-m-d H:i:s');
                $dateRange['dataFinal'] = now()->endOfDay()->format('Y-m-d H:i:s');
            }elseif (($period = "7") or ($period = "15") or ($period = "30") or ($period = "geral")) {
                // Usar período padrão
                $dateRange = $this->getDateRange($request);
                $period = $request->input('period', 'hoje');

                // Definir período baseado no parâmetro
                switch($period) {
                    case '7':
                        $dateRange['dataInicial'] = Carbon::now()->subDays(7);
                        $dateRange['dataFinal'] = Carbon::now();
                        break;
                    case '15':
                        $dateRange['dataInicial'] = Carbon::now()->subDays(15);
                        $dateRange['dataFinal'] = Carbon::now();
                        break;
                    case '30':
                        $dateRange['dataInicial'] = Carbon::now()->subDays(30);
                        $dateRange['dataFinal'] = Carbon::now();
                        break;
                    case 'geral':
                        $dateRange['dataInicial'] = Carbon::create(2020, 1, 1);
                        $dateRange['dataFinal'] = Carbon::now();
                        break;
                }
            }

            $query = Transactions::with('user')
                ->where('type', 1)
                ->where('status', 1)
                ->where(function($query) {
                    $query->where('isaf', '!=', 1)
                        ->orWhereNull('isaf');
                })
                ->whereBetween('created_at', [$dateRange['dataInicial'], $dateRange['dataFinal']])
                ->orderBy('created_at', 'desc');

            return \Yajra\DataTables\Facades\DataTables::of($query)
                ->addColumn('usuario', function ($row) {
                    return $row->user ? $row->user->name : 'Usuário não encontrado';
                })
                ->addColumn('valor', function ($row) {
                    return 'R$ ' . number_format($row->amount, 2, ',', '.');
                })
                ->addColumn('gateway', function ($row) {
                    return $row->gateway;
                })
                ->addColumn('status', function ($row) {
                    return $row->status == 1 ? '<span class="badge badge-success">Aprovado</span>' : '<span class="badge badge-danger">Pendente</span>';
                })
                ->addColumn('data', function ($row) {
                    return $row->created_at->format('d/m/Y H:i:s');
                })
                ->rawColumns(['status'])
                ->make(true);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao carregar saques normais'], 500);
        }
    }

    /**
     * Retorna dados de saques de afiliados via AJAX
     */
    public function getAffiliateWithdrawals(Request $request)
    {
        try {
            // Verificar se há filtros de data customizados
            $period = $request->input('period');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            if ($period == "") {
                $dateRange['dataInicial'] = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay()->format('Y-m-d H:i:s');
                $dateRange['dataFinal'] = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay()->format('Y-m-d H:i:s');
            }elseif ($period == "hoje") {
                $dateRange['dataInicial'] = now()->startOfDay()->format('Y-m-d H:i:s');
                $dateRange['dataFinal'] = now()->endOfDay()->format('Y-m-d H:i:s');
            }elseif (($period = "7") or ($period = "15") or ($period = "30") or ($period = "geral")) {
                // Usar período padrão
                $dateRange = $this->getDateRange($request);
                $period = $request->input('period', 'hoje');

                // Definir período baseado no parâmetro
                switch($period) {
                    case '7':
                        $dateRange['dataInicial'] = Carbon::now()->subDays(7);
                        $dateRange['dataFinal'] = Carbon::now();
                        break;
                    case '15':
                        $dateRange['dataInicial'] = Carbon::now()->subDays(15);
                        $dateRange['dataFinal'] = Carbon::now();
                        break;
                    case '30':
                        $dateRange['dataInicial'] = Carbon::now()->subDays(30);
                        $dateRange['dataFinal'] = Carbon::now();
                        break;
                    case 'geral':
                        $dateRange['dataInicial'] = Carbon::create(2020, 1, 1);
                        $dateRange['dataFinal'] = Carbon::now();
                        break;
                }
            }

            $query = Transactions::with('user')
                ->where('type', 1)
                ->where('status', 1)
                ->where('isaf', 1)
                ->whereBetween('created_at', [$dateRange['dataInicial'], $dateRange['dataFinal']])
                ->orderBy('created_at', 'desc');

            return \Yajra\DataTables\Facades\DataTables::of($query)
                ->addColumn('usuario', function ($row) {
                    return $row->user ? $row->user->name : 'Usuário não encontrado';
                })
                ->addColumn('valor', function ($row) {
                    return 'R$ ' . number_format($row->amount, 2, ',', '.');
                })
                ->addColumn('gateway', function ($row) {
                    return $row->gateway;
                })
                ->addColumn('tipo', function ($row) {
                    return '<span class="badge badge-info">Afiliado</span>';
                })
                ->addColumn('status', function ($row) {
                    return $row->status == 1 ? '<span class="badge badge-success">Aprovado</span>' : '<span class="badge badge-danger">Pendente</span>';
                })
                ->addColumn('data', function ($row) {
                    return $row->created_at->format('d/m/Y H:i:s');
                })
                ->rawColumns(['tipo', 'status'])
                ->make(true);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao carregar saques de afiliados'], 500);
        }
    }

    /**
     * Dados do cassino (apostas e prêmios)
     */
    /**
     * Dados de cassino
     * OTIMIZADO: Apenas calcula para o período filtrado
     */
    /**
     * Dados de cassino
     * OTIMIZADO: Cada linha é uma aposta independente
     * - action = 'loss' → Apostas perdidas
     * - action = 'win' → Apostas ganhadoras
     */
    private function getCasinoData(array $dateRange)
    {
        // Apostas perdidas (action = loss)
        $apostasPerdidasCassino = GameHistory::whereBetween('created_at', [$dateRange['dataInicial'], $dateRange['dataFinal']])
            ->where('action', 'loss')
            ->sum('amount');

        // Apostas ganhadoras (action = win)
        $apostasGanhadorasCassino = GameHistory::whereBetween('created_at', [$dateRange['dataInicial'], $dateRange['dataFinal']])
            ->where('action', 'win')
            ->sum('amount');

        // Total de apostas = perdidas + ganhadoras
        $totalApostasCassino = $apostasGanhadorasCassino + $apostasPerdidasCassino;

        // Líquido Cassino (apostas perdidas - prêmios ganhos)
        $liquidoCassino = $apostasGanhadorasCassino - $apostasPerdidasCassino;

        return [
            'totalApostasCassino' => $totalApostasCassino,
            'apostasPerdidasCassino' => $apostasPerdidasCassino,
            'apostasGanhadorasCassino' => $apostasGanhadorasCassino,
            'ggrConsumidoCassino' => $liquidoCassino,
            'cassino_loss_today' => $apostasPerdidasCassino,  // Total apostas (loss + win)
            'cassino_win_today' => $apostasGanhadorasCassino,  // Prêmios (win)
            'cassino_total_today' => $liquidoCassino  // Líquido (loss - win)
        ];
    }

    /**
     * Dados dos sports (apostas e prêmios)
     */
    private function getSportsData(array $dateRange)
    {
        // Obter o provedor de sports ativo das configurações
        $settings = Helper::getSetting();
        $activeProvider = $settings->sports_api_provider ?? 'digitain';

        // Sports - Apostas e Prêmios baseados no provedor ativo
        if ($activeProvider === 'betby') {
            // BetBy: operation sempre é 'make', usar status para determinar resultado
            // Valores Betby vêm em centavos, precisam ser divididos por 100
            $totalApostasSportsDebit = SportBetSummary::whereBetween('created_at', [$dateRange['dataInicial'], $dateRange['dataFinal']])
                ->where('provider', 'betby')
                ->where('operation', 'make')  // Apostas feitas
                ->whereRaw('LOWER(status) != ?', ['discard'])  // Desconsiderar apostas rejeitadas (case-insensitive)
                ->sum('amount') / 100;

            $totalPremiosSports = SportBetSummary::whereBetween('created_at', [$dateRange['dataInicial'], $dateRange['dataFinal']])
                ->where('provider', 'betby')
                ->whereRaw('LOWER(status) = ?', ['win'])  // Apostas ganhas (case-insensitive)
                ->sum('amount_win') / 100;
        } else {
            // Digitain: usar operation tradicional
            // Card Apostas: operation = 'debit' (sem filtro de status, pois queremos todas as apostas feitas)
            $totalApostasSportsDebit = SportBetSummary::whereBetween('created_at', [$dateRange['dataInicial'], $dateRange['dataFinal']])
                ->where('operation', 'debit')
                ->where('provider', 'digitain')
                ->sum('amount');

            // Card Prêmios: operation = 'credit' com status 'Completed'
            $totalPremiosSports = SportBetSummary::whereBetween('created_at', [$dateRange['dataInicial'], $dateRange['dataFinal']])
                ->where('operation', 'credit')
                ->where('status', 'Completed')
                ->where('provider', 'digitain')
                ->sum('amount');
        }

        // GGR Consumido Sports (apostas - prêmios)
        $ggrConsumidoSports = $totalApostasSportsDebit - $totalPremiosSports;

        // Calcular bilhetes em aberto para o período atual
        $bilhetesAbertosHoje = $this->getSportsBilhetesAbertos($dateRange);

        return [
            'totalApostasSports' => $totalApostasSportsDebit,
            'totalApostasSportsDebit' => $totalApostasSportsDebit,
            'totalPremiosSports' => $totalPremiosSports,
            'ggrConsumidoSports' => $ggrConsumidoSports,
            'sports_bets_today' => $totalApostasSportsDebit,
            'sports_wins_today' => $totalPremiosSports,
            'sports_total_today' => $ggrConsumidoSports,
            // Bilhetes em aberto
            'bilhetes_abertos_hoje' => $bilhetesAbertosHoje,
            // Provider ativo
            'sports_active_provider' => strtoupper($activeProvider)
        ];
    }

    /**
     * Calcula bilhetes em aberto para esportes
     */
    private function getSportsBilhetesAbertos(array $dateRange)
    {
        // Obter o provedor de sports ativo das configurações
        $settings = Helper::getSetting();
        $activeProvider = $settings->sports_api_provider ?? 'digitain';

        if ($activeProvider === 'betby') {
            // BetBy: Lógica simplificada - cada registro já tem o status final
            // Contar apenas registros com status 'pending' (apostas em aberto)
            // Usar whereRaw para case-insensitive
            $bilhetesAbertos = SportBetSummary::whereBetween('created_at', [$dateRange['dataInicial'], $dateRange['dataFinal']])
                ->where('provider', 'betby')
                ->where('operation', 'make')
                ->whereRaw('LOWER(status) = ?', ['pending'])  // Case-insensitive
                ->count();

            $valorApostasAbertas = SportBetSummary::whereBetween('created_at', [$dateRange['dataInicial'], $dateRange['dataFinal']])
                ->where('provider', 'betby')
                ->where('operation', 'make')
                ->whereRaw('LOWER(status) = ?', ['pending'])  // Case-insensitive
                ->sum('amount') / 100;  // Converter de centavos para reais

            return [
                'quantidade' => $bilhetesAbertos,
                'valor' => $valorApostasAbertas
            ];
        }

        // Digitain: OTIMIZADO - Uma única query com LEFT JOIN
        // Buscar apostas (debit) que NÃO têm resultado (lose ou credit)
        $result = \Illuminate\Support\Facades\DB::table('sportbetsummary as debit')
            ->leftJoin('sportbetsummary as resultado', function($join) use ($activeProvider) {
                $join->on('debit.transactionId', '=', 'resultado.transactionId')
                     ->on('debit.user_id', '=', 'resultado.user_id')
                     ->where('resultado.provider', '=', $activeProvider)
                     ->whereIn('resultado.operation', ['lose', 'credit']);
            })
            ->whereBetween('debit.created_at', [$dateRange['dataInicial'], $dateRange['dataFinal']])
            ->where('debit.provider', $activeProvider)
            ->where('debit.operation', 'debit')
            ->whereNull('resultado.id')  // Apenas apostas SEM resultado
            ->selectRaw('COUNT(*) as quantidade, SUM(debit.amount) as valor')
            ->first();

        return [
            'quantidade' => $result->quantidade ?? 0,
            'valor' => $result->valor ?? 0
        ];
    }

    /**
     * Cálculos de GGR (Gross Gaming Revenue)
     */
    private function getGGRData(array $dateRange, Request $request)
    {
        // Determinar seção padrão baseada nas configurações ativas
        $ggrDisplaySettings = $this->getGGRDisplaySettings();
        $defaultSection = 'clones'; // padrão inicial

        if (!$ggrDisplaySettings['showGgrCassinoClones'] && $ggrDisplaySettings['showGgrCassinoOriginais']) {
            $defaultSection = 'originais';
        } elseif (!$ggrDisplaySettings['showGgrCassinoClones'] && !$ggrDisplaySettings['showGgrCassinoOriginais'] && $ggrDisplaySettings['showGgrEsportes']) {
            $defaultSection = 'esportes';
        }

        $providerCassino = $request->input('provider_cassino', $defaultSection);

        // Obter taxas de configuração
        $rateClones = config('ggr.rates.clones', 0.10);
        $rateOriginais = config('ggr.rates.originais', 0.20);
        $rateEsportes = config('ggr.rates.esportes', 0.20);

        // USAR O MESMO PERÍODO DOS OUTROS CARDS (dataInicial e dataFinal do filtro principal)
        $ggrDateRange = $dateRange;

        // Dados baseados na seção selecionada
        $apostasPerdidasCassino = 0;
        $apostasGanhadorasCassino = 0;
        $ggrConsumidoCassino = 0;

        // GGR Cassino - Inove (API única)
            $apostasPerdidasCassino = GameHistory::whereBetween('created_at', [$ggrDateRange['dataInicial'], $ggrDateRange['dataFinal']])
                ->where('action', 'loss')
                ->sum('amount') ?: 0;
            $apostasGanhadorasCassino = GameHistory::whereBetween('created_at', [$ggrDateRange['dataInicial'], $ggrDateRange['dataFinal']])
                ->where('action', 'win')
                ->sum('amount') ?: 0;
            // GGR = (Perdas - Ganhos) * Taxa da Provedora
        $netGamingInove = $apostasPerdidasCassino - $apostasGanhadorasCassino;
        $ggrConsumidoCassino = $netGamingInove * $rateClones;

        // Dados de Esportes
        // Obter o provedor de sports ativo das configurações
        $settings = Helper::getSetting();
        $activeProvider = $settings->sports_api_provider ?? 'digitain';

        if ($activeProvider === 'betby') {
            // BetBy: GGR - Apostas Perdidas (status = 'lose')
            $totalApostasSportsDebit = (SportBetSummary::whereBetween('created_at', [$ggrDateRange['dataInicial'], $ggrDateRange['dataFinal']])
                ->where('provider', 'betby')
                ->where('operation', 'make')
                ->whereRaw('LOWER(status) = ?', ['lose'])  // Apenas perdidas
                ->sum('amount') ?: 0) / 100;

            // GGR - Apostas Ganhadoras (status = 'win')
            $totalPremiosSports = (SportBetSummary::whereBetween('created_at', [$ggrDateRange['dataInicial'], $ggrDateRange['dataFinal']])
                ->where('provider', 'betby')
                ->where('operation', 'make')
                ->whereRaw('LOWER(status) = ?', ['win'])
                ->sum('amount_win') ?: 0) / 100;
        } else {
            // Digitain: Buscar apostas perdidas
            // 1. Buscar todos os transactionId com operation = 'lose' (apostas que perderam)
            $transactionIdsLose = SportBetSummary::whereBetween('created_at', [$ggrDateRange['dataInicial'], $ggrDateRange['dataFinal']])
                ->where('operation', 'lose')
                ->where('provider', 'digitain')
                ->pluck('transactionId');

            // 2. Buscar os valores das apostas (debit) correspondentes a esses transactionId
            $totalApostasSportsDebit = SportBetSummary::whereIn('transactionId', $transactionIdsLose)
                ->where('operation', 'debit')
                ->where('provider', 'digitain')
                ->sum('amount') ?: 0;

            // GGR - Apostas Ganhadoras: operation = 'credit' com status 'Completed'
            $totalPremiosSports = SportBetSummary::whereBetween('created_at', [$ggrDateRange['dataInicial'], $ggrDateRange['dataFinal']])
                ->where('operation', 'credit')
                ->whereRaw('LOWER(status) = ?', ['completed'])
                ->where('provider', 'digitain')
                ->sum('amount') ?: 0;
        }

        // GGR Esportes = (Apostas Perdidas - Prêmios) * Taxa da Provedora
        $netGamingSports = $totalApostasSportsDebit - $totalPremiosSports;
        $ggrConsumidoSports = $netGamingSports * $rateEsportes;

        return [
            'apostasPerdidasCassino' => $apostasPerdidasCassino,
            'apostasGanhadorasCassino' => $apostasGanhadorasCassino,
            'ggrConsumidoCassino' => $ggrConsumidoCassino,
            'apostasPerdidasSports' => $totalApostasSportsDebit,  // Apostas que perderam (lose)
            'totalApostasSportsDebit' => $totalApostasSportsDebit, // Mantém compatibilidade
            'totalPremiosSports' => $totalPremiosSports,
            'ggrConsumidoSports' => $ggrConsumidoSports,
            'providerCassino' => $providerCassino,
            'ggrRates' => [
                'clones' => $rateClones,
                'originais' => $rateOriginais,
                'esportes' => $rateEsportes
            ],
            'ggrTitle' => $this->buildGGRTitle($rateClones, $rateOriginais, $rateEsportes)
        ];
    }

    /**
     * Dados para gráficos
     */
    private function getChartData()
    {
        // Dados dos últimos 7 dias para gráfico de usuários
        $dates = collect();
        $dataTotalUsers = collect();
        $dataAfiliados = collect();
        $dataDemoAgents = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);

            $totalUsersCount = User::whereDate('created_at', $date)->count();
            $afiliadosCount = User::whereDate('created_at', $date)->whereHas('affiliates')->count();
            $demoAgentsCount = User::whereDate('created_at', $date)->where('is_demo_agent', 1)->count();

            $dates->push($date->format('d/m'));
            $dataTotalUsers->push($totalUsersCount);
            $dataAfiliados->push($afiliadosCount);
            $dataDemoAgents->push($demoAgentsCount);
        }

        // Dados mensais de visitantes para o ano atual
        $startDate = Carbon::now()->startOfYear();
        $endDate = Carbon::now()->endOfYear();

        // Visitantes orgânicos
        $monthlyCounts = VisitLogs::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('referer')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month')
            ->map(function ($item) {
                return $item->count;
            })
            ->toArray();

        $countsorg = array_fill(1, 12, 0);
        foreach ($monthlyCounts as $month => $count) {
            $countsorg[$month] = $count;
        }

        // Visitantes diretos
        $monthlyCountsDirect = VisitLogs::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNull('referer')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month')
            ->map(function ($item) {
                return $item->count;
            })
            ->toArray();

        $countsdir = array_fill(1, 12, 0);
        foreach ($monthlyCountsDirect as $month => $count) {
            $countsdir[$month] = $count;
        }

        return [
            'chartLabels' => $dates->toArray(),
            'chartTotalUsers' => $dataTotalUsers->toArray(),
            'chartAfiliados' => $dataAfiliados->toArray(),
            'chartDemoAgents' => $dataDemoAgents->toArray(),
            'dadosOrganico' => json_encode(array_values($countsorg)),
            'dadosDirect' => json_encode(array_values($countsdir)),
            'seriesData' => json_encode($dataTotalUsers->toArray()),
            'labelsData' => json_encode($dates->toArray())
        ];
    }


    /**
     * Constrói o título dinâmico do GGR com porcentagens ativas
     */
    private function buildGGRTitle($rateClones, $rateOriginais, $rateEsportes)
    {
        $showClones = config('ggr.show_ggr_cassino_clones', true);
        $showOriginais = config('ggr.show_ggr_cassino_originais', true);
        $showEsportes = config('ggr.show_ggr_esportes', true);

        $titleParts = [];

        if ($showClones) {
            $titleParts[] = 'Clones: ' . number_format($rateClones * 100, 1) . '%';
        }

        if ($showOriginais) {
            $titleParts[] = 'Originais: ' . number_format($rateOriginais * 100, 1) . '%';
        }

        if ($showEsportes) {
            $titleParts[] = 'Sports: ' . number_format($rateEsportes * 100, 1) . '%';
        }

        if (empty($titleParts)) {
            return 'GGR (Net Gaming × Taxa) - Nenhuma seção ativa';
        }

        return 'GGR (Net Gaming × Taxa) - ' . implode(' | ', $titleParts);
    }

    /**
     * Configurações de exibição do GGR
     */
    private function getGGRDisplaySettings()
    {
        return [
            'showGgrCassinoClones' => config('ggr.show_ggr_cassino_clones', true),
            'showGgrCassinoOriginais' => config('ggr.show_ggr_cassino_originais', true),
            'showGgrEsportes' => config('ggr.show_ggr_esportes', true)
        ];
    }
}

