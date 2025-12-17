<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Core as Helper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\GameHistory;
use App\Models\Transactions;
use App\Models\Affiliates;
use App\Models\Wallet;

use App\Models\Admin\Permissions;
use App\Models\Admin\Logs;
use Carbon\Carbon;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

use App\Models\Settings;
use Illuminate\Support\Facades\Storage;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use Illuminate\Support\Facades\DB;
use App\Models\RouletteItem;
use App\Models\HomeSectionsSettings;

class AdminController extends Controller
{
    public function __construct()
    {
    }

    public function SearchAgent(Request $request)
    {
        $UserLogado = auth()->user();
        $this->CheckAdm();

        $name = $request->query('name');

        if (!$name) {
            return response()->json(['status' => false, 'message' => 'Preencha o nome e tente novamente!'], 400);
        }

        if ($UserLogado->is_admin > 1) {
            $users = User::where('name', 'like', '%' . $name . '%')->where('inviter', $UserLogado->id)->get();
        }else{
            $users = User::where('name', 'like', '%' . $name . '%')->get();
        }

        return response()->json($users);
    }

    public function setPermissions(Request $request)
    {
        $this->CheckAdm();

        $chave = $request->input('chave');
        $User = Permissions::where('user_id', $request->input('id'))->first();

        if ($User){
            $PermissoesData = json_decode($User->permission, true);

            $PermissoesData[$chave] = $PermissoesData[$chave] == 1 ? 0 : 1;

            $User->permission = json_encode($PermissoesData);
            $User->save();

            return response()->json(["status" => true, "message" => "Permissão atualizada com sucesso!"]);
        }else{
            return response()->json(["status" => false, "message" => "Usuário não encontrado!"]);
        }
    }

    public function Mensalidade(Request $request)
    {
        if (isset($request->dominio)){
            $Client = Clients::Where('dominio', $request->dominio)->first();

            if ($Client){
                $pubkey = 'PUB-MVPBCGPCU4-B1JKZKSGY8-PZYP';
                $seckey = 'SEC-0QU5QKURE8-QUYGONGENT-CLM5';

                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'pubkey' => $pubkey,
                    'seckey' => $seckey,
                ])->post('https://api.edpay.me/authorization', []);

                if ($response->successful()) {
                    $data = $response->json();
                    $token = $data['token'];

                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $token,
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                    ])->post("https://api.edpay.me/qrcode", [
                        'amount' => $Client->valor,
                        'description' => "Mensalidade (".$request->dominio.")",
                        'callback' => "https://" . $request->dominio . "/wh-pay-r9t4k2",
                    ]);

                    if ($response->successful()) {
                        $data = $response->json();

                        $params = [
                            "partner_id" => 0,
                            "amount" => $Client->valor,
                            "type" => 0,
                            "gateway" => 'EdPay',
                            "token" => $data['id'],
                            "status" => 0,
                            "chave_pix" => "",
                        ];

                        if($NTransaction = Transactions::create($params))
                        {
                            $data = [
                                'status' => true,
                                'qrcode' => $data['qrcode'],
                                'copiacola' => $data['copiacola'],
                            ];

                            return response()->json($data);
                        }
                        else
                        {
                            return response()->json(["status" => false, "message" => "Ocorreu um erro, tente novamente em alguns instantes."]);
                        }
                    } else {
                        $data = $response->json();
                        echo '1 > ' . json_encode($data, JSON_PRETTY_PRINT);
                    }
                }else{
                    $data = $response->json();
                    echo '2 > ' . json_encode($data, JSON_PRETTY_PRINT);

                }
            }else{
                return response()->json(['status' => false], 200);
            }
        }else{
            return response()->json(['status' => false], 200);
        }
    }

    public function GenerateToken($IdUser = null)
    {
        $UserLogado = auth()->user();

        if (!$UserLogado) {
            return "-";
        }else{
            $IdUser = $UserLogado->id;
        }

        $token  = "";
        $User   = User::where('id', $IdUser)->first();

        if ($User){
            $privateKeyPath = Storage::path('private.pem');
            $Settings       = Helper::getSetting();
            $partnerName    = $Settings->sportpartnername;

            if (!file_exists($privateKeyPath)) {
                return "-";
            }

            $privateKey = file_get_contents($privateKeyPath);

            $payload = [
                'id' => $IdUser,
                'currency' => "BRL"
            ];

            try {
                $token = JWT::encode($payload, $privateKey, 'RS512');
            } catch (\Exception $e) {
                return "-";
            }

            $response = Http::withHeaders([
                'x-partner-authorization' => $token,
                'x-partner-name' => $partnerName,
            ])->get('https://partner.digitain.bswbet.org/api/front/token');

            if ($response->successful()) {
                \App\Models\DebugLogs::Create(['text' => "Token BSW: " . json_encode($response->json())]);

                $data = $response->json();
                return $data['result']['token'] ?? "";
            } else {
                \App\Models\DebugLogs::Create(['text' => "Token BSW Failed: " . json_encode($response->json())]);

                return "-";
            }
        }else{
            \App\Models\DebugLogs::Create(['text' => "Token BSW Failed: No user found"]);

            return "-";
        }
    }

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

    public function AttSportsSettings(Request $request, $Qual)
    {
        $this->CheckAdm();

        $Settings = Settings::first();

        if ($Qual == 0) {
            $Settings->enable_sports = ($request->valor === "true") ? 1 : 0;
        }else{
            $Settings->enable_sports_bonus = ($request->valor === "true") ? 1 : 0;
        }

        $Settings->save();

        return response()->json(['status' => true, 'message' => "Atualizado com sucesso!"]);
    }

    public function LoadAgent($id, Request $request)
    {
        $this->CheckAdm();

        $User = User::where('id', $id)->first();
        $UsersTable = User::orderBy('id', 'desc')->get();

        return view('admin.includes.usuarios', ['page' => 'load_agent', 'subpage' => $request->input('subpage'), 'Agent' => $User, 'UsersTable' => $UsersTable]);
    }

    public function AttAgente(Request $request)
    {
        $this->CheckAdm();

        $rules = [
            'cid' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(["status" => false, "message" => "ID do usuário é obrigatório!"]);
        }

        $registro = User::findOrFail($request->cid);

        // Verificar se os dados originais coincidem com os dados atuais do banco
        $originalDataMismatch = false;
        $mismatchFields = [];

        // Verificar dados do usuário
        if (isset($request->original_name) && $request->original_name != $registro->name) {
            $originalDataMismatch = true;
            $mismatchFields[] = 'Nome';
        }

        if (isset($request->original_email) && $request->original_email != $registro->email) {
            $originalDataMismatch = true;
            $mismatchFields[] = 'E-mail';
        }

        if (isset($request->original_phone) && $request->original_phone != $registro->phone) {
            $originalDataMismatch = true;
            $mismatchFields[] = 'Telefone';
        }

        if (isset($request->original_pix) && $request->original_pix != $registro->pix) {
            $originalDataMismatch = true;
            $mismatchFields[] = 'PIX';
        }

        if (isset($request->original_is_demo) && $request->original_is_demo != $registro->is_demo_agent) {
            $originalDataMismatch = true;
            $mismatchFields[] = 'Demo';
        }

        if (isset($request->original_banned) && $request->original_banned != $registro->banned) {
            $originalDataMismatch = true;
            $mismatchFields[] = 'Status de Bloqueio';
        }

        if (isset($request->original_inviter) && $request->original_inviter != $registro->inviter) {
            $originalDataMismatch = true;
            $mismatchFields[] = 'Indicação';
        }

        if (isset($request->original_is_admin) && $request->original_is_admin != $registro->is_admin) {
            $originalDataMismatch = true;
            $mismatchFields[] = 'Nível de Acesso';
        }

        if (isset($request->original_is_affiliate) && $request->original_is_affiliate != $registro->is_affiliate) {
            $originalDataMismatch = true;
            $mismatchFields[] = 'Status de Afiliado';
        }

        // Verificar dados da carteira
        if (isset($request->original_balance) && $request->original_balance != $registro->Wallet->balance) {
            $originalDataMismatch = true;
            $mismatchFields[] = 'Balanço';
        }

        if (isset($request->original_balance_bonus) && $request->original_balance_bonus != $registro->Wallet->balance_bonus) {
            $originalDataMismatch = true;
            $mismatchFields[] = 'Balanço Bônus';
        }

        if (isset($request->original_free_spins) && $request->original_free_spins != $registro->Wallet->free_spins) {
            $originalDataMismatch = true;
            $mismatchFields[] = 'Rodadas Grátis';
        }

        if (isset($request->original_coin) && $request->original_coin != $registro->Wallet->coin) {
            $originalDataMismatch = true;
            $mismatchFields[] = 'Moedas (Coin)';
        }

        if (isset($request->original_refer_percent) && $request->original_refer_percent != $registro->Wallet->referPercent) {
            $originalDataMismatch = true;
            $mismatchFields[] = 'Percentual de Referidos';
        }

        if (isset($request->original_refer_rewards) && $request->original_refer_rewards != $registro->Wallet->refer_rewards) {
            $originalDataMismatch = true;
            $mismatchFields[] = 'Balanço de Referidos';
        }

        //

        if (isset($request->original_balance_bonus_rollover) && $request->original_balance_bonus_rollover != $registro->Wallet->balance_bonus_rollover) {
            $originalDataMismatch = true;
            $mismatchFields[] = 'Rollover de Bônus';
        }
        if (isset($request->original_balance_bonus_rollover_used) && $request->original_balance_bonus_rollover_used != $registro->Wallet->balance_bonus_rollover_used) {
            $originalDataMismatch = true;
            $mismatchFields[] = 'Rollover de Bônus usado';
        }
        if (isset($request->original_balance_bonus_expire) && $request->original_balance_bonus_expire != $registro->Wallet->balance_bonus_expire) {
            $originalDataMismatch = true;
            $mismatchFields[] = 'Expiração de Bônus';
        }
        if (isset($request->original_anti_bot) && $request->original_anti_bot != $registro->Wallet->anti_bot) {
            $originalDataMismatch = true;
            $mismatchFields[] = 'Anti Bot';
        }

        //

        // Se houver discrepância nos dados originais, não permitir a atualização
        if ($originalDataMismatch) {
            return response()->json([
                "status" => false,
                "message" => "Os dados foram alterados por outro usuário desde que você carregou a página. Campos modificados: " . implode(', ', $mismatchFields) . ". Por favor, recarregue a página e tente novamente.",
                "mismatch_fields" => $mismatchFields
            ]);
        }

        $modifiedFields = [];
        $editorUserId = auth()->id();

        // Função helper para registrar logs de edição
        $logEdit = function($fieldName, $oldValue, $newValue, $userId) use ($editorUserId) {
            \App\Models\Admin\Logs::create([
                'field_name' => $fieldName,
                'old_value' => $oldValue,
                'new_value' => $newValue,
                'updated_by' => $editorUserId,
                'user_id' => $userId,
                'type' => 0, // Tipo 0 para edições
                'log' => "Campo '{$fieldName}' alterado de '{$oldValue}' para '{$newValue}'"
            ]);
        };

        // Compare and update only if values are different
        if (isset($request->cdemo) && $registro->is_demo_agent != $request->cdemo) {
            $oldValue = $registro->is_demo_agent ? 'Sim' : 'Não';
            $newValue = $request->cdemo ? 'Sim' : 'Não';
            $logEdit('Usuário Demo', $oldValue, $newValue, $registro->id);

            $registro->is_demo_agent = $request->cdemo;
            $modifiedFields[] = 'Demo';
        }

        if (isset($request->cname) && $registro->name != $request->cname) {
            $logEdit('Nome', $registro->name, $request->cname, $registro->id);

            $registro->name = $request->cname;
            $modifiedFields[] = 'Nome';
        }

        if (isset($request->cemail) && $registro->email != $request->cemail) {
            $logEdit('E-mail', $registro->email, $request->cemail, $registro->id);

            $registro->email = $request->cemail;
            $modifiedFields[] = 'E-mail';
        }

        if (isset($request->ctelefone) && $registro->phone != $request->ctelefone) {
            $logEdit('Telefone', $registro->phone ?? 'Não informado', $request->ctelefone, $registro->id);

            $registro->phone = $request->ctelefone;
            $modifiedFields[] = 'Telefone';
        }

        if (isset($request->cpix) && $registro->pix != $request->cpix) {
            $logEdit('Chave PIX', $registro->pix ?? 'Não informado', $request->cpix, $registro->id);

            $registro->pix = $request->cpix;
            $modifiedFields[] = 'PIX';
        }

        // Update password if provided
        if (isset($request->csenha) && !empty($request->csenha)) {
            $logEdit('Senha', 'Senha anterior', 'Nova senha definida', $registro->id);

            $registro->password = Hash::make($request->csenha);
            $modifiedFields[] = 'Senha';
        }

        if (isset($request->cbanido) && $registro->banned != $request->cbanido) {
            $oldValue = $registro->banned ? 'Bloqueado' : 'Ativo';
            $newValue = $request->cbanido ? 'Bloqueado' : 'Ativo';
            $logEdit('Status de Bloqueio', $oldValue, $newValue, $registro->id);

            $registro->banned = $request->cbanido;
            $modifiedFields[] = 'Status de Bloqueio';

            if ($request->cbanido == 1 && isset($request->cmotivo)){
                $logEdit('Motivo do Bloqueio', $registro->banned_reason ?? 'Não informado', $request->cmotivo, $registro->id);

                $registro->banned_reason = $request->cmotivo;
                $registro->banned_date = Carbon::now()->format('Y-m-d H:i:s');
            } else if ($request->cbanido == 0) {
                if (!empty($registro->banned_reason)) {
                    $logEdit('Motivo do Bloqueio', $registro->banned_reason, 'Removido (usuário desbloqueado)', $registro->id);
                }
                $registro->banned_reason = "";
            }
        }

        if (isset($request->cindica) && $registro->inviter != $request->cindica) {
            $oldInviter = $registro->inviter ? User::find($registro->inviter)?->name ?? "ID: {$registro->inviter}" : 'Nenhum';
            $newInviter = $request->cindica != -1 ? User::find($request->cindica)?->name ?? "ID: {$request->cindica}" : 'Nenhum';
            $logEdit('Indicação', $oldInviter, $newInviter, $registro->id);

            $registro->inviter = $request->cindica == -1 ? null : $request->cindica;
            $modifiedFields[] = 'Indicação';
        }

        if (isset($request->cadm) && $registro->is_admin != $request->cadm) {
            $adminLevels = [
                0 => 'Usuário Comum',
                1 => 'Administrador',
                2 => 'Supervisor',
                3 => 'Afiliado'
            ];

            $oldValue = $adminLevels[$registro->is_admin] ?? "Nível {$registro->is_admin}";
            $newValue = $adminLevels[$request->cadm] ?? "Nível {$request->cadm}";
            $logEdit('Nível de Acesso', $oldValue, $newValue, $registro->id);

            $registro->is_admin = $request->cadm;
            $modifiedFields[] = 'Nível de Acesso';

            if ($request->cadm >= 1){
                $HavePermission = Permissions::where('user_id', $request->cid)->first();

                if (!$HavePermission) {
                    Permissions::create([
                        'user_id' => $request->cid,
                        'permission' => '{
                                            "1": 0,
                                            "2": 0,
                                            "3": 0,
                                            "4": 0,
                                            "5": 0,
                                            "6": 0,
                                            "7": 0,
                                            "8": 0,
                                            "9": 0,
                                            "10": 0
                                        }'
                    ]);
                }
            }
        }

        if (isset($request->caffiliate) && $registro->is_affiliate != $request->caffiliate) {
            $oldValue = $registro->is_affiliate ? 'Sim' : 'Não';
            $newValue = $request->caffiliate ? 'Sim' : 'Não';
            $logEdit('Status de Afiliado', $oldValue, $newValue, $registro->id);

            $registro->is_affiliate = $request->caffiliate;
            $modifiedFields[] = 'Status de Afiliado';
        }

        // Save user changes if any were made
        if (count($modifiedFields) > 0) {
            $registro->save();
        }

        // Handle wallet updates
        $walletModified = false;

        if (isset($request->coin) && $registro->Wallet->coin != $request->coin) {
            $logEdit('Moedas (Coin)', $registro->Wallet->coin, $request->coin, $registro->id);

            $registro->Wallet->coin = $request->coin;
            $modifiedFields[] = 'Moedas (Coin)';
            $walletModified = true;
        }

        if (isset($request->free_spins) && $registro->Wallet->free_spins != $request->free_spins) {
            $logEdit('Rodadas Grátis', $registro->Wallet->free_spins, $request->free_spins, $registro->id);

            $registro->Wallet->free_spins = $request->free_spins;
            $modifiedFields[] = 'Rodadas Grátis';
            $walletModified = true;
        }

        if (isset($request->cbalanco) && $registro->Wallet->balance != $request->cbalanco) {
            $valorAnterior = $registro->Wallet->balance;
            $novoValor = $request->cbalanco;
            $valorAlterado = $novoValor - $valorAnterior;

            // Registrar log de alteração de saldo
            if ($valorAlterado > 0) {
                // Adição de saldo
                \App\Models\Admin\Logs::create([
                    'field_name' => 'Adição de Saldo',
                    'old_value' => number_format($valorAnterior, 2, ',', '.'),
                    'new_value' => number_format($novoValor, 2, ',', '.'),
                    'updated_by' => $editorUserId,
                    'user_id' => $registro->id,
                    'type' => 0,
                    'log' => "Saldo adicionado: R$ " . number_format($valorAlterado, 2, ',', '.') . " (Saldo anterior: R$ " . number_format($valorAnterior, 2, ',', '.') . " → Novo saldo: R$ " . number_format($novoValor, 2, ',', '.') . ")"
                ]);
            } else if ($valorAlterado < 0) {
                // Remoção de saldo
                \App\Models\Admin\Logs::create([
                    'field_name' => 'Remoção de Saldo',
                    'old_value' => number_format($valorAnterior, 2, ',', '.'),
                    'new_value' => number_format($novoValor, 2, ',', '.'),
                    'updated_by' => $editorUserId,
                    'user_id' => $registro->id,
                    'type' => 0,
                    'log' => "Saldo removido: R$ " . number_format(abs($valorAlterado), 2, ',', '.') . " (Saldo anterior: R$ " . number_format($valorAnterior, 2, ',', '.') . " → Novo saldo: R$ " . number_format($novoValor, 2, ',', '.') . ")"
                ]);
            } else {
                // Alteração sem mudança no valor (não deveria acontecer, mas por segurança)
                $logEdit('Balanço', number_format($valorAnterior, 2, ',', '.'), number_format($novoValor, 2, ',', '.'), $registro->id);
            }

            $registro->Wallet->balance = $request->cbalanco;
            $modifiedFields[] = 'Balanço';
            $walletModified = true;
        }

        if (isset($request->cbalancob) && $registro->Wallet->balance_bonus != $request->cbalancob) {
            $logEdit('Balanço Bônus', number_format($registro->Wallet->balance_bonus, 2, ',', '.'), number_format($request->cbalancob, 2, ',', '.'), $registro->id);

            $registro->Wallet->balance_bonus = $request->cbalancob;
            $modifiedFields[] = 'Balanço Bônus';
            $walletModified = true;
        }

        if (isset($request->cref) && $registro->Wallet->referPercent != $request->cref) {
            $logEdit('Percentual de Referidos', $registro->Wallet->referPercent . '%', $request->cref . '%', $registro->id);

            $registro->Wallet->referPercent = $request->cref;
            $modifiedFields[] = 'Percentual de Referidos';
            $walletModified = true;
        }

        if (isset($request->cbalancor) && $registro->Wallet->refer_rewards != $request->cbalancor) {
            $logEdit('Balanço de Referidos', number_format($registro->Wallet->refer_rewards, 2, ',', '.'), number_format($request->cbalancor, 2, ',', '.'), $registro->id);

            $registro->Wallet->refer_rewards = $request->cbalancor;
            $modifiedFields[] = 'Balanço de Referidos';
            $walletModified = true;
        }

        //
        if (isset($request->cbalance_bonus_rollover) && $registro->Wallet->balance_bonus_rollover != $request->cbalance_bonus_rollover) {
            $logEdit('Rollover de bônus', number_format($registro->Wallet->balance_bonus_rollover, 2, ',', '.'), number_format($request->cbalance_bonus_rollover, 2, ',', '.'), $registro->id);

            $registro->Wallet->balance_bonus_rollover = $request->cbalance_bonus_rollover;
            $modifiedFields[] = 'Rollover de bônus';
            $walletModified = true;
        }
        if (isset($request->cbalance_bonus_rollover_used) && $registro->Wallet->balance_bonus_rollover_used != $request->cbalance_bonus_rollover_used) {
            $logEdit('Rollover de bônus usado', number_format($registro->Wallet->balance_bonus_rollover_used, 2, ',', '.'), number_format($request->cbalance_bonus_rollover_used, 2, ',', '.'), $registro->id);

            $registro->Wallet->balance_bonus_rollover_used = $request->cbalance_bonus_rollover_used;
            $modifiedFields[] = 'Rollover de bônus usado';
            $walletModified = true;
        }
        if (isset($request->cbalance_bonus_expire) && $registro->Wallet->balance_bonus_expire != $request->cbalance_bonus_expire) {
            $logEdit('Expiração de saldo bônus', $registro->Wallet->balance_bonus_expire, $request->cbalance_bonus_expire, $registro->id);

            $registro->Wallet->balance_bonus_expire = $request->cbalance_bonus_expire;
            $modifiedFields[] = 'Expiração de saldo bônus';
            $walletModified = true;
        }
        if (isset($request->canti_bot) && $registro->Wallet->anti_bot != $request->canti_bot) {
            $logEdit('Anti Bot', number_format($registro->Wallet->anti_bot, 2, ',', '.'), number_format($request->canti_bot, 2, ',', '.'), $registro->id);

            $registro->Wallet->anti_bot = $request->canti_bot;
            $modifiedFields[] = 'Anti Bot';
            $walletModified = true;
        }
        //

        // Save wallet changes if any were made
        if ($walletModified) {
            $registro->Wallet->save();
        }

        $message = count($modifiedFields) > 0
            ? "Campos atualizados: " . implode(', ', $modifiedFields)
            : "Nenhuma alteração realizada";

        return response()->json([
            "status" => true,
            "message" => $message
        ]);
    }

    public function RemoveAgente(Request $request)
    {
        $this->CheckAdm();

        if ($request->cid == 1){
            return response()->json(["status" => false, "message" => "Alteração desativada para esse usuário!"]);
        }

        $User = User::where('id', $request->cid)->first();

        if (!$User){
            return response()->json(["status" => false, "message" => "Usuário não encontrado!"]);
        }

        // Registrar log da remoção do usuário
        Logs::create([
            'field_name' => 'Remoção de Usuário',
            'old_value' => $User->name . ' (ID: ' . $User->id . ')',
            'new_value' => 'Usuário removido',
            'updated_by' => auth()->id(),
            'user_id' => $User->id,
            'type' => 0,
            'log' => "Usuário '{$User->name}' (ID: {$User->id}) foi removido permanentemente do sistema"
        ]);

        $Uregistro = User::where('id', $request->cid)->delete();
        $Tregistro = Transactions::where('user_id', $request->cid)->delete();
        $Wregistro = Wallet::where('user_id', $request->cid)->delete();
        $Aregistro = Affiliates::where('user_id', $request->cid)->delete();
        $GHregistro = GameHistory::where('user_id', $request->cid)->delete();
        $Pregistro = Permissions::where('user_id', $request->cid)->delete();

        return response()->json(["status" => true, "message" => "Usuário removido com sucesso!"]);
    }

    public function UnblockAgente(Request $request)
    {
        $this->CheckAdm();

        $User = User::where('id', $request->cid)->first();

        if (!$User) {
            return response()->json(["status" => false, "message" => "Usuário não encontrado!"]);
        }

        // Registrar log do desbloqueio
        if ($User->banned == 1) {
            Logs::create([
                'field_name' => 'Desbloqueio de Usuário',
                'old_value' => 'Bloqueado' . ($User->banned_reason ? " (Motivo: {$User->banned_reason})" : ''),
                'new_value' => 'Desbloqueado',
                'updated_by' => auth()->id(),
                'user_id' => $User->id,
                'type' => 0,
                'log' => "Usuário '{$User->name}' foi desbloqueado"
            ]);
        }

        $User->banned = 0;
        $User->banned_reason = "";
        $User->save();

        return response()->json(["status" => true, "message" => "Usuário desbloqueado com sucesso!"]);
    }



    public function modalAgent(Request $request, $id = null)
    {
        // Verificar se o usuário está autenticado e é admin
        if (!auth()->check() || auth()->user()->is_admin != 1) {
            return redirect()->route('admin.login');
        }

        $agent = User::findOrFail($id);
        return view('admin.modal.agent_detail', compact('agent'));
    }

    /**
     * Configuração da roleta
     */
    public function rouletteConfig()
    {
        $rouletteItems = RouletteItem::orderBy('probability', 'desc')->get();
        $homeSections = HomeSectionsSettings::getSettings();

        return view('admin.personalizacao.roulette', compact('rouletteItems', 'homeSections'));
    }

    /**
     * Atualizar configurações da roleta
     */
    public function updateRouletteSettings(Request $request)
    {
        try {
            $value = $request->input('show_roulette');

            $homeSections = HomeSectionsSettings::getSettings();
            $homeSections->show_roulette = $value;
            $homeSections->save();

            return response()->json([
                'success' => true,
                'message' => 'Configurações da roleta atualizadas com sucesso!',
                'show_roulette' => $homeSections->show_roulette
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar configurações da roleta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Atualizar item da roleta
     */
    public function updateRouletteItem(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer|exists:roulette_items,id',
                'name' => 'required|string|max:255',
                'free_spins' => 'required|integer|min:0',
                'game_name' => 'nullable|string|max:255',
                'color_code' => 'required|string|max:7',
                'coupon_code' => 'nullable|string|max:50',
                'probability' => 'required|numeric|min:0|max:1',
                'deposit_value' => 'required|numeric|min:0',
                'show_modal' => 'boolean',
                'is_active' => 'boolean'
            ]);

            $item = RouletteItem::findOrFail($request->id);

            $item->update([
                'name' => $request->name,
                'free_spins' => $request->free_spins,
                'game_name' => $request->game_name,
                'color_code' => $request->color_code,
                'coupon_code' => $request->coupon_code,
                'probability' => $request->probability,
                'deposit_value' => $request->deposit_value,
                'show_modal' => $request->has('show_modal'),
                'is_active' => $request->has('is_active')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Item da roleta atualizado com sucesso!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar item da roleta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Criar novo item da roleta
     */
    public function createRouletteItem(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'free_spins' => 'required|integer|min:0',
                'game_name' => 'nullable|string|max:255',
                'color_code' => 'required|string|max:7',
                'coupon_code' => 'nullable|string|max:50',
                'probability' => 'required|numeric|min:0|max:1',
                'deposit_value' => 'required|numeric|min:0',
                'show_modal' => 'boolean',
                'is_active' => 'boolean'
            ]);

            RouletteItem::create([
                'name' => $request->name,
                'free_spins' => $request->free_spins,
                'game_name' => $request->game_name,
                'color_code' => $request->color_code,
                'coupon_code' => $request->coupon_code,
                'probability' => $request->probability,
                'deposit_value' => $request->deposit_value,
                'show_modal' => $request->has('show_modal'),
                'is_active' => $request->has('is_active')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Item da roleta criado com sucesso!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar item da roleta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Deletar item da roleta
     */
    public function deleteRouletteItem(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer|exists:roulette_items,id'
            ]);

            $item = RouletteItem::findOrFail($request->id);
            $item->delete();

            return response()->json([
                'success' => true,
                'message' => 'Item da roleta deletado com sucesso!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar item da roleta: ' . $e->getMessage()
            ], 500);
        }
    }
}
