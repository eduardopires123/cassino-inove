<?php

namespace App\Http\Controllers;

use App\Helpers\Core as Helper;

use App\Models\Settings;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transactions;
use App\Models\Affiliates;
use App\Models\AffiliatesHistory;
use App\Models\Gateways;
use App\Models\DebugLogs;
use App\Models\Admin\Logs;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Auth;

use App\Models\Sessions;

class GatewayController extends Controller
{
    public function CheckAdm()
    {
        if (auth()->check())
        {
            $user = auth()->user();

            $isadmin = User::where('id', $user->id)->where('is_admin', '>=', 1)->first();

            if (!$isadmin)
            {
                abort(response()->json(["status" => false, "message" => "Você não tem acesso a essa página!"]));
            }
        }
        else
        {
            abort(response()->json(["status" => false, "message" => "Sua sessão expirou. Por favor, faça login novamente."]));
        }

        return false;
    }

    public function GetBalanceGate(Request $request)
    {
        $this->CheckAdm();

        $Gera = Helper::GetBalanceEdPay();
        return response()->json(["infos" => $Gera]);
    }

    public function GateSaq(Request $request)
    {
        $this->CheckAdm();

        $Valor = $request->input('valor');
        $Saldo = Helper::GetBalanceEdPay();

        $Admin = auth()->user();

        if ($Valor > $Saldo['availableBalance']){
            return response()->json(["status" => false, "message" => "Saldo disponível inferior ao valor do saque!"]);
        }elseif ($Valor < 0){
            return response()->json(["status" => false, "message" => "Informe apenas números positivos!"]);
        }else{
            $Gera = Helper::GeraSaqueEdPay($Admin, $Valor);

            if (!$Gera) {
                return response()->json(["status" => false, "message" => "Ocorreu um erro, tente novamente em alguns instantes."]);
            }
        }

        Logs::create([
            'updated_by' => $Admin->id,
            'user_id' => 0,
            'log' => sprintf("Saque do Gateway: Foi sacado R$ %.2f para %s", $Valor, $Admin->pix),
            'type' => 1,
        ]);

        return response()->json(["status" => true, "message" => "Saque realizado com sucesso!"]);
    }

    public function SaqueBonus(Request $request)
    {
        $user = null;
        if (auth()->check()){$user = auth()->user();}

        if ($user)
        {
            $date = $user->wallet->balance_bonus_expire;

            if ($date == "") {
                return response()->json(['status' => false, 'message' => 'Expiração de bônus não informada, contate o administrador.!']);
            }else{
                $expira = Carbon::parse($user->wallet->balance_bonus_expire);
                $hoje = Carbon::now();

                if($expira <= $hoje){
                    return response()->json(['status' => false, 'message' => 'Seu bônus expirou! 🕒']);
                }
            }

            $saldo = floatval($user->wallet->balance_bonus);

            if ($saldo <= 0.00){
                return response()->json(['status' => false, 'message' => 'Você não tem saldo de bônus! ❌']);
            }

            if ($user->wallet->balance_bonus_rollover_used < $user->wallet->balance_bonus_rollover){
                return response()->json(['status' => false, 'message' => 'Você não completou o rollover! ⚠️']);
            }

            $Saldo = floatval($user->wallet->balance);
            $SaldoNew = $Saldo + $saldo;

            $user->wallet->update(['balance' => $SaldoNew]);
            $user->wallet->update(['balance_bonus' => 0.00]);
            $user->wallet->update(['balance_bonus_rollover' => 0.00]);
            $user->wallet->update(['balance_bonus_rollover_used' => 0.00]);

            return response()->json(['status' => true, 'message' => 'O saldo do bônus foi transferido para sua carteira com sucesso!']);
        }
        else
        {
            return response()->json(["status" => false, "message" => "Sua sessão expirou. Por favor, faça login novamente."]);
        }
    }

    public function finalizePayment($id)
    {
        $Transaction = Transactions::where('token', $id)->where('status', 0)->first();

        if ($Transaction)
        {
            $User = User::where('id', $Transaction->user_id)->first();
            $Settings = Helper::getSetting();

            $Transaction->status = 1;
            $Transaction->save();

            // Adicionar saldo ao usuário
            if ($Transaction->accept_bonus == 1 && $Settings->bonus_ativado == 1)
            {
                // Bônus
                $valorBonus = floatval($Transaction->amount * ($Settings->bonus_percent / 100));
                $valorBonus = min($valorBonus, $Settings->bonus_max);
                $valorBonus = round($valorBonus, 2);

                $rollover = $Settings->rollover_dep + $Settings->rollover_bonus;
                $rollover_calc = ($Transaction->amount + $valorBonus) * $rollover;

                $User->wallet->increment('balance', floatval($Transaction->amount));
                $User->wallet->increment('balance_bonus', floatval($valorBonus));
                $User->wallet->update(['balance_bonus_rollover' => $rollover_calc, 'balance_bonus_expire' => Carbon::now()->addDays($Settings->bonus_validade)]);

                if ($Settings->bonus_afiliado == 1)
                {
                    $Affiliate = Affiliates::where('user_id', $User->id)->first();

                    if ($Affiliate)
                    {
                        $Owner = User::where('id', $Affiliate->affiliate_id)->first();

                        if ($Owner)
                        {
                            $percComissao = $Settings->afiliado_porcentagem_bonus;
                            $comissao = floatval($valorBonus * ($percComissao / 100));

                            $Owner->wallet->increment('refer_rewards', floatval($comissao));

                                AffiliatesHistory::create([
                                'user_id' => $Owner->id,
                                'type' => 'CPA',
                                'amount' => $comissao,
                                'refer_id' => $User->id,
                                ]);
                            }
                        }
                    }
            }
            else
            {
                $User->wallet->increment('balance', floatval($Transaction->amount));
            }

            // Verificar se é o primeiro depósito
            $isFirstDeposit = Transactions::where('user_id', $User->id)
                ->where('type', 0)
                ->where('status', 1)
                ->count() == 1;

            if ($isFirstDeposit)
            {
                $Affiliate = Affiliates::where('user_id', $User->id)->first();

                if ($Affiliate)
                {
                    $Owner = User::where('id', $Affiliate->affiliate_id)->first();

                    if ($Owner)
                    {
                        $valorCPA = $Settings->afiliado_cpa;
                        $Owner->wallet->increment('refer_rewards', floatval($valorCPA));

                        AffiliatesHistory::create([
                            'user_id' => $Owner->id,
                            'type' => 'CPA',
                            'amount' => $valorCPA,
                            'refer_id' => $User->id,
                        ]);
                }
                }
            }
            return true;
        }
        return false;
    }

    public function callback(Request $request)
    {
        $ip = $request->header('X-Forwarded-For') ?? $request->ip();
        $data = $request->all();

        // Log detalhado para debugging
        \App\Models\DebugLogs::create([
            'text' => 'CALLBACK GERAL > IP: ' . $ip . ', USER-AGENT: ' . $request->header('User-Agent') . ', JSON: ' . json_encode($data)
        ]);

        // EdPay
        if(isset($request['account_id']) && $request['object'] == 'in') {
            if(self::finalizePayment($request['txid'])) {
                return response()->json([], 200);
            }
        }

        return response()->json([], 200);
    }

    public function callbackMethodPayment(Request $request)
    {
        $data = $request->all();
        return response()->json([], 200);
    }

    public function CheckPayment($id)
    {
        $Transaction = Transactions::where('id', $id)->where('status', 1)->first();
        if ($Transaction) {
            return response()->json(['status' => true]);
        }

        return response()->json(['status' => false]);
    }

    public function PagPix(Request $request)
    {
        $Settings = Helper::getSetting();
        $Gateways = Gateways::where('nome', 'EdPay')->first();

        $User = null;
        $valor = $request->input('amount');
        $valor = Helper::removeCurrencyFormatting($valor);

        if ($valor < $Settings->min_dep) {
            return response()->json(["status" => false, "message" => "Valor abaixo do mínimo!"]);
        }

        if ($valor > $Settings->max_dep) {
            return response()->json(["status" => false, "message" => "Valor acima do máximo!"]);
        }

        if (auth()->check()){$User = auth()->user();}

        if ($User)
        {
            $Transaction = Transactions::where('status', 0)->where('type', 0)->where('user_id', $User->id)->first();

            if ($Transaction)
            {
                if ($Transaction->updated_at > Carbon::now()->subMinutes(5)) {
                    return response()->json(["status" => false, "message" => "Aguarde 5 minutos antes de solicitar um novo pagamento!"]);
                } else {
                    $Transaction->status = 2;
                    $Transaction->save();
                }
            }

                $Gera = Helper::GeraQRCodeEdPay($User, $valor, "Saldo Plataforma");

                if (!$Gera) {
                    return response()->json(["status" => false,"message" => "Erro ao criar QRCode, Tente novamente em alguns minutos!"]);
                }

                $IdTransation   = $Gera['id'];
                $QrCode         = $Gera['qrcode'];
                $CopiaCola      = $Gera['copiacola'];
                $PreCopToQr     = '';

            $params = [
                "user_id" => $User->id,
                "amount" => $valor,
                "type" => 0,
                "gateway" => "EdPay",
                "token" => $IdTransation,
                "status" => 0,
                "chave_pix" => "",
                "accept_bonus" => $request->input('accept_bonus'),
            ];

            if($NTransaction = Transactions::create($params))
            {
                $data = [
                    'status' => true,
                    'valor' => $valor,
                    'pedido' => $NTransaction->id,
                    'qrcode' => $QrCode,
                    'copiacola' => $CopiaCola
                ];

                return response()->json($data);
            }
            else
            {
                return response()->json(["status" => false, "message" => "Ocorreu um erro, tente novamente em alguns instantes."]);
            }
        }
        else
        {
            return response()->json(["status" => false, "message" => "Sua sessão expirou. Por favor, faça login novamente."]);
        }
    }

    public function Saque(Request $request)
    {
        $User = null;
        $Settings = Settings::first();
        $Gateways = Gateways::where('nome', 'EdPay')->first();

        $valor = $request->input('amount');
        $valor = Helper::removeCurrencyFormatting($valor);

        if ($valor < $Settings->min_saque_n)
        {
            return response()->json(["status" => false,"message" => "Valor abaixo do saque mínimo!"]);
        }
        elseif ($valor > $Settings->max_saque_n)
        {
            return response()->json(["status" => false,"message" => "Valor superior ao saque máximo!"]);
        }
        else
        {
            if (auth()->check()){$User = auth()->user();}

            if ($User)
            {
                if ($User->is_demo_agent == 1)
                {
                    return response()->json(["status" => false, "message" => "Você não tem permissão para realizar saques!"]);
                }

                if ($valor > $User->Wallet->balance)
                {
                    return response()->json(["status" => false, "message" => "Você não tem saldo suficiente!"]);
                }
                elseif ($User->Wallet->anti_bot > 0)
                {
                    return response()->json(["status" => false, "message" => "anti_bot"]);
                }
                else
                {
                    // Verificar limite diário de saque
                    if ($Settings->max_saque_diario > 0) {
                        $hoje = Carbon::today();
                        $totalSacadoHoje = Transactions::where('user_id', $User->id)
                            ->where('type', 1)
                            ->where('status', 1)
                            ->whereDate('created_at', $hoje)
                            ->sum('amount');

                        $totalSacadoHoje = (float) $totalSacadoHoje;
                        $valorDisponivelRestante = (float) $Settings->max_saque_diario - $totalSacadoHoje;

                        if ($valorDisponivelRestante <= 0) {
                            return response()->json([
                                "status" => false,
                                "message" => "Você já atingiu o limite diário de saque de R$ " . number_format($Settings->max_saque_diario, 2, ',', '.') . ". Tente novamente amanhã."
                            ]);
                        }

                        if ($valor > $valorDisponivelRestante) {
                            return response()->json([
                                "status" => false,
                                "message" => "Este saque excede o limite diário disponível. Você ainda pode sacar até R$ " . number_format($valorDisponivelRestante, 2, ',', '.') . " hoje."
                            ]);
                        }
                    }

                    // Verificar limite de quantidade de saques por dia
                    $hoje = Carbon::today();
                    $limiteQuantidadeSaques = $User->max_quantidade_saques_diario !== null
                        ? $User->max_quantidade_saques_diario
                        : $Settings->max_quantidade_saques_diario;

                    if ($limiteQuantidadeSaques > 0) {
                        $quantidadeSaquesHoje = Transactions::where('user_id', $User->id)
                            ->where('type', 1)
                            ->where('status', 1)
                            ->whereDate('created_at', $hoje)
                            ->count();

                        if ($quantidadeSaquesHoje >= $limiteQuantidadeSaques) {
                            return response()->json([
                                "status" => false,
                                "message" => "Você já atingiu o limite de " . $limiteQuantidadeSaques . " saque(s) por dia. Tente novamente amanhã."
                            ]);
                        }
                    }

                    // Verificar se é saque automático e aplicar limite de quantidade de saques automáticos
                    $ehSaqueAutomatico = $valor <= $Settings->max_saque_aut;
                    $limiteAutomaticoExcedido = false;

                    if ($ehSaqueAutomatico) {
                        $limiteQuantidadeSaquesAutomaticos = $User->max_quantidade_saques_automaticos_diario !== null
                            ? $User->max_quantidade_saques_automaticos_diario
                            : $Settings->max_quantidade_saques_automaticos_diario;

                        if ($limiteQuantidadeSaquesAutomaticos > 0) {
                            $quantidadeSaquesAutomaticosHoje = Transactions::where('user_id', $User->id)
                                ->where('type', 1)
                                ->where('status', 1)
                                ->whereDate('created_at', $hoje)
                                ->where('amount', '<=', $Settings->max_saque_aut)
                                ->count();

                            if ($quantidadeSaquesAutomaticosHoje >= $limiteQuantidadeSaquesAutomaticos) {
                                $limiteAutomaticoExcedido = true;
                            }
                        }
                    }

                    $Transaction = Transactions::where('status', 0)->where('type', 1)->where('user_id', $User->id)->first();

                    if ($Transaction)
                    {
                        return response()->json(["status" => false, "message" => "Aguarde o processamento anterior!"]);
                    }
                    else
                    {
                        // Se o limite automático foi excedido, criar saque manual mesmo que o valor seja <= max_saque_aut
                        if ($valor <= $Settings->max_saque_aut && !$limiteAutomaticoExcedido) {
                                $Gera = Helper::GeraSaqueEdPay($User, $valor);

                                if (!$Gera) {
                                    return response()->json(["status" => false, "message" => "Ocorreu um erro, tente novamente em alguns instantes."]);
                                }

                                if (!isset($Gera['id']) || empty($Gera['id'])) {
                                    return response()->json(["status" => false, "message" => "Ocorreu um erro, tente novamente em alguns instantes."]);
                                }

                                $IdTransaction = $Gera['id'];
                                $resultado = 'OK';

                            if ($resultado == 'OK') {
                                $User->wallet->decrement('balance', floatval($valor));

                                $params = [
                                    "user_id" => $User->id,
                                    "amount" => $valor,
                                    "type" => 1,
                                    "with_type" => "balance",
                                    "gateway" => "EdPay",
                                    "token" => $IdTransaction,
                                    "status" => 1,
                                    "chave_pix" => $User->pix,
                                ];

                                if ($NTransaction = Transactions::create($params)) {
                                    $data = [
                                        'status' => true,
                                        'valor' => $valor,
                                        'pedido' => $NTransaction->id
                                    ];

                                    return response()->json($data);
                                } else {
                                    return response()->json(["status" => false, "message" => "Ocorreu um erro, tente novamente em alguns instantes."]);
                                }
                            } else {
                                return response()->json(["status" => false, "message" => "Ocorreu um erro, tente novamente em alguns instantes."]);
                            }
                        }else{
                            $params = [
                                "user_id" => $User->id,
                                "amount" => $valor,
                                "type" => 1,
                                "with_type" => "balance",
                                "gateway" => "EdPay",
                                "token" => '',
                                "status" => 0,
                                "chave_pix" => $User->pix,
                            ];

                            $User->wallet->decrement('balance', floatval($valor));

                            if ($NTransaction = Transactions::create($params)) {
                                $data = [
                                    'status' => true,
                                    'valor' => $valor,
                                    'pedido' => $NTransaction->id
                                ];

                                return response()->json($data);
                            } else {
                                return response()->json(["status" => false, "message" => "Ocorreu um erro, tente novamente em alguns instantes."]);
                            }
                        }
                    }
                }
            }
            else
            {
                return response()->json(["status" => false, "message" => "Sua sessão expirou. Por favor, faça login novamente."]);
            }
        }
    }

    public function SaqueAff(Request $request)
    {
        $User = null;
        $Settings = Settings::first();
        $Gateways = Gateways::where('nome', 'EdPay')->first();

        $valor = $request->input('amount');
        $valor = Helper::removeCurrencyFormatting($valor);

        if ($valor < $Settings->min_saque_af)
        {
            return response()->json(["status" => false,"message" => "Valor abaixo do saque mínimo!"]);
        }
        elseif ($valor > $Settings->max_saque_af)
        {
            return response()->json(["status" => false,"message" => "Valor superior ao saque máximo!"]);
        }
        else
        {
            if (auth()->check()){$User = auth()->user();}

            if ($User)
            {
                if ($User->is_demo_agent == 1)
                {
                    return response()->json(["status" => false, "message" => "Você não tem permissão para realizar saques!"]);
                }

                if ($valor > $User->Wallet->refer_rewards)
                {
                    return response()->json(["status" => false, "message" => "Você não tem saldo suficiente!"]);
                }
                else
                {
                    // Verificar limite de quantidade de saques por dia
                    $hoje = Carbon::today();
                    $limiteQuantidadeSaques = $User->max_quantidade_saques_diario !== null
                        ? $User->max_quantidade_saques_diario
                        : $Settings->max_quantidade_saques_diario;

                    if ($limiteQuantidadeSaques > 0) {
                        $quantidadeSaquesHoje = Transactions::where('user_id', $User->id)
                            ->where('type', 1)
                            ->where('status', 1)
                            ->whereDate('created_at', $hoje)
                            ->count();

                        if ($quantidadeSaquesHoje >= $limiteQuantidadeSaques) {
                            return response()->json([
                                "status" => false,
                                "message" => "Você já atingiu o limite de " . $limiteQuantidadeSaques . " saque(s) por dia. Tente novamente amanhã."
                            ]);
                        }
                    }

                    // Verificar se é saque automático e aplicar limite de quantidade de saques automáticos
                    $ehSaqueAutomatico = $valor <= $Settings->max_saque_aut_af;
                    $limiteAutomaticoExcedido = false;

                    if ($ehSaqueAutomatico) {
                        $limiteQuantidadeSaquesAutomaticos = $User->max_quantidade_saques_automaticos_diario !== null
                            ? $User->max_quantidade_saques_automaticos_diario
                            : $Settings->max_quantidade_saques_automaticos_diario;

                        if ($limiteQuantidadeSaquesAutomaticos > 0) {
                            $quantidadeSaquesAutomaticosHoje = Transactions::where('user_id', $User->id)
                                ->where('type', 1)
                                ->where('status', 1)
                                ->whereDate('created_at', $hoje)
                                ->where('amount', '<=', $Settings->max_saque_aut_af)
                                ->count();

                            if ($quantidadeSaquesAutomaticosHoje >= $limiteQuantidadeSaquesAutomaticos) {
                                $limiteAutomaticoExcedido = true;
                            }
                        }
                    }

                    $Transaction = Transactions::where('status', 0)->where('type', 1)->where('user_id', $User->id)->first();

                    if ($Transaction)
                    {
                        return response()->json(["status" => false, "message" => "Aguarde o processamento anterior!"]);
                    }
                    else
                    {
                        // Se o limite automático foi excedido, criar saque manual mesmo que o valor seja <= max_saque_aut_af
                        if ($valor <= $Settings->max_saque_aut_af && !$limiteAutomaticoExcedido) {
                                $Gera = Helper::GeraSaqueEdPay($User, $valor);

                                if (!$Gera) {
                                    return response()->json(["status" => false, "message" => "Ocorreu um erro, tente novamente em alguns instantes."]);
                                }

                                if (!isset($Gera['id']) || empty($Gera['id'])) {
                                    return response()->json(["status" => false, "message" => "Ocorreu um erro, tente novamente em alguns instantes."]);
                                }

                                $IdTransaction = $Gera['id'];
                                $resultado = 'OK';

                            if ($resultado == 'OK') {
                                $User->wallet->decrement('refer_rewards', floatval($valor));

                                $params = [
                                    "user_id" => $User->id,
                                    "amount" => $valor,
                                    "type" => 1,
                                    "with_type" => "refer_rewards",
                                    "gateway" => "EdPay",
                                    "token" => $IdTransaction,
                                    "status" => 1,
                                    "chave_pix" => $User->pix,
                                ];

                                if ($NTransaction = Transactions::create($params)) {
                                    $data = [
                                        'status' => true,
                                        'valor' => $valor,
                                        'pedido' => $NTransaction->id
                                    ];

                                    return response()->json($data);
                                } else {
                                    return response()->json(["status" => false, "message" => "Ocorreu um erro, tente novamente em alguns instantes."]);
                                }
                            } else {
                                return response()->json(["status" => false, "message" => "Ocorreu um erro, tente novamente em alguns instantes."]);
                            }
                        }else{
                            $params = [
                                "user_id" => $User->id,
                                "amount" => $valor,
                                "type" => 1,
                                "with_type" => "refer_rewards",
                                "gateway" => "EdPay",
                                "token" => '',
                                "status" => 0,
                                "chave_pix" => $User->pix,
                            ];

                            $User->wallet->decrement('refer_rewards', floatval($valor));

                            if ($NTransaction = Transactions::create($params)) {
                                $data = [
                                    'status' => true,
                                    'valor' => $valor,
                                    'pedido' => $NTransaction->id
                                ];

                                return response()->json($data);
                            } else {
                                return response()->json(["status" => false, "message" => "Ocorreu um erro, tente novamente em alguns instantes."]);
                            }
                        }
                    }
                }
            }
            else
            {
                return response()->json(["status" => false, "message" => "Sua sessão expirou. Por favor, faça login novamente."]);
            }
        }
    }

    public function GetBalance(Request $request)
    {
        $user = null;
        if (auth()->check()){$user = auth()->user();}

        $tabId = $request->input('tab_id');

        if (!session()->has('current_tab_id')) {
            session(['current_tab_id' => $tabId]);
        }

        $isSameTab = session('current_tab_id') === $tabId;

        if (!$isSameTab) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        if ($user)
        {
            $Count = Sessions::where('user_id', $user->id)->count();

            if (($Count >= 2) and ($user->is_admin == 0)) {
                Sessions::where('user_id', $user->id)->delete();
                return response()->json(["status" => false, "message" => "Sua sessão expirou. Por favor, faça login novamente."]);
            }

            $bonus = 0;
            if ($user->wallet->balance_bonus_rollover_used >= $user->wallet->balance_bonus_rollover) {
                $bonus = $user->wallet->balance_bonus;
            }

            return response()->json(["status" => true, "balance" => number_format($user->wallet->balance, 2, ',', '.'), "balance_bonus" => number_format($user->wallet->balance_bonus, 2, ',', '.'), "balance_total" => number_format($user->wallet->balance + $bonus, 2, ',', '.'), "refer_rewards" => $user->wallet->refer_rewards]);
        }
        else
        {
            return response()->json(["status" => false, "message" => "Sua sessão expirou. Por favor, faça login novamente."]);
        }
    }
}
