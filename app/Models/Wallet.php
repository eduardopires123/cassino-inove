<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wallets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'balance',
        'balance_bonus',
        'balance_bonus_rollover',
        'balance_bonus_rollover_used',
        'balance_bonus_expire',
        'anti_bot',
        'anti_bot_total',
        'total_bet',
        'total_won',
        'total_lose',
        'last_won',
        'last_lose',
        'last_used',
        'hide_balance',
        'hide_balancerefer',
        'referPercent',
        'refer_rewards',
        'coin',
        'free_spins',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'balance' => 'decimal:2',
        'balance_bonus' => 'decimal:2',
        'balance_bonus_rollover' => 'decimal:2',
        'balance_bonus_rollover_used' => 'decimal:2',
        'balance_bonus_expire' => 'datetime',
        'anti_bot' => 'decimal:2',
        'anti_bot_total' => 'decimal:2',
        'total_bet' => 'decimal:2',
        'total_won' => 'decimal:2',
        'total_lose' => 'decimal:2',
        'last_won' => 'decimal:2',
        'last_lose' => 'decimal:2',
        'hide_balance' => 'integer',
        'hide_balancerefer' => 'integer',
        'referPercent' => 'integer',
        'refer_rewards' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'coin' => 'integer',
        'free_spins' => 'integer',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get the user that owns the wallet.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::updated(function ($wallet) {
            if ($wallet->isDirty('balance')) {
                $original = $wallet->getOriginal('balance');
                $new = $wallet->balance;

                \DB::table('debug')->insert(['user_id' => $wallet->user_id, 'api' => 'Alteração Saldo', 'text' => "Saldo alterado: De {$original} Para {$new}", 'created_at' => now(), 'updated_at' => now()]);
            }
        });
    }
}
