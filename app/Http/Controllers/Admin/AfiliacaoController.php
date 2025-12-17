<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Core as Helper;

use App\Http\Controllers\Controller;
use App\Models\AffiliatesHistory;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Setting;
use App\Models\GameHistory;
use App\Models\Transactions;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;

use App\Models\Gateways;
use App\Models\Admin\Logs;
use Yajra\DataTables\Facades\DataTables;

class AfiliacaoController extends Controller
{
    public function afiliadosAfiliados(Request $request)
    {
        $a = $request->input('aff', '');
        $b = $request->input('di', Carbon::now()->subDays(7)->format('Y-m-d'));
        $c = $request->input('df', Carbon::now()->format('Y-m-d'));

        // Renomeando variáveis para maior clareza
        $nomeUsuario = $a;
        $dataInicial = $b;
        $dataFinal = $c;

        return view('admin.afiliacao.afiliados_afiliados', compact('nomeUsuario', 'dataInicial', 'dataFinal', 'a', 'b', 'c'));
    }

    public function gerentesAfiliados(Request $request)
    {
        $a = $request->input('aff', '');
        $b = $request->input('di', Carbon::now()->subDays(7)->format('Y-m-d'));
        $c = $request->input('df', Carbon::now()->format('Y-m-d'));

        // Renomeando variáveis para maior clareza
        $nomeUsuario = $a;
        $dataInicial = $b;
        $dataFinal = $c;

        return view('admin.afiliacao.gerentes_afiliados', compact('nomeUsuario', 'dataInicial', 'dataFinal', 'a', 'b', 'c'));
    }

    public function configAfiliados()
    {
        $Settings = Setting::first();
        return view('admin.afiliacao.config_afiliados', compact('Settings'));
    }

    public function salvarConfigAfiliados(Request $request)
    {
        $settings = Setting::first();

        $settings->min_saque_af = $request->input('min_saque_af');
        $settings->max_saque_af = $request->input('max_saque_af');
        $settings->max_saque_aut_af = $request->input('max_saque_aut_af');
        $settings->cpaenabled = $request->has('cpaenabled') ? 1 : 0;
        $settings->aff_min_dep = $request->input('aff_min_dep');
        $settings->aff_amount = $request->input('aff_amount');
        $settings->revenabled = $request->has('revenabled') ? 1 : 0;
        $settings->percent_aff = $request->input('percent_aff');

        $settings->save();

        return response()->json(['success' => true]);
    }

    public function estatisticasAfiliados(Request $request)
    {
        $nomeAfiliado = $request->input('aff', '');
        $dataInicial = $request->input('di', '');
        $dataFinal = $request->input('df', '');

        // Preparar datas para filtros
        $bb = $dataInicial ? Carbon::parse($dataInicial)->startOfDay() : null;
        $cc = $dataFinal ? Carbon::parse($dataFinal)->endOfDay() : null;

        // Buscar usuários afiliados baseado no filtro
        $affiliateUsersQuery = User::where('inviter', '!=', 0);

        // Lógica corrigida para filtro por afiliado
        if (!empty($nomeAfiliado)) {
            // Primeiro, tentar encontrar o afiliado (quem convidou)
            $afiliado = User::where('name', 'LIKE', '%' . $nomeAfiliado . '%')->first();

            if ($afiliado) {
                // Se encontrou o afiliado, buscar apenas os usuários convidados por ele
                $affiliateUsersQuery->where('inviter', $afiliado->id);
            } else {
                // Se não encontrou como afiliado, buscar como usuário afiliado específico
                $userAfiliado = User::where('name', 'LIKE', '%' . $nomeAfiliado . '%')
                    ->where('inviter', '!=', 0)
                    ->first();

                if ($userAfiliado) {
                    $affiliateUsersQuery->where('id', $userAfiliado->id);
                } else {
                    // Se não encontrou nenhum, retornar resultado vazio
                    $affiliateUsersQuery->where('id', 0); // Força resultado vazio
                }
            }
        }

        $affiliateUsers = $affiliateUsersQuery->get();
        $affiliateUserIds = $affiliateUsers->pluck('id')->toArray();

        // Se não há usuários afiliados, retornar zeros
        if (empty($affiliateUserIds)) {
            $data = [
                'winAmount' => 0, 'winCount' => 0, 'lossAmount' => 0, 'totalAmount' => 0, 'totalCount' => 0,
                'depositAmount' => 0, 'depositCount' => 0, 'withdrawAmount' => 0, 'withdrawCount' => 0,
                'totalTransAmount' => 0, 'totalTransCount' => 0, 'casinoProfit' => 0, 'transactionProfit' => 0,
                'total_rev' => 0, 'count_rev' => 0, 'total_all' => 0,
                'SomaDep' => 0, 'CountDep' => 0, 'SomaSaq' => 0, 'CountSaq' => 0,
                'SomaAllDS' => 0, 'CountAllDS' => 0, 'TotalDS' => 0, 'TotalLiqDS' => 0,
                'nomeAfiliado' => $nomeAfiliado, 'dataInicial' => $dataInicial, 'dataFinal' => $dataFinal
            ];
            return view('admin.afiliacao.estatisticas_afiliados', $data);
        }

        // Query para histórico de jogos (corrigido para usar 'bet' ao invés de 'loss')
        $gameHistoryQuery = GameHistory::whereIn('user_id', $affiliateUserIds);
        if ($bb && $cc) {
            $gameHistoryQuery->whereBetween('created_at', [$bb, $cc]);
        } elseif ($bb && !$cc) {
            $gameHistoryQuery->whereBetween('created_at', [$bb, Carbon::now()]);
        }

        $winAmount = (clone $gameHistoryQuery)->where('action', 'win')->sum('amount');
        $winCount = (clone $gameHistoryQuery)->where('action', 'win')->count();

        // Correto: apostas são 'bet', não 'loss'
        $betAmount = (clone $gameHistoryQuery)->where('action', 'bet')->sum('amount');
        $totalAmount = (clone $gameHistoryQuery)->sum('amount');
        $totalCount = (clone $gameHistoryQuery)->count();

        // Query para transações
        $transactionQuery = Transactions::whereIn('user_id', $affiliateUserIds)->where('status', 1);
        if ($bb && $cc) {
            $transactionQuery->whereBetween('created_at', [$bb, $cc]);
        } elseif ($bb && !$cc) {
            $transactionQuery->whereBetween('created_at', [$bb, Carbon::now()]);
        }

        $depositAmount = (clone $transactionQuery)->where('type', 0)->sum('amount');
        $depositCount = (clone $transactionQuery)->where('type', 0)->count();
        $withdrawAmount = (clone $transactionQuery)->where('type', 1)->sum('amount');
        $withdrawCount = (clone $transactionQuery)->where('type', 1)->count();
        $totalTransAmount = (clone $transactionQuery)->sum('amount');
        $totalTransCount = (clone $transactionQuery)->count();

        // Query para histórico de afiliados (RevShare) - corrigido o filtro
        $revShareQuery = AffiliatesHistory::where('game', '!=', 'CPA');

        // Filtrar por afiliado nas comissões se especificado
        if (!empty($nomeAfiliado) && isset($afiliado)) {
            $revShareQuery->where('inviter', $afiliado->id);
        } elseif (!empty($nomeAfiliado) && isset($userAfiliado)) {
            // Se filtrou por usuário específico, buscar comissões desse usuário
            $revShareQuery->where('user_id', $userAfiliado->id);
        }

        if ($bb && $cc) {
            $revShareQuery->whereBetween('created_at', [$bb, $cc]);
        } elseif ($bb && !$cc) {
            $revShareQuery->whereBetween('created_at', [$bb, Carbon::now()]);
        }

        $total_rev = (clone $revShareQuery)->sum('amount');
        $count_rev = (clone $revShareQuery)->count();

        // Cálculos finais
        $casinoProfit = $betAmount - $winAmount; // Corrigido: apostas - prêmios
        $transactionProfit = $depositAmount - $withdrawAmount;
        $total_all = $casinoProfit + $transactionProfit + $total_rev;

        // Preparar dados para a view
        $data = [
            'winAmount' => $winAmount,
            'winCount' => $winCount,
            'lossAmount' => $betAmount, // Mantendo compatibilidade com a view
            'totalAmount' => $totalAmount,
            'totalCount' => $totalCount,
            'depositAmount' => $depositAmount,
            'depositCount' => $depositCount,
            'withdrawAmount' => $withdrawAmount,
            'withdrawCount' => $withdrawCount,
            'totalTransAmount' => $totalTransAmount,
            'totalTransCount' => $totalTransCount,
            'casinoProfit' => $casinoProfit,
            'transactionProfit' => $transactionProfit,
            'total_rev' => $total_rev,
            'count_rev' => $count_rev,
            'total_all' => $total_all,
            'SomaDep' => $depositAmount,
            'CountDep' => $depositCount,
            'SomaSaq' => $withdrawAmount,
            'CountSaq' => $withdrawCount,
            'SomaAllDS' => $totalTransAmount,
            'CountAllDS' => $totalTransCount,
            'TotalDS' => $depositAmount - $withdrawAmount,
            'TotalLiqDS' => $transactionProfit,
            'nomeAfiliado' => $nomeAfiliado,
            'dataInicial' => $dataInicial,
            'dataFinal' => $dataFinal,
            'affiliateUsers' => $affiliateUsers // Para o select de afiliados
        ];

        return view('admin.afiliacao.estatisticas_afiliados', $data);
    }

    public function estatisticasGerente(Request $request)
    {
        $a = $request->input('aff', '');
        $b = $request->input('di', Carbon::now()->subDays(7)->format('Y-m-d'));
        $c = $request->input('df', Carbon::now()->format('Y-m-d'));

        // Lógica para calcular estatísticas
        $SomaSaq = 0;
        $SomaDep = 0;
        $CountAll = 0;
        $TotalLiq = 0;
        $total_all = 0;
        $SomaAll = 0;
        $SomaWin = 0;
        $CountWin = 0;
        $TotalCas = 0;
        $CountDep = 0;
        $CountSaq = 0;
        $TotalDS = 0;
        $TotalLiqDS = 0;

        return view('admin.afiliacao.estatisticas_gerente', compact('a', 'b', 'c', 'TotalLiqDS', 'TotalDS', 'CountSaq', 'CountDep', 'TotalCas', 'SomaDep', 'SomaSaq', 'SomaAll', 'SomaWin', 'CountWin', 'CountAll', 'TotalLiq', 'total_all'));
    }

    public function pagarAfiliado(Request $request)
    {
        Helper::CheckAdm();

        $AUser = User::where('id', $request->id)->first();

        if (!$AUser){
            return response()->json(['success' => false, 'message' => 'Usuário não encontrado!']);
        }

        if ($AUser->Wallet->refer_rewards == 0) {
            return response()->json(['success' => false, 'message' => 'Saldo do afiliado é zero!']);
        }

        $Valor = $AUser->Wallet->refer_rewards;

        // EdPay Gateway
        $Gera = Helper::GeraSaqueEdPay($AUser, $Valor);

            if (!$Gera) {
                return response()->json(["success" => false, "message" => "Ocorreu um erro, tente novamente em alguns instantes."]);
            }

        if (!isset($Gera['id']) || empty($Gera['id'])) {
                return response()->json(["success" => false, "message" => "Ocorreu um erro, tente novamente em alguns instantes."]);
            }

            $IdTransaction = $Gera['id'];
            $resultado = 'OK';

        if ($resultado == 'OK') {
            $params = [
                "user_id" => $AUser->id,
                "isaf" => 1,
                "amount" => $Valor,
                "type" => 1,
                "with_type" => "affiliate",
                "gateway" => "EdPay",
                "token" => $IdTransaction,
                "status" => 1,
                "chave_pix" => $AUser->pix,
            ];

            if ($NTransaction = Transactions::create($params)) {
                Logs::create([
                    'updated_by' => Auth::id(),
                    'user_id' => $request->id,
                    'log' => sprintf("Pagamento de afiliado: Foi pago R$ %.2f ao afiliado %s", $Valor, $AUser->name),
                    'type' => 1,
                ]);

                $AUser->Wallet->refer_rewards = 0;
                $AUser->Wallet->save();

                return response()->json(["success" => true, "message" => "Pagamento gerado com sucesso!"]);
            } else {
                return response()->json(["success" => false, "message" => "Ocorreu um erro, tente novamente em alguns instantes."]);
            }
        } else {
            return response()->json(["success" => false, "message" => "Ocorreu um erro, tente novamente em alguns instantes."]);
        }

    }

    /**
     * Fornece dados para a tabela de afiliados
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function afiliadosData(Request $request)
    {
        $UserLogado = auth()->user();

        if (($UserLogado->is_admin == 1) or ($UserLogado->is_admin == 2)){
            $query = User::whereHas('affiliates');
        }elseif ($UserLogado->is_admin == 3) {
            $query = User::where('inviter', $UserLogado->id);
        }

        // Aplicar filtro por data inicial e final
        if ($request->input('dataInicial') && $request->input('dataFinal')) {
            $query->whereBetween('updated_at', [
                Carbon::parse($request->input('dataInicial'))->startOfDay(),
                Carbon::parse($request->input('dataFinal'))->endOfDay()
            ]);
        } elseif ($request->input('dataInicial') && !$request->input('dataFinal')) {
            $query->where('updated_at', '>=', Carbon::parse($request->input('dataInicial'))->startOfDay());
        }

        // Aplicar filtro por nome
        if ($request->input('nomeUsuario')) {
            $searchTerm = $request->input('nomeUsuario');
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }

        return DataTables::of($query)
            ->addColumn('nome', function ($row) use ($UserLogado) {
                if (($UserLogado->is_admin == 1) or ($UserLogado->is_admin == 2)){
                    return '<a href="javascript:void(0);" onclick="LoadAgent(\''.$row->id.'\');" data-bs-toggle="modal" data-bs-target="#tabsModal" class="bs-tooltip" title="Visualizar afiliado" data-original-title="Visualizar Usuário">'.$row->name.'</a>';
                }elseif ($UserLogado->is_admin == 3) {
                    return $row->name;
                }
            })
            ->addColumn('email', function ($row) {
                return $row->email;
            })
            ->addColumn('data', function ($row) {
                return Carbon::parse($row->updated_at)->format('d/m/Y H:i:s');
            })
            ->rawColumns(['nome'])
            ->make(true);
    }

    /**
     * Fornece dados para a tabela de gerentes de afiliados
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function gerentesData(Request $request)
    {
        $query = User::with('wallet')
            ->where('is_affiliate', 1);

        // Aplicar filtro por data inicial e final
        if ($request->input('dataInicial') && $request->input('dataFinal')) {
            $query->whereBetween('updated_at', [
                Carbon::parse($request->input('dataInicial'))->startOfDay(),
                Carbon::parse($request->input('dataFinal'))->endOfDay()
            ]);
        } elseif ($request->input('dataInicial') && !$request->input('dataFinal')) {
            $query->where('updated_at', '>=', Carbon::parse($request->input('dataInicial'))->startOfDay());
        }

        // Aplicar filtro por nome
        if ($request->input('nomeUsuario')) {
            $searchTerm = $request->input('nomeUsuario');
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }

        return DataTables::of($query)
            ->addColumn('nome', function ($row) {
                return '<a href="javascript:void(0);" onclick="LoadAgent(\''.$row->id.'\');" data-bs-toggle="modal" data-bs-target="#tabsModal" class="bs-tooltip" title="Visualizar afiliado" data-original-title="Visualizar Usuário">'.$row->name.'</a>';
            })
            ->addColumn('email', function ($row) {
                return $row->email;
            })
            ->addColumn('arrecadacao', function ($row) {
                $id = $row->id;
                $Total = $row->wallet->refer_rewards;

                if ($Total > 0) {
                    return '<span class="badge badge-light-success mb-2 me-4">'.sprintf("R$ %.2f", $Total).'</span>';
                } else {
                    return '<span class="badge badge-light-danger mb-2 me-4">'.sprintf("R$ %.2f", $Total).'</span>';
                }
            })
            ->addColumn('valor_formatado', function ($row) {
                return sprintf("R$ %.2f", $row->wallet->refer_rewards);
            })
            ->addColumn('acoes', function ($row) {
                return '<button onclick="confirmPayment(\''.$row->id.'\', \''.$row->name.'\', \''.sprintf("R$ %.2f", $row->wallet->refer_rewards).'\');" class="btn btn-success" type="button">
                            <span class="icon text-darkorange-50">
                                <i class="fa fa-check"></i>
                            </span>
                            <span class="text">Pagar Afiliado</span>
                        </button>

                        <button onclick="ExportarAfiliados(\''.$row->id.'\');" class="btn btn-info" type="button">
                            <span class="icon text-darkorange-50">
                                <i class="fa fa-database"></i>
                            </span>
                            <span class="text">Exportar Afiliados</span>
                        </button>';
            })
            ->rawColumns(['nome', 'arrecadacao', 'acoes'])
            ->make(true);
    }
}
