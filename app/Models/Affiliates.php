<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Affiliates extends Model
{
    protected $table = 'affiliates';

    protected $fillable = [
        'user_id',
        'inviter',
        'status',
    ];

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