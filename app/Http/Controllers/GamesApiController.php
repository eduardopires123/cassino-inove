<?php

namespace App\Http\Controllers;

use App\Helpers\Core as Helper;

use App\Models\User;
use Illuminate\Http\Request;

use App\Models\GameHistory;
use App\Models\AffiliatesHistory;

class GamesApiController extends Controller
{
    public $allow_bonus;

    public function __construct()
    {
        $Settings = Helper::getSetting();

        $this->allow_bonus = $Settings->enable_cassino_bonus;
    }

    public function cassino(Request $request)
    {
        $method     = $request->input('method');
        $userCode   = $request->input('user_code');

        $preinfo    = explode('-', $userCode);
        $user_code  = $preinfo[1];

        \DB::table('debug')->insert(['user_id' => $user_code, 'api' => 'FiverScan callback', 'text' => json_encode($request->all()), 'created_at' => now(), 'updated_at' => now()]);

        if ($method == "user_balance") {
            $user = User::where("id", $user_code)->first();

            return response()->json([
                "status" => 1,
                "user_balance" => $user->wallet->balance + ($this->allow_bonus ? $user->wallet->balance_bonus : 0)
            ]);
        }elseif ($method == "transaction") {
            $type       = $request->input('game_type');
            $gameData   = $request->input($type);

            $gameCode   = $gameData['game_code'];
            $bet        = $gameData['bet_money'];
            $win        = $gameData['win_money'];
            $txnId      = $gameData['txn_id'];
            $RetInfo    = $gameData['txn_type'];

            $parts = explode('-', $txnId);
            $roundId = implode('-', array_slice($parts, 0, 4));

            return $this->setTransaction($user_code, $bet, $win, ($win - $bet), $txnId, $gameCode, $roundId, $request, $RetInfo);
        }

        return response()->json([
            "status" => 0,
            "user_balance" => 0,
            "msg" => "INTERNAL_ERROR",
        ]);
    }

    private function setTransaction($user_code, $bet_money, $win_money, $winLose, $txn_id, $game_code, $round_id, $dados, $type)
    {
        $Settings   = Helper::getSetting();
        $user       = User::where("id", $user_code)->first();

        $bet_money  = (float)$bet_money;
        $win_money  = (float)$win_money;

        if (($bet_money == 0) and ($win_money == 0)) {
            return response()->json([
                "status" => 1,
                "user_balance" => $user->wallet->balance + ($this->allow_bonus ? $user->wallet->balance_bonus : 0)
            ]);
        }

        if ($bet_money > $user->wallet->balance + ($this->allow_bonus ? $user->wallet->balance_bonus : 0)) {
            \DB::table('debug')->insert(['user_id' => $user_code, 'api' => 'FiverScan Aposta < Saldo', 'text' => json_encode($dados->all()), 'created_at' => now(), 'updated_at' => now()]);

            return response()->json([
                "status" => 0,
                "msg" => "INSUFFICIENT_USER_FUNDS",
            ]);
        }

        $rollover = 0;

        if ($user->wallet->balance_bonus_rollover > 0.0) {
            $rollover = $user->wallet->balance_bonus_rollover_used + floatval($bet_money);
        }

        if ($bet_money > 0 && $win_money == 0) {
            if ($user->wallet->balance >= $bet_money) {
                $user->wallet->balance = $user->wallet->balance - $bet_money;
                $user->wallet->last_used = "balance";
            } else {
                $remaining = $bet_money - $user->wallet->balance;
                $user->wallet->balance = 0;
                $user->wallet->balance_bonus = $user->wallet->balance_bonus - $remaining;
                $user->wallet->last_used = "balance_bonus";
            }
        }

        if ($win_money > 0 && $bet_money == 0) {
            if ($user->wallet->last_used == "balance_bonus") {
                $user->wallet->balance_bonus = $user->wallet->balance_bonus + $win_money;
            } else {
                $user->wallet->balance = $user->wallet->balance + $win_money;
            }
        }

        if ($bet_money > 0 && $win_money > 0) {
            $net_win = $win_money - $bet_money;

            if ($net_win != 0) {
                if ($net_win > 0) {
                    if ($user->wallet->balance >= $bet_money) {
                        $user->wallet->balance = $user->wallet->balance - $bet_money + $win_money;
                        $user->wallet->last_used = "balance";
                    } else {
                        $remaining = $bet_money - $user->wallet->balance;
                        $user->wallet->balance = 0;
                        $user->wallet->balance_bonus = $user->wallet->balance_bonus - $remaining + $win_money;
                        $user->wallet->last_used = "balance_bonus";
                    }
                } else {
                    $net_loss = abs($net_win);
                    if ($user->wallet->balance >= $net_loss) {
                        $user->wallet->balance = $user->wallet->balance - $net_loss;
                        $user->wallet->last_used = "balance";
                    } else {
                        $remaining = $net_loss - $user->wallet->balance;
                        $user->wallet->balance = 0;
                        $user->wallet->balance_bonus = $user->wallet->balance_bonus - $remaining;
                        $user->wallet->last_used = "balance_bonus";
                    }
                }
            }
        }

        $user->wallet->balance_bonus_rollover_used = $rollover;

        if ($type == "debit_credit") {
            \DB::table('debug')->insert(['user_id' => $user_code, 'api' => 'FiverScan Refund', 'text' => $win_money, 'created_at' => now(), 'updated_at' => now()]);

            $user->wallet->anti_bot += $win_money;

            if ($user->wallet->balance_bonus_rollover > 0.0) {
                $user->wallet->balance_bonus_rollover_used -= $win_money;
            }
        }else{
            if ($user->wallet->anti_bot > 0) {
                $user->wallet->anti_bot = max(0, $user->wallet->anti_bot - $bet_money);
            }
        }

        $user->wallet->save();

        GameHistory::create([
            'user_id' => $user_code,
            'amount' => $win_money > 0 ? $win_money : $bet_money,
            'provider' => "Inove",
            'provider_tx_id' => $txn_id,
            'game' => $game_code,
            'action' => $win_money > 0 ? 'win' : 'loss',
            'round_id' => $round_id ?: $txn_id,
            'session_token' => $txn_id,
            'json' => json_encode($dados->all())
        ]);

        // Cálculo de RevShare deve ocorrer apenas quando winLose for diferente de 0
        if ($winLose != 0) {
            if ($Settings->revenabled == 1) {
                $this->calculateRevShare($user, $win_money, $winLose, $game_code);
            }
        }

        return response()->json([
            "status" => 1,
            "user_balance" => $user->wallet->balance + ($this->allow_bonus ? $user->wallet->balance_bonus : 0)
        ]);
    }

    private function calculateRevShare($user, $win_money, $winLose, $game)
    {
        $Settings = Helper::getSetting();

        if ($user->inviter && $winLose != 0) {
            $afiliado = User::where('id', $user->inviter)->first();
            if ($afiliado) {
                $refAff = $afiliado->wallet->referPercent ? $afiliado->wallet->referPercent : $Settings->percent_aff;

                // Se winLose for negativo, significa que o usuário perdeu, então o afiliado ganha uma porcentagem da perda
                // Se winLose for positivo, significa que o usuário ganhou, então o afiliado perde uma porcentagem do ganho

                $refRewards = $winLose < 0 ? abs($winLose) * ($refAff / 100) : -$winLose * ($refAff / 100);
                $afiliado->wallet->update([
                    'refer_rewards' => $afiliado->wallet->refer_rewards + $refRewards,
                ]);

                AffiliatesHistory::create([
                    'user_id' => $user->id,
                    'inviter' => $user->inviter,
                    'game' => $game,
                    'amount' => $refRewards
                ]);
            }
        }
    }
}
