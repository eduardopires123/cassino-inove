<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeCustomField extends Model
{
    use HasFactory;

    protected $table = 'home_custom_fields';

    protected $fillable = [
        'title',
        'is_active',
        'position',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'position' => 'integer',
    ];

    /**
     * Relacionamento com os jogos do campo personalizado
     */
    public function games()
    {
        return $this->hasMany(HomeCustomFieldGame::class, 'custom_field_id')
                    ->orderBy('position');
    }

    /**
     * Obter jogos com dados completos
     */
    public function getGamesWithDetails()
    {
        try {
            return Cache::remember("home_custom_field_games_{$this->id}", 3600, function () {
                try {
                    $games = $this->games()
                        ->join('games_api', 'home_custom_field_games.game_id', '=', 'games_api.id')
                        ->leftJoin('providers', 'games_api.provider_id', '=', 'providers.id')
                        ->where('games_api.status', 1)
                        ->select(
                            'games_api.id',
                            'games_api.name',
                            'games_api.image',
                            DB::raw('COALESCE(providers.name, "N/A") as provider_name'),
                            'home_custom_field_games.position as game_position'
                        )
                        ->orderBy('home_custom_field_games.position')
                        ->groupBy('games_api.id', 'games_api.name', 'games_api.image', 'providers.name', 'home_custom_field_games.position')
                        ->get();

                    // Se não houver jogos, retornar coleção vazia
                    if ($games->isEmpty()) {
                        return collect([]);
                    }

                    // Aplicar função completeGameImageUrl se existir
                    if (function_exists('completeGameImageUrl')) {
                        return completeGameImageUrl($games);
                    }

                    // Fallback se a função não existir
                    return $games->map(function ($game) {
                        $game->image_url = $game->image ?? ($game->image ? (strpos($game->image, 'http') === 0 ? $game->image : asset($game->image)) : null);
                        return $game;
                    });
                } catch (\Exception $e) {
                    // Log do erro mas não quebrar a aplicação
                    Log::error("Erro ao buscar jogos do custom field {$this->id}: " . $e->getMessage());
                    return collect([]);
                }
            });
        } catch (\Exception $e) {
            // Log do erro mas não quebrar a aplicação
            Log::error("Erro no cache do custom field {$this->id}: " . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * Obter todos os campos personalizados ativos ordenados
     */
    public static function getActiveFields()
    {
        try {
            return Cache::remember('home_custom_fields_active', 3600, function () {
                try {
                    return self::where('is_active', true)
                        ->orderBy('position')
                        ->get();
                } catch (\Exception $e) {
                    Log::error("Erro ao buscar custom fields ativos: " . $e->getMessage());
                    return collect([]);
                }
            });
        } catch (\Exception $e) {
            Log::error("Erro no cache de custom fields ativos: " . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * Limpar cache
     */
    public static function clearCache()
    {
        Cache::forget('home_custom_fields_active');
        $fields = self::all();
        foreach ($fields as $field) {
            Cache::forget("home_custom_field_games_{$field->id}");
        }
    }
}

