<?php

namespace App\Http\Controllers;

use App\Helpers\Core as Helper;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\BetbyTransaction;
use App\Models\BetbyBetslip;
use App\Models\Wallet;
use Exception;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use App\Models\DebugLogs;

use App\Models\SportBetSummary;
use App\Models\SportBetDiscards;
use App\Models\SportBetParents;

class BetbyApiController extends Controller
{
    protected $Settings;

    public function __construct()
    {
        $this->Settings = Helper::getSetting();
    }

    public function decodeJwt($jwt) {
        list($header, $payload, $signature) = explode('.', $jwt);

        $decodedHeader = json_decode($this->base64UrlDecode($header), false);
        $decodedPayload = json_decode($this->base64UrlDecode($payload), false);

        return [
            'header' => $decodedHeader,
            'payload' => $decodedPayload,
            'signature' => $signature
        ];
    }

    public function base64UrlDecode($data) {
        $base64 = strtr($data, '-_', '+/');
        $paddedBase64 = str_pad($base64, strlen($base64) % 4, '=', STR_PAD_RIGHT);
        return base64_decode($paddedBase64);
    }

    /**
     * Método PING - Verificar disponibilidade da API
     * GET /ping
     */
    public function ping(Request $request)
    {
        \DB::table('debug')->insert(['text' => 'bping > ' . json_encode($request->all())]);
        return response()->json(['timestamp' => time()], 200);
    }

    /**
     * Método BET_MAKE - Processar nova aposta
     * POST /bet/make
     */
    public function betMake(Request $request)
    {
        return \DB::transaction(function () use ($request) {
            try {
                $data = json_decode(json_encode($request->all()), true);
                $decoded = $this->decodeJwt($data['payload']);
                $request = $decoded['payload']->payload;

                \DB::table('debug')->insert(['text' => 'bmake > ' . json_encode($request)]);

                if ($request->currency != "BRL") {
                    return response()->json(["code" => 2002, "message" => "Invalid currency"], 400);
                }

                $Corte = explode('-', $request->player_id);
                $UserID = $Corte[1];

                $User = null;
                $User = User::Where('id', $UserID)->lockForUpdate()->first();

                if (!$User) {
                    return response()->json(["code" => 1007, "message" => "Session is expired"], 400);
                }

                if ($User->banned == 1) {
                    return response()->json(["code" => 1005, "message" => "Player is blocked"], 400);
                }

                $centsamount    = $request->amount;
                $rsamount       = Helper::convertToRealAmount($centsamount);

                $affected = DB::update(
                    'UPDATE wallets SET balance = balance - ? WHERE user_id = ? AND balance >= ?',
                    [ $rsamount, $User->id, $rsamount ]
                );

                if ($affected === 0) {
                    return response()->json(["code" => 2001, "message" => "Not enough money"], 400);
                }

                if (!isset($request->transaction)) {
                    return response()->json(["code" => 2004, "message" => "Bad request"], 400);
                }

                $player_id = $request->player_id;
                $session_id = $request->session_id;
                $transaction = $request->transaction;

                $CheckDiscard = SportBetDiscards::Where('transaction_id', $transaction->id)->first();

                if ($CheckDiscard) {
                    return response()->json(["code" => 2004, "message" => "Bad request"], 400);
                }

                SportBetSummary::create([
                    'provider' => 'betby',
                    'user_id' => (string)$User->id,
                    'transactionId' => $transaction->id,
                    'operation' => 'make',
                    'status' => 'Pending',
                    'statusel' => 'Nil',
                    'reason' => 'Nil',
                    'amount' => $centsamount,
                    'transaction' => json_encode($transaction),
                    'betslip' => json_encode($request),
                ]);

                $balance = DB::table('wallets')->where('user_id', $User->id)->value('balance');
                $balanceLong = (int)($balance * 100);

                $IDX = $transaction->id;

                $User->wallet()->update([
                    'anti_bot' => DB::raw('GREATEST(0, anti_bot - ' . (int)$rsamount . ')')
                ]);

                $User->wallet()->update([
                    'balance_bonus_rollover_used' => $User->wallet->balance_bonus_rollover_used + $rsamount
                ]);

                SportBetParents::Create([
                    'tId' => $User->id,
                    'transaction_id' => $transaction->id,
                    'parent' => $transaction->id,
                ]);

                return response()->json([
                    "id" => $IDX,
                    "ext_transaction_id" => (string)$transaction->id,
                    "parent_transaction_id" => null,
                    "user_id" => $this->Settings->sportpartnername . '-' . $User->id,
                    "operation" => "bet",
                    "amount" => $centsamount,
                    "currency" => "BRL",
                    "balance" => $balanceLong,
                ], 200);
            } catch (Exception $e) {
                return response()->json(["code" => 2004, "message" => "Bad request", "error" => $e->getMessage()], 400);
            }
        });
    }

    /**
     * Método BET_COMMIT - Confirmar aposta aceita (opcional)
     * POST /bet/commit
     */
    public function betCommit(Request $request)
    {

    }

    /**
     * Método BET_SETTLEMENT - Liquidação final da aposta
     * POST /bet/settlement
     */
    public function betSettlement(Request $request)
    {
        $data       = json_decode(json_encode($request->all()), true);
        $decoded    = $this->decodeJwt($data['payload']);
        $request    = $decoded['payload']->payload;

        \DB::table('debug')->insert(['text' => 'bsettlement > ' . json_encode($request)]);

        $status = $request->status;
        $bet_transaction_id = $request->bet_transaction_id;

        SportBetSummary::Where('id', $bet_transaction_id)->update(['statusel' => $status]);
        return response()->json([], 200);
    }

    /**
     * Método BET_REFUND - Estorno de aposta cancelada
     * POST /bet/refund
     */
    public function betRefund(Request $request)
    {
        $data       = json_decode(json_encode($request->all()), true);
        $decoded    = $this->decodeJwt($data['payload']);
        $request    = $decoded['payload']->payload;

        \DB::table('debug')->insert(['text' => 'brefund > ' . json_encode($request)]);

        $bet_transaction_id = $request->bet_transaction_id;
        $reason = $request->reason;
        $transaction = $request->transaction;

        $tInfo = SportBetSummary::Where('transactionId', $bet_transaction_id)->first();

        if ($tInfo){
            $PreStatus = $tInfo->status;
            $tInfo->status = $transaction->operation;
            $tInfo->reason = $reason;
            $tInfo->save();

            $User = User::Where('id', $tInfo->user_id)->first();

            if ($User){
                if ($PreStatus != "refund") {
                    //$User->wallet->balance += $transaction->amount / 100;
                    //$User->wallet->save();
                    $User->wallet->increment('balance', $transaction->amount / 100);
                }

                $balance = $User->wallet->balance;
                $balanceLong = (int)($balance * 100);

                $Parent = $transaction->parent_transaction_id;

                SportBetParents::Create([
                    'tId' => $User->id,
                    'transaction_id' => $transaction->id,
                    'parent' => $transaction->parent_transaction_id,
                ]);

                return response()->json([
                    "id" => $transaction->id,
                    "ext_transaction_id" => (string)$transaction->id,
                    "parent_transaction_id" => $Parent,
                    "user_id" => $this->Settings->sportpartnername . '-' . $User->id,
                    "operation" => "refund",
                    "amount" => $transaction->amount,
                    "currency" => "BRL",
                    "balance" => $balanceLong,
                ], 200);
            }
        }else{
            return response()->json([
                "code" => 2003,
                "message" => "Parent transaction not found",
            ], 400);
        }

        return response()->json([], 200);
    }

    /**
     * Método BET_WIN - Processar ganho de aposta
     * POST /bet/win
     */
    public function betWin(Request $request)
    {
        $data       = json_decode(json_encode($request->all()), true);
        $decoded    = $this->decodeJwt($data['payload']);
        $request    = $decoded['payload']->payload;

        \DB::table('debug')->insert(['text' => 'bwin > ' . json_encode($request)]);

        $bet_transaction_id = $request->bet_transaction_id;
        $transaction = $request->transaction;

        $tInfo = SportBetSummary::Where('transactionId', $bet_transaction_id)->first();

        if ($tInfo){
            $PreStatus = $tInfo->status;
            $tInfo->status = $transaction->operation;
            $tInfo->amount_win = $request->amount;
            $tInfo->cashout = $request->is_cashout;

            $tInfo->save();

            $User = User::Where('id', $tInfo->user_id)->first();

            if ($User){
                if ($PreStatus != "win") {
                    //$User->wallet->balance += $transaction->amount / 100;
                    //$User->wallet->save();
                    $User->wallet->increment('balance', $transaction->amount / 100);

                    if ($request->is_cashout) {
                        $valor = ($request->amount >= $tInfo->amount) ? $tInfo->amount : $request->amount;

                        $User->wallet->increment('anti_bot', $valor / 100);
                    }
                }

                $balance = $User->wallet->balance;
                $balanceLong = (int)($balance * 100);

                SportBetParents::Create([
                    'tId' => $User->id,
                    'transaction_id' => $transaction->id,
                    'parent' => $transaction->parent_transaction_id,
                ]);

                $Parent = $transaction->parent_transaction_id;

                return response()->json([
                    "id" => $transaction->id,
                    "ext_transaction_id" => (string)$transaction->id,
                    "parent_transaction_id" => $Parent,
                    "user_id" => $this->Settings->sportpartnername . '-' . $User->id,
                    "operation" => "win",
                    "amount" => (int)$transaction->amount,
                    "currency" => "BRL",
                    "balance" => $balanceLong,
                ], 200);
            }
        }else{
            return response()->json([
                "code" => 2003,
                "message" => "Parent transaction not found",
            ], 400);
        }
    }

    /**
     * Método BET_LOST - Processar perda de aposta
     * POST /bet/lost
     */
    public function betLost(Request $request)
    {
        $data       = json_decode(json_encode($request->all()), true);
        $decoded    = $this->decodeJwt($data['payload']);
        $request    = $decoded['payload']->payload;

        \DB::table('debug')->insert(['text' => 'blost > ' . json_encode($request)]);

        $bet_transaction_id = $request->bet_transaction_id;
        $transaction = $request->transaction;

        $tInfo = SportBetSummary::Where('transactionId', $bet_transaction_id)->first();

        if ($tInfo){
            $tInfo->status = $transaction->operation;
            $tInfo->save();

            $User = User::Where('id', $tInfo->user_id)->first();

            $balance = $User->wallet->balance;
            $balanceLong = (int)($balance * 100);

            if ($User){
                $Parent = $transaction->parent_transaction_id;

                SportBetParents::Create([
                    'tId' => $User->id,
                    'transaction_id' => $transaction->id,
                    'parent' => $transaction->parent_transaction_id,
                ]);

                return response()->json([
                    "id" => $transaction->id,
                    "ext_transaction_id" => (string)$transaction->id,
                    "parent_transaction_id" => $Parent,
                    "user_id" => $this->Settings->sportpartnername . '-' . $User->id,
                    "operation" => "lost",
                    "balance" => $balanceLong
                ], 200);
            }
        }else{
            return response()->json([
                "code" => 2003,
                "message" => "Parent transaction not found",
            ], 400);
        }
    }

    /**
     * Método BET_DISCARD - Descartar aposta devido a falha
     * POST /bet/discard
     */
    public function betDiscard(Request $request)
    {
        $data       = json_decode(json_encode($request->all()), true);
        $decoded    = $this->decodeJwt($data['payload']);
        $request    = $decoded['payload']->payload;

        \DB::table('debug')->insert(['text' => 'bdiscard > ' . json_encode($request)]);

        $ext_player_id = $request->ext_player_id;
        $transaction_id = $request->transaction_id;
        $reason = $request->reason;

        $tInfo = SportBetSummary::Where('transactionId', $transaction_id)->first();

        $DInfo = SportBetDiscards::create([
            'transaction_id' => $transaction_id,
            'reason' => $reason,
        ]);

        if ($tInfo){
            $tInfo->status = "discard";
            $tInfo->reason = $reason;
            $tInfo->save();

            $User = User::Where('id', $tInfo->user_id)->first();

            if ($User){
                //$User->wallet->balance += $tInfo->amount / 100;
                //$User->wallet->save();
                $User->wallet->increment('balance', $tInfo->amount / 100);
            }

            return response()->json([], 200);
        }

        return response()->json([], 200);
    }

    /**
     * Método BET_ROLLBACK - Rollback de aposta
     * POST /bet/rollback
     */
    public function betRollback(Request $request)
    {
        $data       = json_decode(json_encode($request->all()), true);
        $decoded    = $this->decodeJwt($data['payload']);
        $request    = $decoded['payload']->payload;

        \DB::table('debug')->insert(['text' => 'brollback > ' . json_encode($request)]);

        $bet_transaction_id = $request->bet_transaction_id;
        $transaction = $request->transaction;

        $tInfo = SportBetSummary::Where('transactionId', $bet_transaction_id)->first();

        if ($tInfo) {
            $PreStatus = $tInfo->status;
            $tInfo->status = $transaction->operation;
            $tInfo->save();

            $User = User::Where('id', $tInfo->user_id)->first();

            if ($User){
                \DB::table('debug')->insert(['text' => 'brollback PreStatus > ' . $PreStatus]);

                $amount = 0;

                if (($PreStatus == "win") or ($PreStatus == "refund")) {
                    //$User->wallet->balance -= $transaction->amount / 100;
                    //$User->wallet->save();
                    $User->wallet->decrement('balance', $transaction->amount / 100);
                    $amount = $transaction->amount;
                }

                /*if (($PreStatus == "win") or ($PreStatus == "canceled")) {
                    \DB::table('debug')->insert(['text' => 'brollback Volta Saldo PreStatus > ' . $PreStatus ]);
                    $User->wallet->balance -= $transaction->amount / 100;
                    $User->wallet->save();
                }*/

                $balance = $User->wallet->balance;
                $balanceLong = (int)($balance * 100);

                $Parent = $transaction->parent_transaction_id;

                SportBetParents::Create([
                    'tId' => $User->id,
                    'transaction_id' => $transaction->id,
                    'parent' => $transaction->parent_transaction_id,
                ]);

                return response()->json([
                    "id" => $transaction->id,
                    "ext_transaction_id" => (string)$transaction->id,
                    "parent_transaction_id" => $Parent,
                    "user_id" => $this->Settings->sportpartnername . '-' . $User->id,
                    "operation" => "rollback",
                    "amount" => (int)$amount,
                    "currency" => "BRL",
                    "balance" => $balanceLong,
                ], 200);
            }
        }else{
            return response()->json([
                "code" => 2003,
                "message" => "Parent transaction not found",
            ], 400);
        }
    }

    /**
     * Método PLAYER_SEGMENT - Atualizar segmento do jogador (opcional)
     * PUT /player/segment
     */
    public function playerSegment(Request $request)
    {
    }
}
