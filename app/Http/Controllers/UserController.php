<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function profile()
    {
        return view('user.profile');
    }

    public function updateAvatar(Request $request)
    {
        // Validar a requisição
        $request->validate([
            'image' => 'required|string',
        ]);

        $user = Auth::user();
        $avatar = $request->image;
        $userRank = $user->getRanking()['level'];

        // Verificar se é um avatar do tipo A (Prata)
        if ($request->type == "silver") {
            if ($userRank < 4) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você precisa ter ranking Prata (nível 4) ou superior para usar este avatar.'
                ]);
            }
        }

        // Verificar se é um avatar do tipo B (Ouro)
        if ($request->type == "gold") {
            if ($userRank < 7) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você precisa ter ranking Ouro (nível 7) ou superior para usar este avatar.'
                ]);
            }
        }

        // Atualizar o avatar
        $user->image = $avatar;
        $user->save();

        return response()->json([
            'success' => true,
            'avatar' => $avatar
        ]);
    }

    /**
     * Atualiza o nome do usuário
     */
    public function updateUsername(Request $request)
    {
        // Validar a requisição
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $newName = $request->name;

        // Atualizar o nome do usuário
        $user->name = $newName;
        $user->save();

        return response()->json([
            'success' => true,
            'name' => $newName
        ]);
    }

    /**
     * Exibe a página de dados da conta.
     *
     * @return \Illuminate\View\View
     */
    public function account()
    {
        return view('user.account');
    }

    /**
     * Exibe a página de segurança da conta.
     *
     * @return \Illuminate\View\View
     */
    public function security()
    {
        $user = Auth::user();
        return view('user.security', compact('user'));
    }

    public function index()
    {
        return view('user.account');
    }

    /**
     * Redireciona para a carteira do usuário no ProfileController
     */
    public function wallet()
    {
        return redirect()->route('user.wallet');
    }

    /**
     * Atualiza e retorna o saldo atual do usuário
     */
    public function refreshBalance(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não autenticado'
            ], 401);
        }

        // Recarregar dados do usuário e carteira do banco
        $user->refresh();
        $user->load('wallet');

        return response()->json([
            'success' => true,
            'balance' => $user->wallet->balance ?? 0
        ]);
    }
}
