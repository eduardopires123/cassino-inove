<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyGift extends Model
{
    use HasFactory;

    /**
     * A tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'daily_gifts';

    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'gift_date',
        'gift_type',
        'gift_name',
        'gift_image',
        'claimed_at',
        'status',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'gift_date' => 'date',
        'claimed_at' => 'datetime',
    ];

    /**
     * Obtém o usuário associado a este presente diário.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Verifica se o usuário já recebeu um presente em uma data específica.
     *
     * @param int $userId
     * @param string $date
     * @return bool
     */
    public static function hasUserClaimedOnDate($userId, $date)
    {
        return self::where('user_id', $userId)
            ->whereDate('gift_date', $date)
            ->exists();
    }

    /**
     * Registra um novo presente recebido por um usuário.
     *
     * @param int $userId
     * @param string $date
     * @param string $giftType
     * @param string $giftName
     * @param string $giftImage
     * @return DailyGift
     */
    public static function registerGift($userId, $date, $giftType, $giftName, $giftImage)
    {
        return self::create([
            'user_id' => $userId,
            'gift_date' => $date,
            'gift_type' => $giftType,
            'gift_name' => $giftName,
            'gift_image' => $giftImage,
            'claimed_at' => now(),
            'status' => 'claimed',
        ]);
    }
} 