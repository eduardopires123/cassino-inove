<?php

namespace App\ViewComposers;

use Illuminate\View\View;
use App\Models\Settings;
use App\Models\Transactions;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminLicenseComposer
{
    /**
     * Compartilha dados de licença e outras informações com as views admin
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $user = Auth::user();
        
        // Verificação de licença
        $cacheKey = 'license_check';
        $setting = Cache::remember($cacheKey, now()->addMinutes(1), function () {
            return Settings::first();
        });

        if ($setting && isset($setting->valor) && isset($setting->expire)) {
            $valor = 'Aluguel R$ ' . number_format($setting->valor, 2, ',', '.') . ' / mês';
            
            try {
                $expira = Carbon::parse($setting->expire);
                $hoje = Carbon::now();

                if ($hoje->lessThan($expira)) {
                    $diff = $hoje->diff($expira);
                    $dias = floor($hoje->diffInDays($expira));
                    $horas = $diff->h;
                    $minutos = $diff->i;

                    if ($dias > 0) {
                        $expiraem = "Vencimento em {$dias} dia(s)";
                    } else {
                        $expiraem = "Vencimento em {$horas} horas e {$minutos} minutos";
                    }
                } else {
                    $valor = '';
                    $expiraem = 'Licença expirada';
                }
            } catch (\Exception $e) {
                $valor = '';
                $expiraem = '';
            }
        } else {
            $valor = '';
            $expiraem = '';
        }

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
            'licenseValor' => $valor ?? '',
            'licenseExpiraem' => $expiraem ?? '',
            'NPendencia' => $NPendencia,
            'pendingTransactions' => $pendingTransactions,
            'userType' => $userType,
        ]);
    }
}

