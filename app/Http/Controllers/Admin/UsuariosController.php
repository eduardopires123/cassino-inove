<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transactions;
use App\Models\VipLevel;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class UsuariosController extends Controller
{
    /**
     * Exibe a página de busca de usuários
     */
    public function usuarios(Request $request)
    {
        // Buscar todos os níveis VIP ativos para popular o select
        $vipLevels = VipLevel::getAllActive();

        return view('admin.usuarios.usuarios', compact('vipLevels'));
    }

    /**
     * Fornece dados para a tabela de usuários
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function usuariosData(Request $request)
    {
        $query = User::with('Wallet')
            ->orderBy('id', 'desc');

        // Aplicar filtro por nome ou CPF
        if ($request->input('nome')) {
            $searchTerm = $request->input('nome');
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('cpf', 'like', '%' . $searchTerm . '%');
            });
        }

        // Aplicar filtro por data de cadastro
        if ($request->input('di') && $request->input('df')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->input('di'))->startOfDay(),
                Carbon::parse($request->input('df'))->endOfDay()
            ]);
        } elseif ($request->input('di') && !$request->input('df')) {
            $query->where('created_at', '>=', Carbon::parse($request->input('di'))->startOfDay());
        } elseif (!$request->input('di') && $request->input('df')) {
            $query->where('created_at', '<=', Carbon::parse($request->input('df'))->endOfDay());
        }

        // Aplicar filtro por ranking (nível VIP)
        if ($request->input('ranking')) {
            $query->where('vip_level', $request->input('ranking'));
        }

        return DataTables::of($query)
            ->addColumn('nome', function ($row) {
                return $row->name;
            })
            ->addColumn('ranking', function ($row) {
                $ranking = $row->getRanking();

                if (!$ranking || empty($ranking['image'])) {
                    return '<span>-</span>';
                }

                return '<img src="' . asset($ranking['image']) . '"
                        class="ranking-img"
                        width="30"
                        height="30"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="' . $ranking['name'] . '">';
            })
            ->addColumn('email', function ($row) {
                return $row->email;
            })
            ->addColumn('saldo', function ($row) {
                if (!$row->Wallet) {
                    return 'R$ 0,00';
                }

                $balance = $row->Wallet->balance;
                $formatado = $balance > 9999
                    ? number_format($balance, 2, ',', '.')
                    : number_format($balance, 2, ',', '');

                return 'R$ ' . $formatado;
            })
            ->addColumn('data_cadastro', function ($row) {
                return $row->created_at->format('d/m/Y H:i:s');
            })
            ->addColumn('ultimo_deposito', function ($row) {
                $lastDeposit = Transactions::where('user_id', $row->id)
                    ->where('type', 0) // deposito
                    ->orderBy('id', 'desc')
                    ->first();

                if ($lastDeposit) {
                    return $lastDeposit->updated_at->format('d/m/Y');
                }

                return 'Não há depósitos recentes';
            })
            ->addColumn('acoes', function ($row) {
                $html = '<a class="badge badge-light-primary text-start me-2 action-edit" href="javascript:void(0);" onclick="LoadAgent(\''.$row->id.'\');" data-bs-toggle="modal" data-bs-target="#tabsModal">';
                $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>';
                $html .= '</a>';
                $html .= '<a class="badge badge-light-danger text-start action-delete" href="javascript:void(0);" onclick="DeleteAgent(\''.$row->id.'\', \''.$row->name.'\');">';
                $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>';
                $html .= '</a>';
                return $html;
            })
            ->rawColumns(['ranking', 'acoes'])
            ->make(true);
    }

    /**
     * Exibe a página de carteiras de usuários
     */
    public function carteiras()
    {
        return view('admin.usuarios.carteiras');
    }

    /**
     * Fornece dados para a tabela de carteiras
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function carteirasData(Request $request)
    {
        $query = Wallet::with('user')
            ->orderBy('balance', 'desc');

        // Aplicar filtro por nome
        if ($request->input('nome')) {
            $searchTerm = $request->input('nome');
            $query->whereHas('user', function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('cpf', 'like', '%' . $searchTerm . '%');
            });
        }

        // Aplicar filtro por data de atualização
        if ($request->input('di') && $request->input('df')) {
            $query->whereBetween('updated_at', [
                Carbon::parse($request->input('di'))->startOfDay(),
                Carbon::parse($request->input('df'))->endOfDay()
            ]);
        } elseif ($request->input('di') && !$request->input('df')) {
            $query->where('updated_at', '>=', Carbon::parse($request->input('di'))->startOfDay());
        } elseif (!$request->input('di') && $request->input('df')) {
            $query->where('updated_at', '<=', Carbon::parse($request->input('df'))->endOfDay());
        }

        return DataTables::of($query)
            ->addColumn('nome', function ($row) {
                return $row->user ? $row->user->name : 'Usuário não encontrado';
            })
            ->addColumn('saldo', function ($row) {
                return 'R$ ' . number_format($row->balance ?? 0, 2, ',', '.');
            })
            ->addColumn('saldo_bonus', function ($row) {
                return 'B$ ' . number_format($row->balance_bonus ?? 0, 2, ',', '.');
            })
            ->addColumn('saldo_referidos', function ($row) {
                return 'REF$ ' . number_format($row->refer_rewards ?? 0, 2, ',', '.');
            })
            ->addColumn('ultima_movimentacao', function ($row) {
                return $row->updated_at ? $row->updated_at->format('d/m/Y H:i:s') : 'Data não disponível';
            })
            ->addColumn('acoes', function ($row) {
                if (!$row->user) {
                    return '<span class="badge badge-light-danger">Sem usuário</span>';
                }

                $html = '<a class="badge badge-light-primary text-start me-2 action-edit" href="javascript:void(0);" onclick="LoadAgent(\''.$row->user->id.'\');" data-bs-toggle="modal" data-bs-target="#tabsModal">';
                $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>';
                $html .= '</a>';
                return $html;
            })
            ->rawColumns(['acoes'])
            ->make(true);
    }

    /**
     * Exibe a página de blacklist
     */
    public function blacklist(Request $request)
    {
        return view('admin.usuarios.blacklist');
    }

    /**
     * Fornece dados para a tabela de blacklist
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function blacklistData(Request $request)
    {
        $query = User::where('banned', 1)
            ->orderBy('banned_date', 'desc');

        // Aplicar filtro por nome ou CPF
        if ($request->input('nome')) {
            $searchTerm = $request->input('nome');
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('cpf', 'like', '%' . $searchTerm . '%');
            });
        }

        // Aplicar filtro por data de bloqueio
        if ($request->input('di') && $request->input('df')) {
            $query->whereBetween('banned_date', [
                Carbon::parse($request->input('di'))->startOfDay(),
                Carbon::parse($request->input('df'))->endOfDay()
            ]);
        } elseif ($request->input('di') && !$request->input('df')) {
            $query->where('banned_date', '>=', Carbon::parse($request->input('di'))->startOfDay());
        } elseif (!$request->input('di') && $request->input('df')) {
            $query->where('banned_date', '<=', Carbon::parse($request->input('df'))->endOfDay());
        }

        return DataTables::of($query)
            ->addColumn('nome', function ($row) {
                return $row->name;
            })
            ->addColumn('motivo_bloqueio', function ($row) {
                return $row->banned_reason;
            })
            ->addColumn('data_bloqueio', function ($row) {
                return $row->banned_date && is_object($row->banned_date)
                    ? $row->banned_date->format('d/m/Y H:i:s')
                    : '-';
            })
            ->addColumn('acoes', function ($row) {
                $html = '<a class="badge badge-light-primary text-start me-2 action-edit" href="javascript:void(0);" onclick="UnblockAgent(\''.$row->id.'\', \''.$row->name.'\');">';
                $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-unlock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 9.9-1"></path></svg>';
                $html .= '</a>';
                return $html;
            })
            ->rawColumns(['acoes'])
            ->make(true);
    }

    /**
     * Exibe a página de novos usuários
     */
    public function userNews(Request $request)
    {
        return view('admin.usuarios.user_news');
    }

    /**
     * Fornece dados para a tabela de novos usuários
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userNewsData(Request $request)
    {
        // Iniciar query base
        $query = User::with('Wallet');

        // Aplicar filtro por nome ou CPF
        if ($request->input('nome')) {
            $searchTerm = $request->input('nome');
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('cpf', 'like', '%' . $searchTerm . '%');
            });
        }

        // Aplicar filtro por data de cadastro
        if ($request->input('di') && $request->input('df')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->input('di'))->startOfDay(),
                Carbon::parse($request->input('df'))->endOfDay()
            ]);
        } elseif ($request->input('di') && !$request->input('df')) {
            $query->where('created_at', '>=', Carbon::parse($request->input('di'))->startOfDay());
        } elseif (!$request->input('di') && $request->input('df')) {
            $query->where('created_at', '<=', Carbon::parse($request->input('df'))->endOfDay());
        } else {
            // Se não houver filtros de data, aplicar o filtro padrão de 31 dias
            $dataLimite = now()->subDays(31);
            $query->where('created_at', '>=', $dataLimite);
        }

        // Ordenação sempre por data de cadastro decrescente
        $query->orderBy('created_at', 'desc');

        return DataTables::of($query)
            ->addColumn('nome', function ($row) {
                return $row->name;
            })
            ->addColumn('email', function ($row) {
                return $row->email;
            })
            ->addColumn('saldo', function ($row) {
                if (!$row->Wallet) {
                    return 'R$ 0,00';
                }

                $balance = $row->Wallet->balance;
                $formatado = $balance > 9999
                    ? number_format($balance, 2, ',', '.')
                    : number_format($balance, 2, ',', '');

                return 'R$ ' . $formatado;
            })
            ->addColumn('data_cadastro', function ($row) {
                return $row->created_at->format('d/m/Y H:i:s');
            })
            ->addColumn('acoes', function ($row) {
                $html = '<a class="badge badge-light-primary text-start me-2 action-edit" href="javascript:void(0);" onclick="LoadAgent(\''.$row->id.'\');" data-bs-toggle="modal" data-bs-target="#tabsModal">';
                $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>';
                $html .= '</a>';
                $html .= '<a class="badge badge-light-danger text-start action-delete" href="javascript:void(0);" onclick="DeleteAgent(\''.$row->id.'\', \''.$row->name.'\');">';
                $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>';
                $html .= '</a>';
                return $html;
            })
            ->rawColumns(['acoes'])
            ->make(true);
    }

    /**
     * Bloquear usuário
     */
    public function bloquearUsuario(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->banned = 1;
        $user->banned_date = now();
        $user->banned_reason = $request->input('motivo', 'Usuário bloqueado pela administração');
        $user->save();

        return redirect()->back()->with('success', 'Usuário bloqueado com sucesso!');
    }

    /**
     * Desbloquear usuário
     */
    public function desbloquearUsuario(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->banned = 0;
        $user->banned_reason = null;
        $user->banned_date = null;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Usuário desbloqueado com sucesso!'
        ]);
    }

    /**
     * Busca usuários por nome, email, CPF ou ID
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchUsers(Request $request)
    {
        $search = $request->input('search');
        $users = [];

        if ($search) {
            $users = User::where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('cpf', 'like', "%{$search}%")
                ->orWhere('id', 'like', "%{$search}%")
                ->limit(10)
                ->get(['id', 'name', 'email', 'cpf']);
        }

        return response()->json(['users' => $users]);
    }
}
