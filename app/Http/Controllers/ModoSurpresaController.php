<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\GamesApi;

class ModoSurpresaController extends Controller
{
    /**
     * Sorteia um jogo aleatório para o Modo Surpresa
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sortearJogo(Request $request)
    {
        try {
            // Buscar apenas jogos de slots com mais visualizações (top 100 para ter variedade)
            $jogos = Cache::remember('modo_surpresa_jogos_cache', now()->addHours(6), function () {
                return DB::table('games_api')
                    ->leftJoin('providers', 'games_api.provider_id', '=', 'providers.id')
                    ->where('games_api.status', 1)
                    ->where(function($query) {
                        $query->whereRaw('LOWER(games_api.category) = ?', ['slots'])
                              ->orWhereRaw('LOWER(games_api.category) = ?', ['slot']);
                    })
                    ->whereNotNull('games_api.slug')
                    ->whereNotNull('games_api.provider_id')
                    ->select('games_api.id', 'games_api.name', 'games_api.image', 'games_api.slug', 'providers.name as provider_name', 'games_api.views', 'games_api.category')
                    ->orderBy('games_api.views', 'desc')
                    ->limit(100)
                    ->get();
            });

            // Se não houver jogos slots com views, buscar jogos slots aleatórios
            if ($jogos->isEmpty()) {
                $jogos = Cache::remember('modo_surpresa_jogos_random_cache', now()->addHours(6), function () {
                    return DB::table('games_api')
                        ->leftJoin('providers', 'games_api.provider_id', '=', 'providers.id')
                        ->where('games_api.status', 1)
                        ->where(function($query) {
                            $query->whereRaw('LOWER(games_api.category) = ?', ['slots'])
                                  ->orWhereRaw('LOWER(games_api.category) = ?', ['slot']);
                        })
                        ->whereNotNull('games_api.slug')
                        ->whereNotNull('games_api.provider_id')
                        ->select('games_api.id', 'games_api.name', 'games_api.image', 'games_api.slug', 'providers.name as provider_name', 'games_api.views', 'games_api.category')
                        ->inRandomOrder()
                        ->limit(50)
                        ->get();
                });
            }

            // Verificar se existem jogos disponíveis
            if ($jogos->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhum jogo de slots disponível no momento.'
                ], 404);
            }

            // Sortear um jogo aleatório da lista
            $jogoSorteado = $jogos->random();

            // Completar a URL da imagem usando a função helper global
            $jogoSorteado = $this->completeGameImageUrl($jogoSorteado);

            // Gerar URL do jogo usando apenas o ID
            $gameUrl = '/games/' . $jogoSorteado->id;

            return response()->json([
                'success' => true,
                'jogo' => [
                    'id' => $jogoSorteado->id,
                    'name' => $jogoSorteado->name,
                    'image' => $jogoSorteado->image,
                    'image_url' => $jogoSorteado->image_url,
                    'provider' => $jogoSorteado->provider_name,
                    'url' => $gameUrl,
                    'views' => $jogoSorteado->views ?? 0,
                    'category' => $jogoSorteado->category
                ],
                'total_jogos' => $jogos->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obter lista de jogos slots para popular a roleta (carregamento otimizado)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function obterJogosRoleta(Request $request)
    {
        try {
            $limite = $request->input('limite', 20);
            $inicial = $request->input('inicial', false); // Parâmetro para carregamento inicial
            $nocache = $request->input('nocache', false); // Parâmetro para ignorar cache

            // Se for carregamento inicial, retornar apenas 6 jogos
            if ($inicial) {
                $cacheKey = 'modo_surpresa_inicial_cache';
                
                // Limpar cache se solicitado
                if ($nocache) {
                    Cache::forget($cacheKey);
                }
                
                $jogos = Cache::remember($cacheKey, now()->addHours(6), function () {
                    // Buscar apenas 6 jogos de slots com mais visualizações para carregamento inicial
                    // Aceitar variações: slots, Slots, SLOTS, etc.
                    $jogosViews = DB::table('games_api')
                        ->leftJoin('providers', 'games_api.provider_id', '=', 'providers.id')
                        ->where('games_api.status', 1)
                        ->where(function($query) {
                            $query->whereRaw('LOWER(games_api.category) = ?', ['slots'])
                                  ->orWhereRaw('LOWER(games_api.category) = ?', ['slot']);
                        })
                        ->whereNotNull('games_api.slug')
                        ->whereNotNull('games_api.provider_id')
                        ->select('games_api.id', 'games_api.name', 'games_api.image', 'providers.name as provider_name', 'games_api.views', 'games_api.category')
                        ->orderBy('games_api.views', 'desc')
                        ->limit(6)
                        ->get();

                    // Se não houver jogos slots suficientes com views, completar com jogos slots aleatórios
                    if ($jogosViews->count() < 6) {
                        $jogosIds = $jogosViews->pluck('id')->toArray();
                        $jogosAdicionais = DB::table('games_api')
                            ->leftJoin('providers', 'games_api.provider_id', '=', 'providers.id')
                            ->where('games_api.status', 1)
                            ->where(function($query) {
                                $query->whereRaw('LOWER(games_api.category) = ?', ['slots'])
                                      ->orWhereRaw('LOWER(games_api.category) = ?', ['slot']);
                            })
                            ->whereNotNull('games_api.slug')
                            ->whereNotNull('games_api.provider_id')
                            ->whereNotIn('games_api.id', $jogosIds)
                            ->select('games_api.id', 'games_api.name', 'games_api.image', 'providers.name as provider_name', 'games_api.views', 'games_api.category')
                            ->inRandomOrder()
                            ->limit(6 - $jogosViews->count())
                            ->get();
                        
                        return $jogosViews->merge($jogosAdicionais);
                    }

                    return $jogosViews;
                });
            } else {
                // Carregamento completo com cache
                $cacheKey = 'modo_surpresa_cache_' . $limite;
                
                // Limpar cache se solicitado
                if ($nocache) {
                    Cache::forget($cacheKey);
                }
                
                $jogos = Cache::remember($cacheKey, now()->addHours(6), function () use ($limite) {
                    // Primeiro buscar jogos com mais visualizações (slots)
                    $jogosViews = DB::table('games_api')
                        ->leftJoin('providers', 'games_api.provider_id', '=', 'providers.id')
                        ->where('games_api.status', 1)
                        ->where(function($query) {
                            $query->whereRaw('LOWER(games_api.category) = ?', ['slots'])
                                  ->orWhereRaw('LOWER(games_api.category) = ?', ['slot']);
                        })
                        ->whereNotNull('games_api.slug')
                        ->whereNotNull('games_api.provider_id')
                        ->select('games_api.id', 'games_api.name', 'games_api.image', 'providers.name as provider_name', 'games_api.views', 'games_api.category')
                        ->orderBy('games_api.views', 'desc')
                        ->limit($limite / 2)
                        ->get();

                    // Depois buscar jogos aleatórios
                    $jogosIds = $jogosViews->pluck('id')->toArray();
                    $jogosRandom = DB::table('games_api')
                        ->leftJoin('providers', 'games_api.provider_id', '=', 'providers.id')
                        ->where('games_api.status', 1)
                        ->where(function($query) {
                            $query->whereRaw('LOWER(games_api.category) = ?', ['slots'])
                                  ->orWhereRaw('LOWER(games_api.category) = ?', ['slot']);
                        })
                        ->whereNotNull('games_api.slug')
                        ->whereNotNull('games_api.provider_id')
                        ->whereNotIn('games_api.id', $jogosIds)
                        ->select('games_api.id', 'games_api.name', 'games_api.image', 'providers.name as provider_name', 'games_api.views', 'games_api.category')
                        ->inRandomOrder()
                        ->limit($limite - $jogosViews->count())
                        ->get();
                    
                    return $jogosViews->merge($jogosRandom)->shuffle();
                });
            }

            // Se não houver jogos de slots, buscar qualquer jogo ativo
            if ($jogos->isEmpty()) {
                \Log::warning('Modo Surpresa: Nenhum jogo de slots encontrado, buscando qualquer jogo ativo', [
                    'limite' => $limite,
                    'inicial' => $inicial
                ]);
                
                // Fallback: buscar qualquer jogo ativo
                $jogos = DB::table('games_api')
                    ->leftJoin('providers', 'games_api.provider_id', '=', 'providers.id')
                    ->where('games_api.status', 1)
                    ->whereNotNull('games_api.slug')
                    ->whereNotNull('games_api.provider_id')
                    ->select('games_api.id', 'games_api.name', 'games_api.image', 'providers.name as provider_name', 'games_api.views', 'games_api.category')
                    ->orderBy('games_api.views', 'desc')
                    ->limit($limite)
                    ->get();
                
                if ($jogos->isEmpty()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Nenhum jogo disponível no momento.'
                    ], 404);
                }
            }

            // Processar os jogos para garantir que tenham as URLs corretas das imagens
            $jogos = $jogos->map(function($jogo) {
                // Garantir que provider esteja disponível para o frontend
                $jogo->provider = $jogo->provider_name;
                
                // Usar completeGameImageUrl para padronizar URLs de imagem
                return completeGameImageUrl($jogo);
            });

            \Log::info('Modo Surpresa: Jogos retornados', [
                'total' => $jogos->count(),
                'limite' => $limite
            ]);

            return response()->json([
                'success' => true,
                'jogos' => $jogos,
                'total' => $jogos->count()
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro no modo surpresa:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar jogos de slots: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Completa a URL da imagem de um jogo
     *
     * @param $game
     * @return mixed
     */
    private function completeGameImageUrl($game)
    {
        if (isset($game->image)) {
            // Verifica se a imagem já é uma URL completa
            if (!preg_match('/^https?:\/\//i', $game->image)) {
                // Se não for uma URL completa, adiciona a URL base
                $game->image_url = url('/storage/' . $game->image);
            } else {
                $game->image_url = $game->image;
            }
        } else {
            $game->image_url = '';
        }

        return $game;
    }

    /**
     * Completa URLs das imagens para uma coleção de jogos
     *
     * @param \Illuminate\Support\Collection $games
     * @return \Illuminate\Support\Collection
     */
    private function completeGameImageUrls($games)
    {
        return $games->map(function ($game) {
            return $this->completeGameImageUrl($game);
        });
    }
} 