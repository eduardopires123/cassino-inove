<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\LuckyBox;
use App\Models\LuckyBoxPurchase;
use App\Models\Wallet;
use App\Models\LuckyBoxPrizeOption;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Admin\Logs;

class LuckyBoxController extends Controller
{
    /**
     * Exibe a página das caixas da sorte
     */
    public function index()
    {
        // Get active lucky boxes ordered by order field
        $boxes = LuckyBox::where('is_active', true)
            ->orderBy('order', 'asc')
            ->get();
            
        return view('payment.shop', compact('boxes'));
    }

    /**
     * Processa a abertura de uma caixa da sorte
     */
    public function openBox(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validar os dados recebidos
            $request->validate([
                'level' => 'required|integer|min:1',
            ]);

            $user = Auth::user();
            $level = $request->level;
            $boxId = $request->id; // ID da caixa enviado do frontend
            
            Log::info('Requisição de abertura de caixa recebida', [
                'level' => $level,
                'box_id' => $boxId,
                'user_id' => $user->id
            ]);
            
            // Buscar a caixa pelo ID se disponível, caso contrário pelo level
            if (!empty($boxId)) {
                $box = LuckyBox::where('id', $boxId)->where('is_active', true)->first();
                $searchMethod = 'ID';
            } else {
                $box = LuckyBox::where('level', $level)->where('is_active', true)->first();
                $searchMethod = 'level';
            }
            
            // Check if the box exists
            if (!$box) {
                Log::warning('Caixa não encontrada', [
                    'search_method' => $searchMethod,
                    'level' => $level,
                    'box_id' => $boxId
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => __('messages.box_not_found')
                ]);
            }
            
            Log::info('Caixa encontrada pelo ' . $searchMethod, [
                'id' => $box->id,
                'nome' => $box->name,
                'level' => $box->level,
                'preço' => $box->price
            ]);
            
            // Obter a carteira do usuário
            $wallet = Wallet::where('user_id', $user->id)->first();
            
            if (!$wallet) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.wallet_not_found')
                ]);
            }
            
            // Verificar se o usuário tem saldo suficiente
            if ($wallet->coin < $box->price) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.insufficient_balance')
                ]);
            }
            
            // Verificar limite diário para caixas com limites
            if ($box->daily_limit > 0) {
                $dailyPurchases = LuckyBoxPurchase::where('user_id', $user->id)
                    ->where('level', $box->level)
                    ->whereDate('created_at', now()->toDateString())
                    ->count();
                    
                if ($dailyPurchases >= $box->daily_limit) {
                    return response()->json([
                        'success' => false,
                        'message' => __('messages.daily_limit_reached')
                    ]);
                }
            }
            
            // Obter todas as opções de prêmios ativas para esta caixa usando ID
            $prizeOptions = LuckyBoxPrizeOption::where('lucky_box_id', $box->id)
                ->where('is_active', true)
                ->get();
                
            Log::info('Opções de prêmios para a caixa ID: ' . $box->id, [
                'total_options' => $prizeOptions->count(),
                'options' => $prizeOptions->toArray()
            ]);
                
            if ($prizeOptions->isEmpty()) {
                Log::warning('Nenhuma opção de prêmio encontrada para a caixa ID: ' . $box->id . ' com level: ' . $level);
                
                // Tentar buscar por level como fallback para compatibilidade com dados antigos
                $prizeOptions = LuckyBoxPrizeOption::where('lucky_box_id', $level)
                    ->where('is_active', true)
                    ->get();
                    
                Log::info('Tentativa de fallback buscando pelo level ' . $level, [
                    'total_options' => $prizeOptions->count()
                ]);
                
                if ($prizeOptions->isEmpty()) {
                    return response()->json([
                        'success' => false,
                        'message' => __('messages.no_prize_options')
                    ]);
                }
            }
            
            // Debitar os coins da carteira do usuário
            $wallet->coin -= $box->price;
            
            // Selecionar um prêmio com base nas chances configuradas
            $selectedPrize = $this->selectRandomPrize($prizeOptions);
            
            Log::info('Prêmio selecionado:', [
                'prize' => $selectedPrize ? $selectedPrize->toArray() : 'Nenhum prêmio selecionado'
            ]);
            
            // Se não foi selecionado nenhum prêmio, usar a primeira opção disponível
            if (!$selectedPrize && !$prizeOptions->isEmpty()) {
                $selectedPrize = $prizeOptions->first();
                Log::info('Nenhum prêmio foi selecionado, usando o primeiro disponível: ' . $selectedPrize->id);
            }
            
            // Aplicar o prêmio ao usuário
            $prizeAmount = 0;
            $spinsAmount = 0;
            $prizeType = null;
            $isLowPrize = false;
            
            if ($selectedPrize) {
                $prizeType = $selectedPrize->prize_type;
                
                switch ($prizeType) {
                    case 'real_balance':
                        $prizeAmount = $this->calculatePrizeAmount($selectedPrize);
                        $oldValue = $wallet->balance;
                        $wallet->balance += $prizeAmount;
                        $isLowPrize = $prizeAmount < 1;
                        Log::info('Adicionando saldo real: ' . $prizeAmount);
                        
                        // Registrar no log
                        \App\Models\Admin\Logs::create([
                            'field_name' => 'Saldo Real LuckBox',
                            'old_value' => $oldValue,
                            'new_value' => $wallet->balance,
                            'updated_by' => $box->id, // ID da caixa
                            'user_id' => $user->id,
                            'type' => 4, // Tipo 4 para Saldo Real
                            'log' => 'Usuário recebeu ' . $prizeAmount . ' de saldo real da caixa ' . $box->name
                        ]);
                        break;
                    case 'bonus':
                        $prizeAmount = $this->calculatePrizeAmount($selectedPrize);
                        $oldValue = $wallet->balance_bonus;
                        $wallet->balance_bonus += $prizeAmount;
                        $isLowPrize = $prizeAmount < 1;
                        Log::info('Adicionando saldo bônus: ' . $prizeAmount);
                        
                        // Registrar no log
                        \App\Models\Admin\Logs::create([
                            'field_name' => 'Bonus LuckBox',
                            'old_value' => $oldValue,
                            'new_value' => $wallet->balance_bonus,
                            'updated_by' => $box->id, // ID da caixa
                            'user_id' => $user->id,
                            'type' => 5, // Tipo 5 para Bônus
                            'log' => 'Usuário recebeu ' . $prizeAmount . ' de bônus da caixa ' . $box->name
                        ]);
                        break;
                    case 'free_spins':
                        $spinsAmount = $this->calculateSpinsAmount($selectedPrize);
                        $oldValue = $wallet->free_spins;
                        $wallet->free_spins += $spinsAmount;
                        $isLowPrize = $spinsAmount < 10;
                        Log::info('Adicionando rodadas grátis: ' . $spinsAmount);
                        
                        // Registrar no log
                        \App\Models\Admin\Logs::create([
                            'field_name' => 'Rodadas Grátis LuckBox',
                            'old_value' => $oldValue,
                            'new_value' => $wallet->free_spins,
                            'updated_by' => $box->id, // ID da caixa
                            'user_id' => $user->id,
                            'type' => 3, // Tipo 3 para Rodadas Grátis
                            'log' => 'Usuário recebeu ' . $spinsAmount . ' rodadas grátis da caixa ' . $box->name
                        ]);
                        break;
                    case 'coins':
                        $prizeAmount = $this->calculatePrizeAmount($selectedPrize);
                        $wallet->coin += $prizeAmount;
                        $isLowPrize = $prizeAmount < 5;
                        Log::info('Adicionando coins: ' . $prizeAmount);
                        break;
                    default:
                        // Caso o tipo não seja reconhecido, dar rodadas grátis como fallback
                        $prizeType = 'free_spins';
                        $spinsAmount = 1;
                        $oldValue = $wallet->free_spins;
                        $wallet->free_spins += $spinsAmount;
                        $isLowPrize = false;
                        Log::info('Tipo de prêmio não reconhecido, adicionando rodadas grátis como fallback: ' . $spinsAmount);
                        
                        // Registrar no log
                        \App\Models\Admin\Logs::create([
                            'field_name' => 'Rodadas Gratis LuckBox',
                            'old_value' => $oldValue,
                            'new_value' => $wallet->free_spins,
                            'updated_by' => $box->id, // ID da caixa
                            'user_id' => $user->id,
                            'type' => 3, // Tipo 3 para Rodadas Grátis
                            'log' => 'Usuário recebeu ' . $spinsAmount . ' rodadas grátis da caixa ' . $box->name
                        ]);
                        break;
                }
            } else {
                // Caso extremo onde não há prêmio selecionado nem opções disponíveis
                // Dar um prêmio mínimo de 1 rodada grátis
                $prizeType = 'free_spins';
                $spinsAmount = 1;
                $wallet->free_spins += $spinsAmount;
                $isLowPrize = false;
                Log::info('Nenhum prêmio selecionado e nenhuma opção disponível, adicionando rodadas grátis mínima: ' . $spinsAmount);
            }
            
            // Salvar as alterações na carteira
            $wallet->save();
            
            // Registrar a compra
            $purchase = LuckyBoxPurchase::create([
                'user_id' => $user->id,
                'level' => $box->level,
                'cost' => $box->price,
                'prize_type' => $prizeType,
                'prize' => $prizeAmount,
                'spins_amount' => $spinsAmount
            ]);
            
            Log::info('Compra registrada:', [
                'purchase_id' => $purchase->id,
                'prize_amount' => $prizeAmount,
                'spins_amount' => $spinsAmount,
                'prize_type' => $prizeType
            ]);
            
            DB::commit();
            
            // Definir mensagem personalizada com base no tipo de prêmio
            $message = '';
            if ($prizeType === 'free_spins') {
                if ($isLowPrize) {
                    $message = "Você ganhou {$spinsAmount} rodadas grátis. Aproveite e tente novamente!";
                } else {
                    $message = "Parabéns! Você ganhou {$spinsAmount} rodadas grátis!";
                }
            } elseif ($prizeType === 'real_balance') {
                if ($isLowPrize) {
                    $message = "Você ganhou R$ " . number_format($prizeAmount, 2, ',', '.') . " em saldo real. Tente novamente!";
                } else {
                    $message = "Parabéns! Você ganhou R$ " . number_format($prizeAmount, 2, ',', '.') . " em saldo real!";
                }
            } elseif ($prizeType === 'bonus') {
                if ($isLowPrize) {
                    $message = "Você ganhou R$ " . number_format($prizeAmount, 2, ',', '.') . " em bônus. Tente novamente!";
                } else {
                    $message = "Parabéns! Você ganhou R$ " . number_format($prizeAmount, 2, ',', '.') . " em bônus!";
                }
            } else { // coins
                if ($isLowPrize) {
                    $message = "Você ganhou {$prizeAmount} coins. Tente novamente!";
                } else {
                    $message = "Parabéns! Você ganhou {$prizeAmount} coins!";
                }
            }
            
            // Retornar o resultado
            return response()->json([
                'success' => true,
                'title' => $isLowPrize ? 'Que pena!' : 'Parabéns!',
                'prize_type' => $prizeType,
                'amount' => $prizeAmount,
                'spins_amount' => $spinsAmount,
                'message' => $message,
                'box_name' => $box->name,
                'user_balance' => $wallet->balance,
                'user_bonus' => $wallet->balance_bonus,
                'user_coins' => $wallet->coin,
                'user_free_spins' => $wallet->free_spins
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao abrir caixa: ' . $e->getMessage(), ['exception' => $e]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Seleciona um prêmio aleatório com base nas chances configuradas
     */
    private function selectRandomPrize($prizeOptions)
    {
        // Verificação extra para garantir que temos opções
        if ($prizeOptions->isEmpty()) {
            Log::warning('Nenhuma opção de prêmio disponível');
            return null;
        }
        
        // Se houver apenas uma opção, retorná-la diretamente
        if ($prizeOptions->count() == 1) {
            Log::info('Apenas uma opção de prêmio disponível, selecionando automaticamente');
            return $prizeOptions->first();
        }

        // Criar um array com as chances de cada prêmio
        $chances = [];
        $totalChance = 0;
        
        foreach ($prizeOptions as $option) {
            // Garantir que a chance seja pelo menos 1% ou o valor configurado
            $chance = max(1, (float)$option->chance_percentage);
            
            $chances[] = [
                'option' => $option,
                'chance' => $chance
            ];
            
            $totalChance += $chance;
            
            Log::info('Opção de prêmio disponível', [
                'id' => $option->id,
                'tipo' => $option->prize_type,
                'chance' => $chance . '%',
                'min_amount' => $option->min_amount,
                'max_amount' => $option->max_amount,
                'min_spins' => $option->min_spins,
                'max_spins' => $option->max_spins
            ]);
        }
        
        Log::info('Total de chances: ' . $totalChance . '%');
        
        // Normalizar as chances se o total não for 100%
        $scaleFactor = 100 / $totalChance;
        
        // Gerar um número aleatório entre 0 e 100
        $random = mt_rand(0, 10000) / 100; // Para melhor precisão
        Log::info('Número aleatório gerado: ' . $random);
        
        // Selecionar o prêmio com base no número aleatório
        $cumulativeChance = 0;
        foreach ($chances as $index => $chance) {
            // Usar a chance normalizada
            $normalizedChance = $chance['chance'] * $scaleFactor;
            $cumulativeChance += $normalizedChance;
            
            Log::info('Chance cumulativa para opção ' . ($index + 1) . ': ' . $cumulativeChance . '%');
            
            if ($random <= $cumulativeChance) {
                Log::info('Prêmio selecionado: ID ' . $chance['option']->id . ' - Tipo: ' . $chance['option']->prize_type);
                return $chance['option'];
            }
        }
        
        // Se chegou aqui, é porque nenhum prêmio foi selecionado (pode acontecer devido a arredondamentos)
        // Retorna um prêmio aleatório como fallback
        $randomOption = $prizeOptions->random();
        Log::info('Nenhum prêmio selecionado pelas chances, usando fallback aleatório: ' . $randomOption->id);
        return $randomOption;
    }
    
    /**
     * Calcula o valor do prêmio com base no min_amount e max_amount
     */
    private function calculatePrizeAmount($prizeOption)
    {
        // Verificar se os valores são válidos
        $min = (float) $prizeOption->min_amount;
        $max = (float) $prizeOption->max_amount;
        
        // Garantir que os valores são números positivos
        $min = max(0, $min);
        $max = max($min, $max);
        
        Log::info('Calculando prêmio', [
            'min' => $min,
            'max' => $max,
            'tipo' => $prizeOption->prize_type
        ]);
        
        // Se min e max forem iguais, retorna o valor fixo
        if ($min == $max) {
            Log::info('Valor fixo de prêmio: ' . $min);
            return $min;
        }
        
        // Converter para centavos para cálculo preciso
        $minCents = (int)($min * 100);
        $maxCents = (int)($max * 100);
        
        // Gera um valor aleatório entre min e max
        $randomCents = mt_rand($minCents, $maxCents);
        $random = $randomCents / 100;
        
        // Garantir pelo menos 2 casas decimais
        $result = number_format($random, 2, '.', '');
        
        Log::info('Valor aleatório calculado: ' . $result);
        return (float)$result;
    }
    
    /**
     * Calcula a quantidade de rodadas grátis com base no min_spins e max_spins
     */
    private function calculateSpinsAmount($prizeOption)
    {
        // Verificar se os valores são válidos
        $min = (int) $prizeOption->min_spins;
        $max = (int) $prizeOption->max_spins;
        
        // Garantir que os valores são números positivos
        $min = max(1, $min); // Garantir pelo menos 1 rodada
        $max = max($min, $max);
        
        Log::info('Calculando rodadas grátis', [
            'min_spins' => $min,
            'max_spins' => $max
        ]);
        
        // Se min e max forem iguais, retorna o valor fixo
        if ($min == $max) {
            Log::info('Quantidade fixa de rodadas: ' . $min);
            return $min;
        }
        
        // Gera um valor aleatório entre min e max
        $result = mt_rand($min, $max);
        Log::info('Quantidade aleatória de rodadas: ' . $result);
        return $result;
    }
    
    /**
     * Retorna o histórico de compras de caixas do usuário
     */
    public function history()
    {
        $user = Auth::user();
        $purchases = LuckyBoxPurchase::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('payment.box-history', compact('purchases'));
    }
} 