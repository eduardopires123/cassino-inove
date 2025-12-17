<?php

namespace App\Http\Controllers;

use App\Helpers\Core as Helper;

use App\Models\User;
use App\Models\Game;
use App\Models\GamesApi;
use App\Models\DebugLogs;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

class GamesController extends Controller
{
    private $erro = "";

    public function __construct()
    {
    }

    public function outgame(Request $request)
    {
        if (Auth::check()) {
            User::where('id', Auth::id())->update(['playing' => 0]);
        }
    }

    /**
     * Retorna dados do jogo para uso via API (usado para URLs amigáveis)
     *
     * @param int $id ID do jogo
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGameData($id)
    {
        try {
            // Buscar informações do jogo diretamente na games_api
            $game = GamesApi::with('provider')->find($id);

            if (!$game || !$game->provider) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jogo não encontrado'
                ], 404);
            }

            // Limpar o nome do provedor removendo "ORIGINAL" e "OFICIAL"
            $cleanProviderName = $this->cleanProviderName($game->provider->provider_name ?: $game->provider->name);

            return response()->json([
                'success' => true,
                'id' => $game->id,
                'name' => $game->name,
                'provider_name' => $cleanProviderName
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar dados do jogo'
            ], 500);
        }
    }

    /**
     * Limpa o nome do provedor removendo palavras como ORIGINAL e OFICIAL
     *
     * @param string $providerName
     * @return string
     */
    private function cleanProviderName($providerName)
    {
        if (!$providerName) {
            return '';
        }

        // Remover palavras indesejadas e limpar
        $cleanName = preg_replace('/\b(ORIGINAL|OFICIAL)\b\s*-?\s*/i', '', $providerName);
        $cleanName = trim($cleanName);
        $cleanName = preg_replace('/\s+/', ' ', $cleanName); // Remover espaços duplos
        $cleanName = trim($cleanName, '- '); // Remover hífens e espaços nas extremidades

        return $cleanName;
    }

    public function startGame(Request $request)
    {
        // apenas para up
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $userToUpdate = User::where('id', $user->id)->lockForUpdate()->first();

        try {
            $token = hash('sha256', 'token-' . md5(auth()->user()->email . '-' . time()));
            $userToUpdate->update(['token_time' => time(), 'token' => $token, 'logged_in' => 1]);

            // Buscar jogo diretamente na games_api pelo ID
            // Aceitar tanto 'id' quanto 'slug' para compatibilidade durante transição
            $gameId = $request->input('id') ?? $request->input('slug');

            if (!$gameId) {
                return response()->json(['error' => 'Game ID not provided', 'message' => 'ID do jogo não fornecido!'], 200);
            }

            // Se for numérico, buscar por ID, senão tentar buscar por slug (compatibilidade)
            if (is_numeric($gameId)) {
                $game = GamesApi::with('provider')
                    ->where('id', $gameId)
                    ->where('status', 1)
                    ->first();
            } else {
                // Fallback para slug durante transição
                $game = GamesApi::with('provider')
                    ->where('slug', $gameId)
                    ->where('status', 1)
                    ->first();
            }

            if (!$game || !$game->provider) {
                return response()->json(['error' => 'Could not create token, Game not found', 'message' => 'Jogo não encontrado!'], 200);
            }

            // Atualizar views
            $game->increment('views');

            // Determinar qual método de lançamento usar baseado na distribuição
            $gameUrl = '';

            // Usar sempre Inove para lançamento de jogos
                $providerForInove = $game->provider->provider_name;
                $gameUrl = $this->launchGameInove($game->slug, $providerForInove);

            $isMobile = preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);

            if ($userToUpdate) {
                $userToUpdate->update(['playing' => 1]);
                $userToUpdate->increment('played');
            }

            // Buscar jogos mais visualizados para mostrar na view
            $mostViewedGames = GamesApi::with('provider')
                ->where('status', 1)
                ->whereNotNull('slug')
                ->whereNotNull('provider_id')
                ->orderBy('views', 'desc')
                ->limit(30)
                ->get()
                ->map(function($game) {
                    $game->provider_name = $game->provider->name ?? '';
                    return completeGameImageUrl($game);
                });

            if (!$isMobile) {
                return view('cassino.play', [
                    'gameURL' => $gameUrl,
                    'name' => $game->name,
                    'provider' => $game->provider->provider_name,
                    'views' => $game->views,
                    'mostViewedGames' => $mostViewedGames,
                    'Settings' => null
                ]);
            } else {
                return view('cassino.play_mobile', [
                    'gameURL' => $gameUrl,
                    'name' => $game->name,
                    'provider' => $game->provider->provider_name,
                    'views' => $game->views,
                    'mostViewedGames' => $mostViewedGames,
                    'Settings' => null
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'message' => 'Jogo em manutenção!' . $e->getMessage()], 200);
        }
    }

    /* Inove (FiverScan) */
    private function launchGameInove($game_code, $provider)
    {
        $Settings   = Helper::getSetting();
        $user       = auth()->user();

        $dados = [
            "token" => $Settings->sportpartnername,
            "user_code" => $user->id,
            "provider_code" => $provider,
            "game_code" => $game_code,
            "lang" => "pt",
            "mode" => $user->is_demo_agent
        ];

        $response = Http::post("https://api.inoveigaming.com/games/launch", $dados);

        if ($response->successful()){
            $data = $response->json();

            if (isset($data['status']) && $data['status'] == 0) {
                $errorMessage = isset($data['msg']) ? $data['msg'] : 'Erro da API Inove';
                throw new \Exception("Inove API Error: " . $errorMessage);
            }

            if (!isset($data['url'])) {
                throw new \Exception("URL não encontrada na resposta da API Inove");
            }

            return $data['url'];
        } else {
            $errorBody = $response->body();
            $errorMessage = "HTTP " . $response->status() . ": " . $errorBody;
            throw new \Exception("Falha na comunicação com API Inove: " . $errorMessage);
        }
    }
}
