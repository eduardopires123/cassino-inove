<?php

namespace App\Http\Controllers;

use App\Helpers\Core as Helper;
use App\Models\Settings;

use App\Models\User;
use App\Models\Wallet;
use App\Models\SportBetSummary;
use App\Models\SportBetDiscards;
use App\Models\SportBetParents;

use Couchbase\QueryException;
use Illuminate\Http\Request;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Util\Exception;

use App\Jobs\ProcessarMake;

class SportsWiseController extends Controller
{
    public $partner_id;
    public $allow_bonus;

    public function __construct()
    {
        $Settings = Helper::getSetting();

        $this->partner_id = $Settings->sportpartnername;
        $this->allow_bonus = $Settings->enable_sports_bonus;
    }

    /**
     * Página principal do Digitain Sportsbook
     */
    public function index(Request $request)
    {
        // Verificar se Digitain está ativo
        if (!Settings::isDigitainActive()) {
            // Se Digitain não estiver ativo, redirecionar para home
            return redirect()->route('home')->with('error', 'Serviço de esportes não disponível no momento.');
        }
        
        return view('esportes.digitain');
    }

    public function player_balance(Request $request)
    {
        $ip = $request->header('X-Forwarded-For') ?? $request->ip();

        \DB::table('debug')->insert(['text' => 'player_balance > IP: ' . $ip . " > " . json_encode($request->all())]);

        // Verificar se o project_name corresponde ao partner_id
        if ($request->project_name != $this->partner_id){
            return response()->json(["result" => (object)[], "status" => -1, "error_message" => "Internal Error "], 200);
        }

        $UserID = $request->user_id;

        $User = null;
        $User = User::Where('id', $UserID)->lockForUpdate()->first();

        if (!$User)
        {
            return response()->json(["result" => (object)[], "status" => 14, "error_message" => "User Does Not exist"], 200);
        }

        if ($User->banned == 1){
            return response()->json(["result" => (object)[], "status" => 15, "error_message" => "User is banned"], 200);
        }

        return response()->json([
            'result' => [
                'balance' => (float)$User->wallet->balance + ($this->allow_bonus ? $User->wallet->balance_bonus : 0)
            ],
            'status' => 0,
            'error_message' => ''
        ], 200);
    }

    public function change_balance(Request $request)
    {
        $ip = $request->header('X-Forwarded-For') ?? $request->ip();

        \DB::table('debug')->insert(['text' => 'change_balance > IP: ' . $ip . " > " . json_encode($request->all())]);

        // Verificar se o project_name corresponde ao partner_id
        if ($request->project_name != $this->partner_id){
            return response()->json(["result" => (object)[], "status" => -1, "error_message" => "Internal Error "], 200);
        }

        $CheckOne = SportBetSummary::Where('transactionId', $request->transaction_id)->first();

        // Verificar se existe transação para cancel_debit e cancel_credit
        if (!$CheckOne) {
            if (in_array($request->transaction_type, ["cancel_debit", "cancel_credit"])) {
                return response()->json(["result" => (object)[], "status" => 17, "error_message" => "Transaction not found"], 200);
            }
        }

        $CheckTwo = SportBetSummary::Where('transactionId', $request->transaction_id)->Where('operation', "credit")->first();

        if ($CheckTwo) {
            if ($request->transaction_type == "credit") {
                return response()->json(["result" => (object)[], "status" => -1, "error_message" => "Internal Error "], 200);
            }
        }

        $UserID = $request->user_id;

        $User = null;
        $User = User::Where('id', $UserID)->lockForUpdate()->first();

        if (!$User)
        {
            return response()->json(["result" => (object)[], "status" => 14, "error_message" => "User Does Not exist"], 200);
        }

        if ($User->banned == 1){
            return response()->json(["result" => (object)[], "status" => 15, "error_message" => "User is banned"], 200);
        }

        if ($request->transaction_type === 'debit') {
            $totalBalance = $User->wallet->balance + ($this->allow_bonus ? $User->wallet->balance_bonus : 0);

            if ($totalBalance < $request->amount) {
                return response()->json(["result" => (object)[], "status" => 13, "error_message" => "Insufficient Balance"], 200);
            }
        }

        if (in_array($request->transaction_type, ['debit', 'cancel_credit', 'lose'])) {
            /*if ($this->allow_bonus) {
                if ($User->wallet->balance >= $remaining) {
                    $User->wallet->balance -= $remaining;
                } else {
                    $remaining -= $User->wallet->balance;
                    $User->wallet->balance = 0;
                    $User->wallet->balance_bonus -= $remaining;
                }
            }else{
                $User->wallet->balance -= $remaining;
            }*/

            if ($this->allow_bonus) {
                if ($User->wallet->balance >= $request->amount) {
                    $User->wallet->balance = $User->wallet->balance - $request->amount;
                    $User->wallet->last_used = "balance";
                } else {
                    $remaining = $request->amount - $User->wallet->balance;
                    $User->wallet->balance = 0;
                    $User->wallet->balance_bonus = $User->wallet->balance_bonus - $remaining;
                    $User->wallet->last_used = "balance_bonus";
                }
            } else {
                $User->wallet->balance -= $request->amount;
            }

            if ($User->wallet->anti_bot > 0) {
                $User->wallet->anti_bot = max(0, $User->wallet->anti_bot - $request->amount);
            }
        } elseif (in_array($request->transaction_type, ['credit', 'cancel_debit'])) {
            if ($this->allow_bonus) {
                if ($User->wallet->last_used == "balance_bonus") {
                    $User->wallet->balance_bonus = $User->wallet->balance_bonus + $request->amount;
                } else {
                    $User->wallet->balance = $User->wallet->balance + $request->amount;
                }
            } else {
                $User->wallet->balance += $request->amount;
            }
        }

        $User->wallet->save();

        // Preparar dados adicionais para apostas (debit)
        $additionalData = [];
        if ($request->transaction_type === 'debit' && $request->has('additional_data')) {
            $additionalData = $request->additional_data;
        }

        $InfSum = SportBetSummary::create([
            'provider' => 'digitain',
            'user_id' => (String)$User->id,
            'transactionId' => $request->transaction_id,
            'operation' => $request->transaction_type,
            'status' => ($request->transaction_type == 'debit') ? 'Pending' : 'Completed',
            'statusel' => 'Nil',
            'reason' => 'Nil',
            'amount' => $request->amount,
            'transaction' => '{}',
            'betslip' => json_encode($additionalData),
        ]);

        return response()->json([
            'result' => [
                'balance' => (float)$User->wallet->balance + ($this->allow_bonus ? $User->wallet->balance_bonus : 0),
                'txn_id' => $InfSum->id,
            ],
            'status' => 0,
            'error_message' => ''
        ], 200);
    }
}
