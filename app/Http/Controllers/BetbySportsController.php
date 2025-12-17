<?php

namespace App\Http\Controllers;

use App\Helpers\Core as Helper;
use App\Models\Admin\CustomCSS;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class BetbySportsController extends Controller
{
    /**
     * Página principal do Betby Sportsbook
     */
    public function index(Request $request)
    {
        // Verificar se Betby está ativo
        if (!Settings::isBetbyActive()) {
            // Se Betby não estiver ativo, redirecionar para home
            return redirect()->route('home')->with('error', 'Serviço de esportes não disponível no momento.');
        }
        
        $user = Auth::user();

        // Configurações do Betby vindas do arquivo de config
        $betbyConfig = [
            'brand_id' => config('betby.brand_id'),
            'operator_id' => config('betby.operator_id'),
            'api_url' => config('betby.is_production')
                ? config('betby.production_api_url')
                : config('betby.api_url'),
            'bt_library_url' => config('betby.bt_library_url'),
            'external_api_url' => config('betby.external_api_url'),
            'theme_name' => config('betby.theme_name'),
            'currency' => config('betby.currency'),
            'language' => app()->getLocale() ?? 'pt_BR',
        ];

        // Gerar token JWT se o usuário estiver autenticado
        $jwtToken = null;
        if ($user) {
            $jwtToken = $this->generateJWTToken($user);
        }

        // Obter tema ativo do CSS customizado
        $customCss = CustomCSS::first();
        $activeTheme = $customCss ? $customCss->active_theme : 1; // Default para tema 1

        // Capturar parâmetros especiais da URL
        $btPath = $request->get('bt-path');
        $btBookingCode = $request->get('btBookingCode');
        
        return view('esportes.betby', [
            'betbyConfig' => $betbyConfig,
            'jwtToken' => $jwtToken,
            'user' => $user,
            'activeTheme' => $activeTheme,
            'btPath' => $btPath,
            'btBookingCode' => $btBookingCode
        ]);
    }

    /**
     * Página de widget específico do Betby
     */
    public function widget(Request $request, $type)
    {
        $user = Auth::user();

        $betbyConfig = [
            'brand_id' => config('betby.brand_id'),
            'operator_id' => config('betby.operator_id'),
            'api_url' => config('betby.is_production')
                ? config('betby.production_api_url')
                : config('betby.api_url'),
            'bt_library_url' => config('betby.bt_library_url'),
            'external_api_url' => config('betby.external_api_url'),
            'theme_name' => config('betby.theme_name'),
            'currency' => config('betby.currency'),
            'language' => app()->getLocale() ?? 'pt_BR',
        ];

        $jwtToken = null;
        if ($user) {
            $jwtToken = $this->generateJWTToken($user);
        }

        // Obter tema ativo do CSS customizado
        $customCss = CustomCSS::first();
        $activeTheme = $customCss ? $customCss->active_theme : 1; // Default para tema 1

        return view('betby.sports.widget', [
            'betbyConfig' => $betbyConfig,
            'jwtToken' => $jwtToken,
            'user' => $user,
            'widgetType' => $type,
            'activeTheme' => $activeTheme
        ]);
    }

    /**
     * Atualizar token JWT
     */
    public function refreshToken(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'error' => 'Usuário não autenticado'
            ], 401);
        }

        $newToken = $this->generateJWTToken($user);

        return response()->json([
            'token' => $newToken,
            'expires_at' => now()->addHours(24)->timestamp
        ]);
    }

    /**
     * Gerar token JWT para o usuário
     */
    private function generateJWTToken($user)
    {
        $now = time();

        if ($user->language == "pt_BR") {
            $idioma = "pt-br";
        }else{
            $idioma = $user->language;
        }

        $Settings = Helper::getSetting();

        $payload = [
            'iat' => $now,
            'exp' => $now + (config('betby.token_expiry_hours', 24) * 60 * 60),
            'jti' => uniqid(),
            'iss' => config('betby.brand_id'),
            'aud' => config('betby.brand_id'),
            'sub' => $Settings->sportpartnername . '-' . $user->id,
            'name' => $user->name,
            'lang' => $idioma,
            'currency' => config('betby.currency'),
            'odds_format' => config('betby.odds_format'),
            'ff' => config('betby.feature_flags'),
            'nbf' => $now // Not before
        ];

        $privateKeyPath  = config('betby.private_key');
        $privateKey = file_get_contents($privateKeyPath );

        $algorithm = config('betby.jwt_algorithm', 'ES256');

        try {
            return JWT::encode($payload, $privateKey, $algorithm);
        } catch (\Exception $e) {
            \Log::error('Erro ao gerar token JWT para Betby: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Mapear idioma do Laravel para o formato do Betby
     */
    private function mapLanguage($locale)
    {
        $languageMap = config('betby.languages');

        return $languageMap[$locale] ?? 'en';
    }
}
