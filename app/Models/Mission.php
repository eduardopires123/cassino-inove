<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    use HasFactory;

    protected $table = 'missions';
    
    protected $fillable = [
        'id',
        'title',
        'description',
        'image',
        'game_id',
        'target_amount',
        'reward_koins',
        'reward_balance',
        'reward_balance_bonus',
        'reward_free_spins',
        'status',
        'target_ranking_level',
        'ranking_type',
    ];

    /**
     * Relação com o jogo (na tabela games_api)
     */
    public function game()
    {
        return $this->belongsTo(\App\Models\GamesApi::class, 'game_id', 'id');
    }

    /**
     * Relação com o nível VIP (ranking)
     */
    public function vipLevel()
    {
        return $this->belongsTo(VipLevel::class, 'target_ranking_level', 'level');
    }

    // Relação com os usuários que completaram a missão
    public function completions()
    {
        return $this->hasMany(MissionCompletion::class);
    }

    /**
     * Verifica se a missão está disponível para o usuário baseado no ranking
     */
    public function isAvailableForUser($user)
    {
        // Se não há filtro de ranking, está disponível para todos
        if ($this->ranking_type === 'all' || !$this->target_ranking_level) {
            return true;
        }

        // Obter o ranking do usuário
        $userRanking = $user->getRanking();
        $userLevel = $userRanking ? $userRanking['level'] : 1;

        switch ($this->ranking_type) {
            case 'specific':
                // Nível específico - deve ser exatamente o nível
                return $userLevel == $this->target_ranking_level;
            
            case 'minimum':
                // Nível mínimo - deve ser igual ou superior
                return $userLevel >= $this->target_ranking_level;
            
            default:
                return true;
        }
    }

    /**
     * Verifica se a missão está disponível para um nível de ranking específico
     */
    public function isAvailableForRanking($rankingLevel)
    {
        // Se não há filtro de ranking, está disponível para todos
        if ($this->ranking_type === 'all' || !$this->target_ranking_level) {
            return true;
        }

        switch ($this->ranking_type) {
            case 'specific':
                // Nível específico - deve ser exatamente o nível
                return $rankingLevel == $this->target_ranking_level;
            
            case 'minimum':
                // Nível mínimo - deve ser igual ou superior
                return $rankingLevel >= $this->target_ranking_level;
            
            default:
                return true;
        }
    }

    /**
     * Retorna o texto descritivo do requisito de ranking
     */
    public function getRankingRequirementText()
    {
        if ($this->ranking_type === 'all' || !$this->target_ranking_level) {
            return 'Todos os Rankings';
        }

        // Buscar o nome do ranking dinamicamente
        $vipLevel = VipLevel::where('level', $this->target_ranking_level)->first();
        $rankName = $vipLevel ? $vipLevel->name : "Nível {$this->target_ranking_level}";

        switch ($this->ranking_type) {
            case 'specific':
                return "Apenas {$rankName}";
            case 'minimum':
                return "{$rankName}+";
            default:
                return 'Todos os Rankings';
        }
    }
} 