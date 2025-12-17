<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Transactions;
use App\Models\DailyGift;
use App\Models\GameHistory;

class MinigamesController extends Controller
{
    /**
     * Exibe a página do presente diário
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function boxDiaria(Request $request)
    {
        $user = Auth::user();

        // Se o usuário não estiver autenticado, redireciona para login
        if (!$user) {
            return redirect()->route('login');
        }

        // Obtém o ranking do usuário
        $ranking = $user->getRanking() ?? [
            'level' => 1,
            'name' => 'Bronze',
            'image' => 'img/ranking/1.png',
            'current_deposit' => 0,
            'next_level' => 2,
            'next_level_deposit' => 200,
            'progress' => 0,
            'has_reward' => false,
            'reward_id' => null
        ];

        // Obtém o total de missões concluídas
        $missionsCompleted = \App\Models\MissionCompletion::where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->count();

        // Obtém os últimos 7 dias para os prêmios diários
        $hoje = Carbon::now();
        $dias = [];
        
        // Cria um array com os dados dos últimos 7 dias
        for ($i = 6; $i >= 0; $i--) {
            $data = $hoje->copy()->subDays($i);
            $formattedDate = $data->format('d M'); // Formato: "08 jun"
            
            // Verifica se o usuário já recebeu o prêmio neste dia
            $premioClaimed = $this->verificaPremioRecebido($user->id, $data);
            
            // Obtém informações sobre o presente recebido, se houver
            $presenteInfo = null;
            if ($premioClaimed) {
                $presenteInfo = DailyGift::where('user_id', $user->id)
                    ->whereDate('gift_date', $data->format('Y-m-d'))
                    ->first();
            }
            
            // Obtém o prêmio disponível para o dia
            $premioInfo = $presenteInfo ? [
                'premio' => $presenteInfo->gift_type,
                'imagem' => $presenteInfo->gift_image,
                'nome' => $presenteInfo->gift_name
            ] : $this->getPremioInfo($data);
            
            $dias[] = [
                'data' => $formattedDate,
                'data_completa' => $data->format('Y-m-d'),
                'claimed' => $premioClaimed,
                'premio' => $premioInfo['premio'],
                'premio_imagem' => $premioInfo['imagem'],
                'premio_nome' => $premioInfo['nome'],
                'missed' => $data->lt($hoje->copy()->startOfDay()) && !$premioClaimed,
                'is_today' => $data->isSameDay($hoje)
            ];
        }

        // Retorna a view com os dados necessários
        return view('minigames.boxdiaria', compact('user', 'ranking', 'missionsCompleted', 'dias'));
    }

    /**
     * Verifica se o usuário já recebeu o prêmio em uma determinada data
     *
     * @param int $userId ID do usuário
     * @param Carbon $data Data para verificação
     * @return bool
     */
    private function verificaPremioRecebido($userId, Carbon $data)
    {
        // Verifica no banco de dados se o usuário já recebeu o prêmio nesta data
        return DailyGift::hasUserClaimedOnDate($userId, $data->format('Y-m-d'));
    }

    /**
     * Obtém informações sobre o prêmio disponível para uma data específica
     *
     * @param Carbon $data Data para a qual buscar o prêmio
     * @return array Informações do prêmio
     */
    private function getPremioInfo(Carbon $data)
    {
        // Em uma implementação real, você buscaria essas informações no banco de dados
        // Aqui estamos usando um array simulado com opções de prêmios

        $premios = [
            [
                'premio' => 'raspadinha',
                'imagem' => 'https://d146b4m7rkvjkw.cloudfront.net/2a443df74f136605711831-RASPADINHACOMIPHONE256.png',
                'nome' => 'Raspadinha com iPhone'
            ],
            [
                'premio' => 'roleta',
                'imagem' => 'https://d146b4m7rkvjkw.cloudfront.net/710c016ae41f1d51502639-ROLETACOMIPHONE256.png',
                'nome' => 'Roleta com iPhone'
            ],
            [
                'premio' => 'jogo',
                'imagem' => 'https://d146b4m7rkvjkw.cloudfront.net/ab2d757b873fd84108797b-MASTERJOKER256.png',
                'nome' => 'Master Joker'
            ],
            [
                'premio' => 'dragoes',
                'imagem' => 'https://d146b4m7rkvjkw.cloudfront.net/2ff9a429f4f1c9f1f688e9-888dragons256.png',
                'nome' => '888 Dragons'
            ]
        ];

        // Usa o dia da semana como índice para selecionar o prêmio
        $index = $data->dayOfWeek % count($premios);
        return $premios[$index];
    }

    /**
     * Processa a solicitação para receber o prêmio diário
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function receberPremio(Request $request)
    {
        $user = Auth::user();
        
        // Verifica se o usuário está autenticado
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Usuário não autenticado'], 401);
        }
        
        // Obtém a data do prêmio a ser recebido
        $data = $request->input('data', Carbon::now()->format('Y-m-d'));
        $dataCarbon = Carbon::parse($data);
        
        // Verifica se o usuário já recebeu o prêmio nesta data
        if ($this->verificaPremioRecebido($user->id, $dataCarbon)) {
            return response()->json(['success' => false, 'message' => 'Você já recebeu este prêmio'], 400);
        }
        
        // Verifica se está tentando receber um prêmio futuro
        if ($dataCarbon->gt(Carbon::now())) {
            return response()->json(['success' => false, 'message' => 'Você não pode receber prêmios futuros'], 400);
        }
        
        // Obtém as informações do prêmio
        $premioInfo = $this->getPremioInfo($dataCarbon);
        
        // Registra que o usuário recebeu o prêmio
        DailyGift::registerGift(
            $user->id, 
            $dataCarbon->format('Y-m-d'),
            $premioInfo['premio'],
            $premioInfo['nome'],
            $premioInfo['imagem']
        );
        
        // Retorna resposta de sucesso com informações do prêmio
        return response()->json([
            'success' => true, 
            'message' => 'Prêmio recebido com sucesso!',
            'premio' => $premioInfo
        ]);
    }
} 