<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TournamentPlayer extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'user_id',
        'points',
        'position',
        'is_disqualified',
        'disqualification_reason',
        'joined_at',
        'last_active_at',
        'last_points_update',
        'points_calculation_method',
        'is_random_player'
    ];

    protected $casts = [
        'points' => 'decimal:2',
        'is_disqualified' => 'boolean',
        'joined_at' => 'datetime',
        'last_active_at' => 'datetime',
        'last_points_update' => 'datetime',
        'is_random_player' => 'boolean',
    ];

    /**
     * Get the tournament that the player belongs to.
     */
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * Get the user that represents this tournament player.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Update player points.
     *
     * @param float $points
     * @return bool
     */
    public function addPoints($points)
    {
        $this->points += $points;
        $this->last_active_at = now();
        return $this->save();
    }

    /**
     * Update player position in the tournament.
     *
     * @param int $position
     * @return bool
     */
    public function updatePosition($position)
    {
        $this->position = $position;
        return $this->save();
    }

    /**
     * Disqualify a player.
     *
     * @param string $reason
     * @return bool
     */
    public function disqualify($reason = null)
    {
        $this->is_disqualified = true;
        $this->disqualification_reason = $reason;
        return $this->save();
    }

    /**
     * Format the player's name with the ** pattern
     */
    public function getFormattedNameAttribute()
    {
        return Tournament::formatPlayerName($this->user->name ?? Tournament::getRandomPersonName());
    }
    
    /**
     * Get the player's avatar
     */
    public function getAvatarAttribute()
    {
        // Para usuários aleatórios (is_random_player = true)
        if ($this->is_random_player) {
            // Use a deterministic avatar based on user_id for consistency
            return $this->getRandomAvatar($this->user_id);
        }
        
        // Para usuários reais, obter a imagem do perfil
        if ($this->user) {
            // Verificar se o usuário tem uma imagem definida
            if (!empty($this->user->image)) {
                // Usar a imagem do usuário
                return asset($this->user->image);
            } else {
                // Se não tiver imagem, usar avatar padrão do sistema
                return asset('img/default-avatar.png');
            }
        }
        
        // Fallback - apenas se for um usuário real sem avatar definido
        return asset('img/default-avatar.png');
    }
    
    /**
     * Get a random avatar from the avatar folder
     * 
     * @param int $seed Optional seed for deterministic selection
     * @return string
     */
    private function getRandomAvatar($seed = null)
    {
        // Lista de avatares disponíveis
        $avatarFiles = glob(public_path('img/avatar/*.png'));
        
        if (empty($avatarFiles)) {
            // Avatar padrão caso não encontre nenhum
            return asset('img/default-avatar.png');
        }
        
        // Se um seed foi fornecido, use-o para selecionar um avatar de forma determinística
        if ($seed !== null) {
            $index = $seed % count($avatarFiles);
            $randomAvatar = $avatarFiles[$index];
        } else {
            // Caso contrário, selecione aleatoriamente
            $randomAvatar = $avatarFiles[array_rand($avatarFiles)];
        }
        
        // Retornar caminho relativo
        return asset('img/avatar/' . basename($randomAvatar));
    }
}
