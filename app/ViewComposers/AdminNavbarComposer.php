<?php

namespace App\ViewComposers;

use Illuminate\View\View;
use App\Models\Transactions;
use Illuminate\Support\Facades\Auth;

class AdminNavbarComposer
{
    /**
     * Compartilha dados com as views admin (navbar e layout)
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $user = Auth::user();

        // Contagem de pendências
        $NPendencia = Transactions::where('type', 1)->where('status', 0)->count();

        // Transações pendentes para notificações
        $pendingTransactions = Transactions::where('type', 1)
            ->where('status', 0)
            ->orderBy('id', 'desc')
            ->get();

        // Tipo de usuário
        $userType = '';
        if ($user) {
            if ($user->is_admin == 1) {
                $userType = 'Administrador';
            } elseif ($user->is_admin == 2) {
                $userType = 'Supervisor';
            } elseif ($user->is_admin == 3) {
                $userType = 'Afiliado';
            }
        }

        // Compartilhar variáveis com a view
        $view->with([
            'NPendencia' => $NPendencia,
            'pendingTransactions' => $pendingTransactions,
            'userType' => $userType,
        ]);
    }
}

