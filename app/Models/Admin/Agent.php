<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    // Defina a tabela associada, se não seguir os padrões de nomenclatura
    protected $table = 'agents';

    // Defina quais campos podem ser preenchidos em massa
    protected $fillable = [
        'name',
        'email',
        'phone',
        'is_demo_agent',
        'is_admin',
        'is_affiliate',
        'banned',
        'banned_reason',
        'pix',
        'Wallet_id', // Se houver uma relação com a wallet
        // Inclua quaisquer outros campos que você tenha na tabela agents
    ];

    // Definições de relacionamento, se aplicável
    public function wallet()
    {
        return $this->hasOne(Wallet::class); // Supondo que haja uma relação um-para-um com a Wallet
    }

    // Método para acessar logs, se existir
    public function logs()
    {
        return $this->hasMany(AgentLog::class); // Supondo que você tenha um modelo AgentLog correspondente
    }

    // Outros métodos e funcionalidades do modelo podem ser definidos aqui
}

