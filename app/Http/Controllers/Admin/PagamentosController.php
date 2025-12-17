<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Core as Helper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transactions;
use App\Models\User;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
use App\Models\Gateways;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

class PagamentosController extends Controller
{
    public function depositos(Request $request)
    {
        $queryParams = [
            'aff' => $request->input('aff', ''),
            'di' => $request->input('di', ''),
            'df' => $request->input('df', '')
        ];

        return view('admin.pagamentos.depositos', compact('queryParams'));
    }

    /**
     * Fornece dados para a tabela de depósitos
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function depositosData(Request $request)
    {
        $query = Transactions::select('transactions.*', 'users.name as usuario_nome', 'users.cpf')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->where('transactions.type', 0)
            ->where(function ($q) {
                $q->where('transactions.with_type', '!=', 'Mensalidade')
                    ->orWhereNull('transactions.with_type')
                    ->orWhere('transactions.with_type', '');
            });

        // Aplicar filtro por data inicial e final
        if ($request->input('dataInicial') && $request->input('dataFinal')) {
            $query->whereBetween('transactions.updated_at', [
                Carbon::parse($request->input('dataInicial'))->startOfDay(),
                Carbon::parse($request->input('dataFinal'))->endOfDay()
            ]);
        } elseif ($request->input('dataInicial') && !$request->input('dataFinal')) {
            $query->where('transactions.updated_at', '>=', Carbon::parse($request->input('dataInicial'))->startOfDay());
        }

        // Aplicar filtro por nome de usuário ou CPF
        if ($request->input('nomeUsuario')) {
            $searchTerm = $request->input('nomeUsuario');
            $query->where(function($q) use ($searchTerm) {
                $q->where('users.name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('users.cpf', 'like', '%' . $searchTerm . '%');
            });
        }

        $query->OrderBy('id', 'desc');

        return DataTables::of($query)
            ->addColumn('usuario', function ($row) {
                return '<a href="javascript:void(0);" onclick="LoadAgent(' . $row->user_id . ');" data-bs-toggle="modal" data-bs-target="#tabsModal" class="bs-tooltip" title="Visualizar Usuário" data-original-title="Visualizar Usuário">' . $row->usuario_nome . '</a>';
            })
            ->addColumn('valor', function ($row) {
                return 'R$ ' . $row->amount;
            })
            ->addColumn('gateway', function ($row) {
                return $row->gateway;
            })
            ->addColumn('status', function ($row) {
                if ($row->status == 0) {
                    return '<span class="badge badge-light-warning mb-2 me-4">Pendente</span>';
                } elseif ($row->status == 1) {
                    return '<span class="badge badge-light-success mb-2 me-4">Concluído</span>';
                } elseif ($row->status == 2) {
                    return '<span class="badge badge-light-danger mb-2 me-4">Cancelado</span>';
                }

                return '<span class="badge badge-light-dark mb-2 me-4">Desconhecido</span>';
            })
            ->addColumn('data', function ($row) {
                return Carbon::parse($row->updated_at)->format('d/m/Y H:i:s');
            })
            ->rawColumns(['usuario', 'status'])
            ->make(true);
    }

    public function saques(Request $request)
    {
        $queryParams = [
            'aff' => $request->input('aff', ''),
            'di' => $request->input('di', ''),
            'df' => $request->input('df', '')
        ];

        return view('admin.pagamentos.saques', compact('queryParams'));
    }

    public function saquesPendentes(Request $request)
    {
        $queryParams = [
            'aff' => $request->input('aff', ''),
            'di' => $request->input('di', ''),
            'df' => $request->input('df', '')
        ];

        return view('admin.pagamentos.saques_pendentes', compact('queryParams'));
    }

    public function saquesAfiliados(Request $request)
    {
        $queryParams = [
            'aff' => $request->input('aff', ''),
            'di' => $request->input('di', ''),
            'df' => $request->input('df', '')
        ];

        return view('admin.pagamentos.saques_afiliados', compact('queryParams'));
    }

    public function historicoPagamentos(Request $request)
    {
        $queryParams = [
            'aff' => $request->input('aff', ''),
            'di' => $request->input('di', ''),
            'df' => $request->input('df', '')
        ];

        return view('admin.pagamentos.historico_pagamentos', compact('queryParams'));
    }

    /**
     * Atualiza o status de uma solicitação de saque
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSaqueStatus(Request $request)
    {
        Helper::CheckAdm();

        try {
            // Validar os dados de entrada
            $request->validate([
                'id' => 'required|integer',
                'status' => 'required|in:1,2' // 1 = Aprovado, 2 = Rejeitado
            ]);

            // Buscar a transação
            $transaction = Transactions::findOrFail($request->id);

            // Verificar se é uma transação de saque
            if ($transaction->type != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta transação não é um saque'
                ]);
            }

            // Verificar se o status atual é pendente (0)
            if ($transaction->status != 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este saque já foi processado anteriormente'
                ]);
            }

            $User = User::find($transaction->user_id);

            if ($request->status == 1){
                $Gateways = Gateways::where('active', 1)->first();

                $valor = $transaction->amount;

                if ($User)
                {
                    // EdPay Gateway
                        $Gera = Helper::GeraSaqueEdPay($User, $valor);

                        if (!$Gera) {
                            return response()->json(["success" => false, "message" => "Ocorreu um erro, tente novamente em alguns instantes."]);
                        }

                        if (!isset($Gera['id']) || empty($Gera['id'])) {
                            return response()->json(["success" => false, "message" => "Ocorreu um erro, tente novamente em alguns instantes."]);
                        }

                        $IdTransaction = $Gera['id'];
                        $resultado = 'OK';

                    if ($resultado == 'OK') {
                        $transaction->token = $IdTransaction;
                        $transaction->save();

                        // Atualizar o status
                        $transaction->status = $request->status;
                        $transaction->updated_at = Carbon::now();
                        $transaction->save();

                        return response()->json([
                            'success' => true,
                            'message' => 'Status do saque atualizado com sucesso'
                        ]);
                    } else {
                        return response()->json(["success" => false, "message" => "Ocorreu um erro, tente novamente em alguns instantes."]);
                    }
                }
                else
                {
                    return response()->json(["success" => false, "message" => "Usuário da transação não encontrado!"]);
                }
            }else{
                $wallet = $User->wallet;

                if ($transaction->with_type == "balance") {
                    $wallet->balance += $transaction->amount;
                }elseif($transaction->with_type == "refer_rewards"){
                    $wallet->refer_rewards += $transaction->amount;
                }

                $wallet->save();

                // Atualizar o status
                $transaction->status = $request->status;
                $transaction->updated_at = Carbon::now();
                $transaction->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Status do saque atualizado com sucesso'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar o status do saque: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Fornece dados para a tabela de saques
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saquesData(Request $request)
    {
        $query = Transactions::select('transactions.*', 'users.name as usuario_nome', 'users.cpf')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->where('transactions.type', 1)
            ->where('transactions.with_type', 'balance');

        // Aplicar filtro por data inicial e final
        if ($request->input('dataInicial') && $request->input('dataFinal')) {
            $query->whereBetween('transactions.updated_at', [
                Carbon::parse($request->input('dataInicial'))->startOfDay(),
                Carbon::parse($request->input('dataFinal'))->endOfDay()
            ]);
        } elseif ($request->input('dataInicial') && !$request->input('dataFinal')) {
            $query->where('transactions.updated_at', '>=', Carbon::parse($request->input('dataInicial'))->startOfDay());
        }

        // Aplicar filtro por nome de usuário ou CPF
        if ($request->input('nomeUsuario')) {
            $searchTerm = $request->input('nomeUsuario');
            $query->where(function($q) use ($searchTerm) {
                $q->where('users.name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('users.cpf', 'like', '%' . $searchTerm . '%');
            });
        }

        $query->orderBy('id', 'desc');

        return DataTables::of($query)
            ->addColumn('usuario', function ($row) {
                $bonus = ($row->isaf == 2) ? "<span class=\"badge badge-success mb-2 me-4\">Bônus</span>" : "";
                return '<a href="javascript:void(0);" onclick="LoadAgent(' . $row->user_id . ');" data-bs-toggle="modal" data-bs-target="#tabsModal" class="bs-tooltip" title="Visualizar Usuário" data-original-title="Visualizar Usuário">' . $row->usuario_nome . ' ' . $bonus . '</a>';
            })
            ->addColumn('valor', function ($row) {
                return 'R$ ' . $row->amount;
            })
            ->addColumn('gateway', function ($row) {
                return $row->gateway;
            })
            ->addColumn('status', function ($row) {
                if ($row->status == 0) {
                    return '<span class="badge badge-light-warning mb-2 me-4">Pendente</span>';
                } elseif ($row->status == 1) {
                    return '<span class="badge badge-light-success mb-2 me-4">Concluído</span>';
                } elseif ($row->status == 2) {
                    return '<span class="badge badge-light-danger mb-2 me-4">Cancelado</span>';
                }

                return '<span class="badge badge-light-dark mb-2 me-4">Desconhecido</span>';
            })
            ->addColumn('data', function ($row) {
                return Carbon::parse($row->updated_at)->format('d/m/Y H:i:s');
            })
            ->rawColumns(['usuario', 'status'])
            ->make(true);
    }

    /**
     * Fornece dados para a tabela de saques pendentes
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saquesPendentesData(Request $request)
    {
        $query = Transactions::select('transactions.*', 'users.name as usuario_nome', 'users.cpf')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->where('transactions.type', 1)
            ->where('transactions.status', 0);

        // Aplicar filtro por data inicial e final
        if ($request->input('dataInicial') && $request->input('dataFinal')) {
            $query->whereBetween('transactions.updated_at', [
                Carbon::parse($request->input('dataInicial'))->startOfDay(),
                Carbon::parse($request->input('dataFinal'))->endOfDay()
            ]);
        } elseif ($request->input('dataInicial') && !$request->input('dataFinal')) {
            $query->where('transactions.updated_at', '>=', Carbon::parse($request->input('dataInicial'))->startOfDay());
        }

        // Aplicar filtro por nome de usuário ou CPF
        if ($request->input('nomeUsuario')) {
            $searchTerm = $request->input('nomeUsuario');
            $query->where(function($q) use ($searchTerm) {
                $q->where('users.name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('users.cpf', 'like', '%' . $searchTerm . '%');
            });
        }

        return DataTables::of($query)
            ->addColumn('usuario', function ($row) {
                $afiliado = ($row->isaf == 1) ? "<span class=\"badge badge-success mb-2 me-4\">Afiliado</span>" : "";
                $bonus = ($row->isaf == 2) ? "<span class=\"badge badge-success mb-2 me-4\">Bônus</span>" : "";
                return '<a href="javascript:void(0);" onclick="LoadAgent(' . $row->user_id . ');" data-bs-toggle="modal" data-bs-target="#tabsModal" class="bs-tooltip" title="Visualizar Usuário" data-original-title="Visualizar Usuário">' . $row->usuario_nome . ' ' . $afiliado . ' ' . $bonus . '</a>';
            })
            ->addColumn('valor', function ($row) {
                return 'R$ ' . $row->amount;
            })
            ->addColumn('status', function ($row) {
                return '<span class="badge badge-light-warning mb-2 me-4">Pendente</span>';
            })
            ->addColumn('data', function ($row) {
                return Carbon::parse($row->updated_at)->format('d/m/Y H:i:s');
            })
            ->addColumn('acoes', function ($row) {
                return '<div class="btn-group" role="group" aria-label="Basic example">
                    <button onclick="ActSaque(\'' . $row->id . '\', \'1\');" type="button" class="btn btn-primary">Aprovar</button>
                    <button onclick="ActSaque(\'' . $row->id . '\', \'2\');" type="button" class="btn btn-danger">Rejeitar</button>
                </div>';
            })
            ->rawColumns(['usuario', 'status', 'acoes'])
            ->make(true);
    }

    /**
     * Fornece dados para a tabela de saques de afiliados
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saquesAfiliadosData(Request $request)
    {
        $query = Transactions::select('transactions.*', 'users.name as usuario_nome', 'users.cpf')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->where('transactions.type', 1)
            ->where('transactions.with_type', 'refer_rewards');

        // Aplicar filtro por data inicial e final
        if ($request->input('dataInicial') && $request->input('dataFinal')) {
            $query->whereBetween('transactions.updated_at', [
                Carbon::parse($request->input('dataInicial'))->startOfDay(),
                Carbon::parse($request->input('dataFinal'))->endOfDay()
            ]);
        } elseif ($request->input('dataInicial') && !$request->input('dataFinal')) {
            $query->where('transactions.updated_at', '>=', Carbon::parse($request->input('dataInicial'))->startOfDay());
        }

        // Aplicar filtro por nome de usuário ou CPF
        if ($request->input('nomeUsuario')) {
            $searchTerm = $request->input('nomeUsuario');
            $query->where(function($q) use ($searchTerm) {
                $q->where('users.name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('users.cpf', 'like', '%' . $searchTerm . '%');
            });
        }

        return DataTables::of($query)
            ->addColumn('usuario', function ($row) {
                return '<a href="javascript:void(0);" onclick="LoadAgent(' . $row->user_id . ');" data-bs-toggle="modal" data-bs-target="#tabsModal" class="bs-tooltip" title="Visualizar Usuário" data-original-title="Visualizar Usuário">' . $row->usuario_nome . '</a>';
            })
            ->addColumn('valor', function ($row) {
                return 'R$ ' . $row->amount;
            })
            ->addColumn('gateway', function ($row) {
                return $row->gateway;
            })
            ->addColumn('status', function ($row) {
                if ($row->status == 0) {
                    return '<span class="badge badge-light-warning mb-2 me-4">Pendente</span>';
                } elseif ($row->status == 1) {
                    return '<span class="badge badge-light-success mb-2 me-4">Concluído</span>';
                } elseif ($row->status == 2) {
                    return '<span class="badge badge-light-danger mb-2 me-4">Cancelado</span>';
                }

                return '<span class="badge badge-light-dark mb-2 me-4">Desconhecido</span>';
            })
            ->addColumn('data', function ($row) {
                return Carbon::parse($row->updated_at)->format('d/m/Y H:i:s');
            })
            ->rawColumns(['usuario', 'status'])
            ->make(true);
    }

    /**
     * Gerar PDF do relatório de depósitos
     */
    public function depositosPdf(Request $request)
    {
        Helper::CheckAdm();

        // Buscar os dados dos depósitos
        $query = Transactions::select('transactions.*', 'users.name as usuario_nome', 'users.cpf')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->where('transactions.type', 0)
            ->where(function ($q) {
                $q->where('transactions.with_type', '!=', 'Mensalidade')
                    ->orWhereNull('transactions.with_type')
                    ->orWhere('transactions.with_type', '');
            });

        // Aplicar filtro por data inicial e final
        if ($request->input('di') && $request->input('df')) {
            $query->whereBetween('transactions.updated_at', [
                Carbon::parse($request->input('di'))->startOfDay(),
                Carbon::parse($request->input('df'))->endOfDay()
            ]);
        } elseif ($request->input('di') && !$request->input('df')) {
            $query->where('transactions.updated_at', '>=', Carbon::parse($request->input('di'))->startOfDay());
        }

        // Aplicar filtro por nome de usuário ou CPF
        if ($request->input('aff')) {
            $searchTerm = $request->input('aff');
            $query->where(function($q) use ($searchTerm) {
                $q->where('users.name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('users.cpf', 'like', '%' . $searchTerm . '%');
            });
        }

        $depositos = $query->orderBy('id', 'desc')->get();

        // Calcular total apenas dos concluídos
        $totalConcluidos = $depositos->where('status', 1)->sum('amount');

        $data = [
            'depositos' => $depositos,
            'total' => $totalConcluidos,
            'periodo' => [
                'inicio' => $request->input('di') ? Carbon::parse($request->input('di'))->format('d/m/Y') : 'Início',
                'fim' => $request->input('df') ? Carbon::parse($request->input('df'))->format('d/m/Y') : 'Hoje'
            ],
            'filtro_usuario' => $request->input('aff', ''),
            'data_geracao' => Carbon::now()->format('d/m/Y H:i:s')
        ];

        $pdf = Pdf::loadView('admin.pagamentos.pdf.depositos', $data);
        
        return $pdf->download('relatorio-depositos-' . date('Y-m-d-H-i-s') . '.pdf');
    }

    /**
     * Gerar PDF do relatório de saques
     */
    public function saquesPdf(Request $request)
    {
        Helper::CheckAdm();

        // Buscar os dados dos saques
        $query = Transactions::select('transactions.*', 'users.name as usuario_nome', 'users.cpf')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->where('transactions.type', 1)
            ->where('transactions.with_type', 'balance');

        // Aplicar filtro por data inicial e final
        if ($request->input('di') && $request->input('df')) {
            $query->whereBetween('transactions.updated_at', [
                Carbon::parse($request->input('di'))->startOfDay(),
                Carbon::parse($request->input('df'))->endOfDay()
            ]);
        } elseif ($request->input('di') && !$request->input('df')) {
            $query->where('transactions.updated_at', '>=', Carbon::parse($request->input('di'))->startOfDay());
        }

        // Aplicar filtro por nome de usuário ou CPF
        if ($request->input('aff')) {
            $searchTerm = $request->input('aff');
            $query->where(function($q) use ($searchTerm) {
                $q->where('users.name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('users.cpf', 'like', '%' . $searchTerm . '%');
            });
        }

        $saques = $query->orderBy('id', 'desc')->get();

        // Calcular total apenas dos concluídos
        $totalConcluidos = $saques->where('status', 1)->sum('amount');

        $data = [
            'saques' => $saques,
            'total' => $totalConcluidos,
            'periodo' => [
                'inicio' => $request->input('di') ? Carbon::parse($request->input('di'))->format('d/m/Y') : 'Início',
                'fim' => $request->input('df') ? Carbon::parse($request->input('df'))->format('d/m/Y') : 'Hoje'
            ],
            'filtro_usuario' => $request->input('aff', ''),
            'data_geracao' => Carbon::now()->format('d/m/Y H:i:s')
        ];

        $pdf = Pdf::loadView('admin.pagamentos.pdf.saques', $data);
        
        return $pdf->download('relatorio-saques-' . date('Y-m-d-H-i-s') . '.pdf');
    }

    /**
     * Gerar PDF do relatório de saques de afiliados
     */
    public function saquesAfiliadosPdf(Request $request)
    {
        Helper::CheckAdm();

        // Buscar os dados dos saques de afiliados
        $query = Transactions::select('transactions.*', 'users.name as usuario_nome', 'users.cpf')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->where('transactions.type', 1)
            ->where('transactions.with_type', 'refer_rewards');

        // Aplicar filtro por data inicial e final
        if ($request->input('di') && $request->input('df')) {
            $query->whereBetween('transactions.updated_at', [
                Carbon::parse($request->input('di'))->startOfDay(),
                Carbon::parse($request->input('df'))->endOfDay()
            ]);
        } elseif ($request->input('di') && !$request->input('df')) {
            $query->where('transactions.updated_at', '>=', Carbon::parse($request->input('di'))->startOfDay());
        }

        // Aplicar filtro por nome de usuário ou CPF
        if ($request->input('aff')) {
            $searchTerm = $request->input('aff');
            $query->where(function($q) use ($searchTerm) {
                $q->where('users.name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('users.cpf', 'like', '%' . $searchTerm . '%');
            });
        }

        $saquesAfiliados = $query->orderBy('id', 'desc')->get();

        // Calcular total apenas dos concluídos
        $totalConcluidos = $saquesAfiliados->where('status', 1)->sum('amount');

        $data = [
            'saques_afiliados' => $saquesAfiliados,
            'total' => $totalConcluidos,
            'periodo' => [
                'inicio' => $request->input('di') ? Carbon::parse($request->input('di'))->format('d/m/Y') : 'Início',
                'fim' => $request->input('df') ? Carbon::parse($request->input('df'))->format('d/m/Y') : 'Hoje'
            ],
            'filtro_usuario' => $request->input('aff', ''),
            'data_geracao' => Carbon::now()->format('d/m/Y H:i:s')
        ];

        $pdf = Pdf::loadView('admin.pagamentos.pdf.saques_afiliados', $data);
        
        return $pdf->download('relatorio-saques-afiliados-' . date('Y-m-d-H-i-s') . '.pdf');
    }
}
