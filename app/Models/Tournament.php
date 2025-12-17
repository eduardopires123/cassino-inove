<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Tournament extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'total_prize',
        'start_date',
        'end_date',
        'banner_image',
        'description',
        'max_players',
        'status',
        'is_featured',
        'use_random_players',
        'random_players_count',
        'prize_1st',
        'prize_2nd',
        'prize_3rd',
        'prize_4th',
        'prize_5th',
        'prize_6th',
        'prize_7th',
        'prize_8th',
        'prize_9th',
        'prize_10th',
        'prize_11th',
        'qualified_games',
        'min_bet_amount',
        'points_calculation_type',
        'points_multiplier'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'total_prize' => 'decimal:2',
        'is_featured' => 'boolean',
        'use_random_players' => 'boolean',
        'prize_1st' => 'decimal:2',
        'prize_2nd' => 'decimal:2',
        'prize_3rd' => 'decimal:2',
        'prize_4th' => 'decimal:2',
        'prize_5th' => 'decimal:2',
        'prize_6th' => 'decimal:2',
        'prize_7th' => 'decimal:2',
        'prize_8th' => 'decimal:2',
        'prize_9th' => 'decimal:2',
        'prize_10th' => 'decimal:2',
        'prize_11th' => 'decimal:2',
        'min_bet_amount' => 'decimal:2',
    ];

    /**
     * Get the players for the tournament.
     */
    public function players()
    {
        return $this->hasMany(TournamentPlayer::class);
    }

    /**
     * Get the top players for the tournament.
     */
    public function topPlayers($limit = 5)
    {
        return $this->players()
            ->with('user')
            ->where('points', '>', 0)
            ->where('is_random_player', false)
            ->orderBy('points', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Check if the tournament is active.
     */
    public function isActive()
    {
        return $this->status === 'active' &&
               $this->start_date <= now() &&
               $this->end_date > now();
    }

    /**
     * Check if the tournament has ended.
     */
    public function hasEnded()
    {
        return $this->end_date <= now();
    }

    /**
     * Get the remaining time until the tournament ends.
     */
    public function getRemainingTime()
    {
        if ($this->hasEnded()) {
            return null;
        }

        return $this->end_date->diff(now());
    }

    /**
     * Get the remaining days, hours, minutes until the tournament ends.
     */
    public function getRemainingTimeFormatted()
    {
        if ($this->hasEnded()) {
            return ['days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 0];
        }

        $diff = $this->end_date->diff(now());
        
        return [
            'days' => $diff->d,
            'hours' => $diff->h,
            'minutes' => $diff->i,
            'seconds' => $diff->s
        ];
    }

    /**
     * Get active tournaments.
     */
    public static function getActive()
    {
        return self::where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>', now())
            ->orderBy('end_date', 'asc')
            ->get();
    }

    /**
     * Get player count for the tournament.
     */
    public function getPlayerCount()
    {
        return $this->players()->where('is_random_player', false)->count();
    }

    /**
     * Check if user is participating in the tournament.
     */
    public function isUserParticipating($userId)
    {
        return $this->players()->where('user_id', $userId)->exists();
    }

    /**
     * Join a user to the tournament.
     */
    public function joinUser($userId)
    {
        if ($this->isUserParticipating($userId)) {
            return false;
        }

        $this->players()->create([
            'user_id' => $userId,
            'joined_at' => now(),
            'last_active_at' => now()
        ]);

        return true;
    }

    /**
     * Format player name with ** pattern
     */
    public static function formatPlayerName($name)
    {
        if (empty($name) || $name === 'User') {
            return self::getRandomPersonName() . "*****";
        }
        
        if (strlen($name) <= 4) {
            return $name . "*****";
        }
        
        return substr($name, 0, 4) . "*****";
    }
    
    /**
     * Gerar um nome aleatório brasileiro
     * 
     * @return string
     */
    public static function getRandomPersonName()
    {
        $firstNames = ['João', 'Maria', 'Pedro', 'Ana', 'Lucas', 'Julia', 'Carlos', 'Mariana', 
                      'Rafael', 'Fernanda', 'Victor', 'Larissa', 'Guilherme', 'Camila', 'Diego',
                      'Bruno', 'Amanda', 'Gabriel', 'Juliana', 'Mateus', 'Beatriz', 'Leonardo',
                      'Thiago', 'Daniela', 'André', 'Bianca', 'Eduardo', 'Letícia', 'Felipe'];
        
        // Retornar um nome aleatório da lista
        return $firstNames[array_rand($firstNames)];
    }
    
    /**
     * Ensure tournament has random players if needed
     * 
     * @param int $minPlayers Minimum number of players to ensure
     * @return void
     */
    public function ensureRandomPlayers($minPlayers = null)
    {
        // Se o uso de jogadores aleatórios não estiver ativado, retorne
        if (!$this->use_random_players) {
            return;
        }
        
        // Use o número configurado de jogadores aleatórios ou o padrão
        $minPlayers = $minPlayers ?? $this->random_players_count ?? 10;
        
        // Se já temos jogadores suficientes, não precisamos adicionar mais
        if ($this->players()->count() >= $minPlayers) {
            return;
        }
        
        // Quantos jogadores precisamos adicionar
        $playersToAdd = $minPlayers - $this->players()->count();
        
        // Gerar jogadores aleatórios
        for ($i = 0; $i < $playersToAdd; $i++) {
            $this->addRandomPlayer();
        }
    }
    
    /**
     * Add a random player to the tournament
     */
    public function addRandomPlayer()
    {
        // Gera um ID de usuário fictício (maior que o maior ID de usuário real)
        $maxUserId = User::max('id') ?? 0;
        $fakeUserId = $maxUserId + rand(1000, 9999);
        
        // Cria um novo jogador com pontuação aleatória
        return $this->players()->create([
            'user_id' => $fakeUserId,
            'points' => rand(10, 1000) / 10, // Pontos aleatórios entre 1 e 100 com 1 casa decimal
            'joined_at' => Carbon::now()->subHours(rand(1, 24)),
            'last_active_at' => Carbon::now()->subMinutes(rand(1, 60)),
            'is_random_player' => true
        ]);
    }
    
    /**
     * Obter um avatar aleatório da pasta de avatares
     * 
     * @return string
     */
    public static function getRandomAvatar()
    {
        // Lista de avatares disponíveis
        $avatarFiles = glob(public_path('img/avatar/*.png'));
        
        if (empty($avatarFiles)) {
            // Avatar padrão caso não encontre nenhum
            return asset('img/default-avatar.png');
        }
        
        // Selecionar um avatar aleatório
        $randomAvatar = $avatarFiles[array_rand($avatarFiles)];
        
        // Retornar caminho relativo
        return asset('img/avatar/' . basename($randomAvatar));
    }
    
    /**
     * Auto-inscrever novo usuário em torneios ativos
     * 
     * @param int $userId
     * @return void
     */
    public static function autoEnrollUserInActiveTournaments($userId)
    {
        $activeTournaments = self::where('status', 'active')
            ->where('end_date', '>', now())
            ->get();
            
        foreach ($activeTournaments as $tournament) {
            if (!$tournament->isUserParticipating($userId)) {
                $tournament->joinUser($userId);
            }
        }
    }

    /**
     * Calcula os pontos dos usuários com base nas apostas
     */
    public function calculateBetPoints()
    {
        // Se o torneio não estiver ativo, não calcular
        if (!$this->isActive()) {
            return false;
        }
        
        // Preparar a lista de jogos qualificados, se houver
        $qualifiedGames = null;
        $qualifiedGameIds = [];
        $qualifiedGameSlugs = [];
        
        if (!empty($this->qualified_games)) {
            $qualifiedGameIds = explode(',', $this->qualified_games);
            
            // Buscar todos os slugs dos jogos qualificados na tabela consolidada games_api
            $gamesInfo = DB::table('games_api')
                ->whereIn('id', $qualifiedGameIds)
                ->where('status', 1)
                ->get(['slug', 'id']);
                
            foreach ($gamesInfo as $gameInfo) {
                // Adicionar o slug à lista de jogos qualificados
                if (!empty($gameInfo->slug)) {
                    $qualifiedGameSlugs[] = $gameInfo->slug;
                }
                
                // Também adicionar o ID do jogo como possibilidade
                $qualifiedGameSlugs[] = (string)$gameInfo->id;
            }
            
            // Remover duplicatas
            $qualifiedGameSlugs = array_unique($qualifiedGameSlugs);
        }
        
        // Definir valor mínimo de aposta
        $minBetAmount = $this->min_bet_amount ?? 0.40;
        
        // Definir multiplicador de pontos (padrão: 100 = cada R$0,01 vale 1 ponto)
        $pointsMultiplier = $this->points_multiplier ?? 100;
        
        // Primeiro, procurar por usuários com apostas que ainda não têm registro como jogador
        // Isso garante que todos os usuários que apostaram estejam no ranking
        if (!empty($qualifiedGameSlugs)) {
            // Buscar todos os usuários com apostas nos jogos qualificados
            $userQuery = DB::table('games_history')
                ->select('user_id')
                ->distinct()
                ->where('amount', '>=', $minBetAmount)
                ->where('action', 'loss')
                ->where('created_at', '>=', $this->start_date)
                ->where('created_at', '<=', now());
                
            // Adicionar filtro por jogos qualificados
            if (!empty($qualifiedGameSlugs)) {
                $userQuery->whereIn('game', $qualifiedGameSlugs);
            }
            
            // Obter IDs de usuários com apostas
            $userIds = $userQuery->pluck('user_id')->toArray();
            
            // Obter IDs de usuários que já são jogadores deste torneio
            $existingPlayerUserIds = $this->players()
                ->where('is_random_player', false)
                ->pluck('user_id')
                ->toArray();
            
            // Determinar quais usuários têm apostas mas não são jogadores
            $newUserIds = array_diff($userIds, $existingPlayerUserIds);
            
            // Criar registros de jogadores para estes novos usuários
            foreach ($newUserIds as $userId) {
                // Verificar se o usuário existe
                $userExists = DB::table('users')->where('id', $userId)->exists();
                
                if ($userExists) {
                    // Criar novo registro de jogador
                    $this->players()->create([
                        'user_id' => $userId,
                        'points' => 0, // Pontos serão calculados abaixo
                        'joined_at' => now(),
                        'last_active_at' => now(),
                        'is_random_player' => false
                    ]);
                }
            }
        }
        
        // Agora, buscar todos os jogadores do torneio e calcular pontos
        $players = $this->players()->get();
        
        // Para cada jogador
        foreach ($players as $player) {
            // Pular jogadores aleatórios se não queremos calcular para eles
            if ($player->is_random_player) {
                continue;
            }
            
            // Iniciar uma consulta para buscar apostas do jogador
            $query = \App\Models\GameHistory::where('user_id', $player->user_id)
                ->where('amount', '>=', $minBetAmount) // Considerar apenas apostas acima do valor mínimo
                ->where('action', 'loss') // 'loss' normalmente é a ação de apostas
                ->where('created_at', '>=', $this->start_date) // Considerar apenas apostas após o início do torneio
                ->where('created_at', '<=', now());
            
            // Filtrar por jogos qualificados, se definidos
            if (!empty($qualifiedGameSlugs)) {
                $query->whereIn('game', $qualifiedGameSlugs);
            }
            
            // Calcular pontos conforme o tipo de cálculo
            $points = 0;
            switch ($this->points_calculation_type ?? 'bet_amount') {
                case 'bet_amount':
                    // Valor apostado (ex: cada R$0,01 = 1 ponto)
                    $totalAmount = $query->sum('amount');
                    $points = $totalAmount * $pointsMultiplier;
                    break;
                    
                case 'win_amount':
                    // Valor ganho
                    $winQuery = clone $query;
                    $winQuery->where('action', 'win');
                    $totalWinAmount = $winQuery->sum('amount');
                    $points = $totalWinAmount * $pointsMultiplier;
                    break;
                    
                case 'bet_count':
                    // Número de apostas
                    $betCount = $query->count();
                    $points = $betCount * $pointsMultiplier;
                    break;
                    
                default:
                    $totalAmount = $query->sum('amount');
                    $points = $totalAmount * 100; // Padrão: R$0,01 = 1 ponto
            }
            
            // Atualizar os pontos do jogador
            $player->points = $points;
            $player->points_calculation_method = $this->points_calculation_type ?? 'bet_amount';
            $player->last_points_update = now();
            $player->save();
        }
        
        return true;
    }
    
    /**
     * Get the prize for a specific position
     * 
     * @param int $position The position (1-11)
     * @return float|null The prize amount
     */
    public function getPrizeForPosition($position)
    {
        if ($position < 1 || $position > 11) {
            return null;
        }
        
        $field = 'prize_' . $this->getOrdinalSuffix($position);
        return $this->$field;
    }
    
    /**
     * Get the ordinal suffix for a number (1st, 2nd, 3rd, etc.)
     * 
     * @param int $number The position number
     * @return string The ordinal suffix
     */
    private function getOrdinalSuffix($number)
    {
        $suffixes = [
            1 => '1st',
            2 => '2nd',
            3 => '3rd',
            4 => '4th',
            5 => '5th',
            6 => '6th',
            7 => '7th',
            8 => '8th',
            9 => '9th',
            10 => '10th',
            11 => '11th'
        ];
        
        return $suffixes[$number] ?? $number . 'th';
    }
    
    /**
     * Calculate and get the prize distribution array
     * 
     * @return array The prize distribution
     */
    public function getPrizeDistribution()
    {
        $distribution = [];
        
        for ($i = 1; $i <= 11; $i++) {
            $prize = $this->getPrizeForPosition($i);
            if ($prize !== null && $prize > 0) {
                $distribution[$i] = $prize;
            }
        }
        
        return $distribution;
    }
}
