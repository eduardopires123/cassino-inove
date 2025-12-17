<?php

namespace App\Models;

use App\Models\VipLevel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\HasRanking;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRanking, MustVerifyEmailTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'pix',
        'cpf',
        'phone',
        'image',
        'nascimento',
        'is_admin',
        'is_affiliate',
        'logged_in',
        'banned',
        'banned_reason',
        'banned_date',
        'inviter',
        'is_demo_agent',
        'status',
        'max_quantidade_saques_diario',
        'max_quantidade_saques_automaticos_diario',
        'playing',
        'played',
        'twitch_id',
        'email_verified_at',
        'phone_verified_at',
        'language',
        'vip_level ',
        'token_sport',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'integer',
        'is_affiliate' => 'boolean',
        'logged_in' => 'boolean',
        'banned' => 'boolean',
        'is_demo_agent' => 'boolean',
        'playing' => 'boolean',
        'played' => 'boolean',
        'banned_date' => 'datetime',
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
    ];

    /**
     * Obter a carteira associada ao usuário.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function affiliates()
    {
        return $this->hasMany(User::class, 'inviter', 'id');
    }

    /**
     * Relacionamento com as transações do usuário
     */
    public function transactions()
    {
        return $this->hasMany(Transactions::class);
    }

    /**
     * Relacionamento com os giros de roleta do usuário
     */
    public function rouletteSpins()
    {
        return $this->hasMany(RouletteSpin::class);
    }

    /**
     * Verifica se o usuário tem permissão para um determinado módulo
     *
     * @param int $permissionId ID da permissão a ser verificada
     * @return int Retorna 1 se tem permissão, 0 se não tem
     */
    public function hasPermission($permissionId)
    {
        // Buscar permissões do usuário no banco de dados
        $userPermission = \App\Models\Admin\Permissions::where('user_id', $this->id)->first();

        // Se não tem registros de permissão, retorna 0 (sem permissão)
        if (!$userPermission) {
            return 0;
        }

        // Decodifica as permissões do formato JSON
        $permissions = json_decode($userPermission->permission, true);

        // Verifica se a permissão específica existe e está ativa (1)
        if (isset($permissions[$permissionId]) && $permissions[$permissionId] == 1) {
            return 1;
        }

        return 0;
    }

    // Verifique se a tabela está definida corretamente
    protected $table = 'users'; // Este é o nome da tabela no banco de dados

    // Defina a conexão explicitamente
    protected $connection = 'mysql'; // ou o nome da conexão que contém seus usuários

    public function findForPassport($username)
    {
        // Normaliza o nome de usuário
        $username = trim(strtolower($username));

        // Verifica se é um email ou CPF
        return $this->where('email', $username)
            ->orWhere('cpf', $username)
            ->first();
    }

    /**
     * Obtém o ranking do usuário com base nos depósitos
     *
     * @return array|null
     */
    public function getRanking()
    {
        // Calcula o total de depósitos do usuário
        $totalDeposits = Transactions::where('user_id', $this->id)
            ->where('type', 0)
            ->where('status', 1)
            ->sum('amount');

        $totalDeposits = (float) $totalDeposits;

        // Busca níveis ativos no banco de dados
        $levels = VipLevel::getAllActive()->toArray();

        if (empty($levels)) {
            return null;
        }

        // Obter o nível atual com base no depósito
        $currentLevelObj = VipLevel::getCurrentLevelByDeposit($totalDeposits);

        if (!$currentLevelObj) {
            // Caso não tenha nível atual (não deve acontecer se o seeder for executado)
            $currentLevelObj = VipLevel::where('level', 1)->first();

            if (!$currentLevelObj) {
                return null;
            }
        }

        $currentLevel = $currentLevelObj->level;
        $currentLevelData = $currentLevelObj->toArray();

        // Obter o próximo nível
        $nextLevelObj = $currentLevelObj->getNextLevel();
        $nextLevel = null;
        $nextLevelData = null;

        if ($nextLevelObj) {
            $nextLevel = $nextLevelObj->level;
            $nextLevelData = $nextLevelObj->toArray();
        }

        // Calcular o progresso percentual para o próximo nível
        $progress = 0;
        if ($nextLevelData) {
            // Obter níveis ordenados para encontrar o nível atual e o anterior
            $orderedLevels = VipLevel::where('active', true)
                ->orderBy('level', 'asc')
                ->get()
                ->toArray();

            // Encontrar o índice do nível atual
            $currentIndex = array_search($currentLevel, array_column($orderedLevels, 'level'));

            // Obter o valor mínimo do nível atual e do nível anterior
            $currentLevelMinDeposit = $currentLevelData['min_deposit'];

            // Calcular quanto falta para o próximo nível
            $depositRange = $nextLevelData['min_deposit'] - $currentLevelMinDeposit;
            $currentProgress = $totalDeposits - $currentLevelMinDeposit;

            // Calcular a porcentagem de progresso
            $progress = $depositRange > 0 ? min(100, ($currentProgress / $depositRange) * 100) : 0;
        } else {
            // Se já está no nível máximo
            $progress = 100;
        }

        // Retornar array com dados de ranking
        return [
            'level' => $currentLevel,
            'name' => $currentLevelData['name'],
            'image' => $currentLevelData['image'],
            'current_deposit' => $totalDeposits,
            'next_level' => $nextLevel,
            'next_level_deposit' => $nextLevelData ? $nextLevelData['min_deposit'] : null,
            'progress' => $progress,  // Adicionando o progresso ao array
        ];
    }

    /**
     * Obtém o endereço associado ao usuário.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function address()
    {
        return $this->hasOne(UserAddress::class);
    }

    /**
     * Atualiza o nível VIP do usuário com base nos depósitos
     * e adiciona coins de recompensa se necessário
     *
     * @return array|null
     */
    public function updateVipLevelAndRewardCoins()
    {
        // Calcular total de depósitos
        $totalDeposits = Transactions::where('user_id', $this->id)
            ->where('type', 0)
            ->where('status', 1)
            ->sum('amount');

        $totalDeposits = (float) $totalDeposits;

        // Log para debug
        \Log::info("updateVipLevelAndRewardCoins - Usuário ID: {$this->id}, Total de depósitos: {$totalDeposits}");

        // Obter o nível atual com base no depósito
        $newLevelObj = VipLevel::getCurrentLevelByDeposit($totalDeposits);

        if (!$newLevelObj) {
            \Log::warning("updateVipLevelAndRewardCoins - Nenhum nível encontrado para o valor de depósito {$totalDeposits}");
            return null;
        }

        \Log::info("updateVipLevelAndRewardCoins - Nível determinado: {$newLevelObj->level}, Nome: {$newLevelObj->name}, Coins: {$newLevelObj->coins_reward}");

        // Verificar se o usuário tem um último nível registrado
        $lastKnownLevel = $this->last_vip_level ?? 0;
        \Log::info("updateVipLevelAndRewardCoins - Último nível conhecido: {$lastKnownLevel}");

        // Verificar se a carteira existe
        $wallet = $this->wallet;

        if (!$wallet) {
            \Log::warning("updateVipLevelAndRewardCoins - Wallet não encontrada para o usuário {$this->id}, criando nova");
            // Criar uma carteira se não existir
            $wallet = $this->wallet()->create([
                'user_id' => $this->id,
                'balance' => 0,
                'balance_bonus' => 0,
                'coin' => 0
            ]);
        } else {
            \Log::info("updateVipLevelAndRewardCoins - Wallet encontrada: ID {$wallet->id}, Coins atuais: {$wallet->coin}");
        }

        // Se o novo nível for maior que o último nível conhecido, o usuário subiu de nível
        if ($newLevelObj->level > $lastKnownLevel) {
            \Log::info("updateVipLevelAndRewardCoins - Usuário subiu de nível! De {$lastKnownLevel} para {$newLevelObj->level}");

            try {
                // Criar registros de recompensas pendentes para todos os níveis atingidos
                $startLevel = ($lastKnownLevel > 0) ? $lastKnownLevel + 1 : 1;

                // Registrar recompensas para cada nível entre o anterior e o atual
                for ($level = $startLevel; $level <= $newLevelObj->level; $level++) {
                    $levelObj = VipLevel::where('level', $level)->where('active', true)->first();

                    if ($levelObj) {
                        // Verificar se já existe um registro para este nível
                        $existingReward = VipReward::where('user_id', $this->id)
                            ->where('vip_level_id', $levelObj->id)
                            ->first();

                        if (!$existingReward) {
                            // Criar um novo registro de recompensa não resgatada
                            VipReward::create([
                                'user_id' => $this->id,
                                'vip_level_id' => $levelObj->id,
                                'is_claimed' => false,
                                'coins_rewarded' => $levelObj->coins_reward,
                                'balance_rewarded' => $levelObj->balance_reward,
                                'balance_bonus_rewarded' => $levelObj->balance_bonus_reward
                            ]);

                            \Log::info("updateVipLevelAndRewardCoins - Recompensa registrada para nível {$level}");
                        }
                    }
                }

                // Atualizar último nível conhecido
                $this->last_vip_level = $newLevelObj->level;
                $this->save();
                \Log::info("updateVipLevelAndRewardCoins - Nível do usuário atualizado para {$newLevelObj->level}");
            } catch (\Exception $e) {
                \Log::error("updateVipLevelAndRewardCoins - Erro ao registrar recompensas: " . $e->getMessage());
            }

            return [
                'level_up' => true,
                'old_level' => $lastKnownLevel,
                'new_level' => $newLevelObj->level,
                'coins_rewarded' => $newLevelObj->coins_reward,
                'balance_rewarded' => $newLevelObj->balance_reward,
                'balance_bonus_rewarded' => $newLevelObj->balance_bonus_reward
            ];
        } else {
            \Log::info("updateVipLevelAndRewardCoins - Usuário não subiu de nível. Permanece no nível {$newLevelObj->level}");
        }

        return [
            'level_up' => false,
            'current_level' => $newLevelObj->level
        ];
    }

    /**
     * Retorna as recompensas VIP disponíveis para resgate
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAvailableVipRewards()
    {
        return VipReward::with('vipLevel')
            ->where('user_id', $this->id)
            ->where('is_claimed', false)
            ->orderBy('vip_level_id', 'asc')
            ->get();
    }

    /**
     * Resgata uma recompensa de nível VIP específica
     *
     * @param int $rewardId ID da recompensa a ser resgatada
     * @return bool|array Retorna false em caso de erro ou array com os detalhes da recompensa resgatada
     */
    public function claimVipReward($rewardId)
    {
        $reward = VipReward::where('id', $rewardId)
            ->where('user_id', $this->id)
            ->where('is_claimed', false)
            ->first();

        if (!$reward) {
            return false;
        }

        // Verificar se há níveis inferiores não resgatados
        $lowerLevelNotClaimed = VipReward::join('vip_levels', 'vip_rewards.vip_level_id', '=', 'vip_levels.id')
            ->where('vip_rewards.user_id', $this->id)
            ->where('vip_rewards.is_claimed', false)
            ->where('vip_levels.level', '<', $reward->vipLevel->level)
            ->exists();

        if ($lowerLevelNotClaimed) {
            return [
                'success' => false,
                'message' => 'Você precisa resgatar as recompensas de níveis inferiores primeiro!'
            ];
        }

        try {
            // Adicionar as recompensas à carteira do usuário
            $wallet = $this->wallet;

            if (!$wallet) {
                return false;
            }

            // Atualizar a carteira com as recompensas
            $wallet->coin += $reward->coins_rewarded;
            $wallet->balance += $reward->balance_rewarded;
            $wallet->balance_bonus += $reward->balance_bonus_rewarded;
            $wallet->save();

            // Marcar a recompensa como resgatada
            $reward->is_claimed = true;
            $reward->claimed_at = now();
            $reward->save();

            return [
                'success' => true,
                'reward' => $reward,
                'message' => 'Parabéns! Você resgatou a recompensa do nível ' . $reward->vipLevel->name
            ];
        } catch (\Exception $e) {
            \Log::error("claimVipReward - Erro ao resgatar recompensa: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Ocorreu um erro ao processar o resgate. Tente novamente.'
            ];
        }
    }

    /**
     * Relacionamento com as recompensas VIP
     */
    public function vipRewards()
    {
        return $this->hasMany(VipReward::class);
    }
}
