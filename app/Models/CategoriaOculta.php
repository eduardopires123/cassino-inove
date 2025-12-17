<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaOculta extends Model
{
    use HasFactory;

    /**
     * Nome da tabela associada ao modelo
     *
     * @var string
     */
    protected $table = 'categorias_ocultas';

    /**
     * Atributos que podem ser atribuídos em massa
     *
     * @var array
     */
    protected $fillable = [
        'sport_id',
        'titulo',
        'status',
    ];

    /**
     * Atributos que devem ser convertidos
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Escopo para filtrar apenas as categorias ocultas
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOcultas($query)
    {
        return $query->where('status', 'Oculto');
    }

    /**
     * Escopo para filtrar apenas as categorias visíveis
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVisiveis($query)
    {
        return $query->where('status', 'Visível');
    }
} 