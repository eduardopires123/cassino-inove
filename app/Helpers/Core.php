<?php

namespace App\Helpers;

use App\Models\Settings;
use App\Models\User;
use App\Models\Transactions;
use App\Models\GamesApi;
use App\Models\Gateways;
use App\Models\Wallet;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Core
{
    public static function CheckAdm()
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

    public static function removeCurrencyFormatting($value) {
        $cleanValue = str_replace(['$', 'R$', '€', '.', ','], ['', '', '', '', '.'], $value);

        return floatval($cleanValue);
    }

    public static function convertToCents($amount) {
        $cents = round($amount * 100);
        return $cents;
    }

    public static function convertToRealAmount($amount) {
        $real = round($amount / 100);
        return $real;
    }

    public static function soNumero($str) {
        return preg_replace("/[^0-9]/", "", $str);
    }

    public static function porcentagem_xn( $porcentagem, $total )
    {
        return ( $porcentagem / 100 ) * $total;
    }

    public static function porcentagem_nx( $parcial, $total ) {
        if(!empty($parcial) && !empty($total)) {
            return ( $parcial * 100 ) / $total;
        }else{
            return 0;
        }
    }

    function porcentagem_nnx( $parcial, $porcentagem ) {
        return ( $parcial / $porcentagem ) * 100;
    }

    public static function getSetting()
    {
        $settings = null;
        // Verificar se o cache existe
        if (Cache::has('settings')) {
            $settings = Cache::get('settings');
        } else {
            // Carregar da base de dados e salvar no cache
            $settings = Settings::first();
            // Defina o tempo de expiração do cache, por exemplo, 60 minutos
            Cache::put('settings', $settings, now()->addMinutes(60));
        }

        return $settings;
    }

    public static function getPGGames()
    {
        $PGGames = null;
        if(Cache::has('PGGames')) {
            $PGGames = Cache::get('PGGames');
        }else{
            $PGGames = DB::table('api_games_slugs')
                ->join('games_api', 'api_games_slugs.id_game', '=', 'games_api.id')
                ->join('providers', 'api_games_slugs.provider_id', '=', 'providers.id')
                ->where('api_games_slugs.active', 1)
                ->where('providers.name', 'PGSOFT')
                ->select('games_api.*')
                ->distinct()
                ->orderBy('games_api.order_value', 'desc')
                ->get();
            Cache::put('PGGames', $PGGames);
        }

        return $PGGames;
    }

    /* EdPay - Gateway de Pagamentos */

    public static function GetBalanceEdPay()
    {
        $Settings = Gateways::where('nome', 'EdPay')->first();

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'pubkey' => $Settings->public_clientid_key,
            'seckey' => $Settings->secret_key,
        ])->post($Settings->api_url . 'authorization', []);

        if ($response->successful()) {
            $data   = $response->json();
            $token  = $data['token'];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($Settings->api_url . 'balance', []);

            if ($response->successful()) {
                $data = $response->json();
                return $data;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public static function GeraQRCodeEdPay($Usuario, $Valor, $description = null)
    {
        $Settings = Gateways::where('nome', 'EdPay')->first();

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'pubkey' => $Settings->public_clientid_key,
            'seckey' => $Settings->secret_key,
        ])->post($Settings->api_url . 'authorization', []);

        if ($response->successful()) {
            $data   = $response->json();
            $token  = $data['token'];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($Settings->api_url . 'qrcode', ['amount' => $Valor, 'description' => $description]);

            if ($response->successful()) {
                $data = $response->json();
                return $data;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public static function GeraSaqueEdPay($Usuario, $Valor)
    {
        $Settings = Gateways::where('nome', 'EdPay')->first();

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'pubkey' => $Settings->public_clientid_key,
            'seckey' => $Settings->secret_key,
        ])->post($Settings->api_url . 'authorization', []);

        if ($response->successful()) {
            $data   = $response->json();
            $token  = $data['token'];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($Settings->api_url . 'transfer', [
                'amount' => $Valor,
                'pix_key' => $Usuario->pix,
                'pix_type' => 'CPF',
            ]);

            if ($response->successful()) {
                $setting = Settings::first();

                if ($setting->revenabled == 1) {
                    $Indicou = User::where('id', $Usuario->inviter)->first();

                    if ($Indicou) {
                        if ($Indicou->is_affiliate == 1) {
                            $Valor = $Valor * ($Indicou->wallet->referPercent / 100);
                            $Indicou->wallet->decrement('refer_rewards', $Valor);
                        }
                    }
                }

                $data = $response->json();
                return $data;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}
