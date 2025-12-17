<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampeonatoOculto extends Model
{
    use HasFactory;
    
    /**
     * Nome da tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'campeonatos_ocultos';
    
    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'campeonato_id',
        'nome',
        'status',
        'tipo_esporte'
    ];
} 