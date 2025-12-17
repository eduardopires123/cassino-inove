<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    /**
     * A tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'games_api';

    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'image',
        'category',
        'order_value',
        'show_home',
        'destaque',
        'distribution',
        'views',
        'maintenance',
        'status'
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'order_value' => 'integer',
        'show_home' => 'boolean',
        'destaque' => 'boolean',
        'views' => 'integer',
        'maintenance' => 'boolean',
        'status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];



    /**
     * Escopo de consulta para obter jogos ativos.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Escopo de consulta para obter jogos destacados.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFeatured($query)
    {
        return $query->where('destaque', 1);
    }

    /**
     * Escopo de consulta para obter jogos por categoria.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $category
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Escopo de consulta para obter jogos por provedor.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $provider
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByProvider($query, $provider)
    {
        return $query->join('api_games_slugs', 'games_api.id', '=', 'api_games_slugs.id_game')
                    ->join('providers', 'api_games_slugs.provider_id', '=', 'providers.id')
                    ->where('providers.name', $provider)
                    ->where('api_games_slugs.active', 1)
                    ->select('games_api.*')
                    ->distinct();
    }

    /**
     * Escopo de consulta para pesquisar jogos por nome.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }

    /**
     * Obter jogos similares a este jogo.
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSimilarGames($limit = 6)
    {
        return self::where('category', $this->category)
            ->where('id', '!=', $this->id)
            ->active()
            ->orderBy('views', 'desc')
            ->limit($limit)
            ->get();
    }
}