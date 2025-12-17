<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VipReward extends Model
{
    protected $fillable = [
        'user_id',
        'vip_level_id',
        'is_claimed',
        'claimed_at',
        'coins_rewarded',
        'balance_rewarded',
        'balance_bonus_rewarded',
        'free_spins_rewarded'
    ];

    protected $casts = [
        'is_claimed' => 'boolean',
        'claimed_at' => 'datetime',
        'coins_rewarded' => 'integer',
        'balance_rewarded' => 'decimal:2',
        'balance_bonus_rewarded' => 'decimal:2',
        'free_spins_rewarded' => 'integer'
    ];

    /**
     * Relacionamento com o usuário
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com o nível VIP
     */
    public function vipLevel()
    {
        return $this->belongsTo(VipLevel::class);
    }
}
