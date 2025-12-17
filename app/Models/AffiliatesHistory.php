<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliatesHistory extends Model
{
    protected $table = 'affiliates_history';

    protected $fillable = [
        'user_id',
        'inviter',
        'game',
        'amount'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relação para obter o nome do usuário convidado
    public function inviterUser()
    {
        return $this->belongsTo(User::class, 'inviter');
    }
}