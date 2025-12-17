<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class VerificationToken extends Model
{
    use HasFactory;

    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'token',
        'type',
        'expires_at',
        'verified_at',
    ]; 

    /**
     * Os atributos que devem ser convertidos.
     *
     * @var array
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    /**
     * Relacionamento com o usuário.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Verifica se o token expirou.
     *
     * @return bool
     */
    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    /**
     * Verifica se o token foi verificado.
     *
     * @return bool
     */
    public function isVerified()
    {
        return $this->verified_at !== null;
    }

    /**
     * Marca o token como verificado.
     *
     * @return bool
     */
    public function markAsVerified()
    {
        return $this->update(['verified_at' => now()]);
    }

    /**
     * Cria um novo token para um usuário.
     *
     * @param  User  $user
     * @param  string  $type
     * @param  int  $expiresInHours
     * @return static
     */
    public static function createToken(User $user, string $type = 'email', int $expiresInHours = 24)
    {
        // Inativar tokens anteriores do mesmo tipo
        self::where('user_id', $user->id)
            ->where('type', $type)
            ->where('verified_at', null)
            ->delete();

        // Criar novo token
        return self::create([
            'user_id' => $user->id,
            'token' => Str::random(64),
            'type' => $type,
            'expires_at' => Carbon::now()->addHours($expiresInHours),
        ]);
    }

    /**
     * Encontra um token válido.
     *
     * @param  int  $userId
     * @param  string  $token
     * @param  string  $type
     * @return static|null
     */
    public static function findValidToken(int $userId, string $token, string $type = 'email')
    {
        return self::where('user_id', $userId)
            ->where('token', $token)
            ->where('type', $type)
            ->where('verified_at', null)
            ->where('expires_at', '>', now())
            ->first();
    }
}
