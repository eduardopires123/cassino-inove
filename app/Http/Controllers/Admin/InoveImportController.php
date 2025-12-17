<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GamesApi;
use App\Models\Admin\Providers;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InoveImportController extends Controller
{
    private $apiUrl = 'https://api.inoveigaming.com/games/list';
    private $providersApiUrl = 'https://api.inoveigaming.com/games/providers';
    private $distribution = 'Inove';

    public function index()
    {
        return view('admin.import.inove');
    }

    /**
     * Proxy para carregar imagens externas e evitar problemas de CORS
     */
    public function proxyImage(Request $request)
    {
        $imageUrl = $request->query('url');
        
        if (!$imageUrl) {
            return response('URL não fornecida', 400);
        }

        try {
            // Fazer requisição para a imagem externa
            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'Referer' => 'https://demo.inoveigaming.com/',
                ])
                ->get($imageUrl);

            if (!$response->successful()) {
                return response('Erro ao carregar imagem', $response->status());
            }

            // Determinar o tipo de conteúdo
            $contentType = $response->header('Content-Type') ?: 'image/webp';

            // Retornar a imagem com headers CORS corretos
            return response($response->body())
                ->header('Content-Type', $contentType)
                ->header('Cache-Control', 'public, max-age=86400') // Cache de 24 horas
                ->header('Access-Control-Allow-Origin', '*');

        } catch (\Exception $e) {
            Log::error('Erro no proxy de imagem: ' . $e->getMessage());
            return response('Erro ao processar imagem', 500);
        }
    }

    public function getGames()
    {
        try {
            $response = Http::timeout(30)->get($this->apiUrl);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao conectar com a API da Inove: ' . $response->status()
                ]);
            }

            $games = $response->json();

            if (empty($games)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhum jogo encontrado na API da Inove'
                ]);
            }

            return response()->json([
                'success' => true,
                'games' => $games
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao buscar jogos da Inove: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ]);
        }
    }

    public function getExistingGames()
    {
        try {
            // Buscar jogos que têm distribution Inove diretamente na tabela games_api
            $existingGames = GamesApi::with('provider')
                ->where('distribution', $this->distribution)
                ->select('id', 'name', 'distribution', 'image', 'slug', 'provider_id')
                ->get()
                ->map(function($game) {
                    $providerName = $game->provider?->name ?? 'Desconhecido';
                    
                    return [
                        'id' => $game->id,
                        'name' => $game->name,
                        'provider_name' => $providerName,
                        'distribution' => $game->distribution,
                        'image' => $game->image,
                        'slug' => $game->slug // Campo consolidado - cada jogo tem apenas um slug
                    ];
                });

            return response()->json([
                'success' => true,
                'existingGames' => $existingGames
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao buscar jogos existentes da Inove: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar jogos existentes: ' . $e->getMessage()
            ]);
        }
    }

    public function importGames(Request $request)
    {
        // Aumentar limite de memória e tempo de execução
        ini_set('memory_limit', '1024M');
        set_time_limit(600);

        $request->validate([
            'games' => 'required|array',
            'games.*' => 'required|string'
        ]);

        $gameIds = $request->games;
        $processedGames = [];
        $importedCount = 0;
        $updatedCount = 0;
        $errorCount = 0;

        Log::info('Iniciando importação de jogos da Inove', ['count' => count($gameIds)]);

        try {
            $response = Http::timeout(30)->get($this->apiUrl);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao conectar com a API da Inove: ' . $response->status()
                ]);
            }

            $apiGames = $response->json();
            $gamesById = collect($apiGames)->keyBy('id');

            foreach ($gameIds as $gameId) {
                try {
                    $gameData = $gamesById->get($gameId);

                    if (!$gameData) {
                        $processedGames[$gameId] = [
                            'status' => 'error',
                            'message' => 'Jogo não encontrado na API'
                        ];
                        $errorCount++;
                        continue;
                    }

                    $result = $this->processGame($gameData);
                    $processedGames[$gameId] = $result;

                    if ($result['status'] === 'imported') {
                        $importedCount++;
                    } elseif ($result['status'] === 'updated') {
                        $updatedCount++;
                    } elseif ($result['status'] === 'already_exists') {
                        // Jogo já existe com este slug - não conta como erro nem como atualização
                        // Mantém os contadores como estão
                    } else {
                        $errorCount++;
                    }

                } catch (\Exception $e) {
                    Log::error("Erro ao processar jogo ID {$gameId}: " . $e->getMessage());
                    $processedGames[$gameId] = [
                        'status' => 'error',
                        'message' => 'Erro interno: ' . $e->getMessage()
                    ];
                    $errorCount++;
                }
            }

            $message = $this->buildResponseMessage($importedCount, $updatedCount, $errorCount);

            Log::info('Importação concluída', [
                'imported' => $importedCount,
                'updated' => $updatedCount,
                'errors' => $errorCount
            ]);

            return response()->json([
                'success' => true,
                'message' => $message,
                'imported_count' => $importedCount,
                'updated_count' => $updatedCount,
                'error_count' => $errorCount,
                'games' => $processedGames
            ]);

        } catch (\Exception $e) {
            Log::error('Erro geral na importação: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro na importação: ' . $e->getMessage()
            ]);
        }
    }

    private function processGame($gameData)
    {
        try {
            $providerName = $gameData['provider']; // Usar o código como nome
            $originalProviderName = $gameData['provider_name'];

            $provider = $this->findOrCreateProvider($gameData['provider'], $providerName, $originalProviderName);

            if (!$provider) {
                return [
                    'status' => 'error',
                    'message' => 'Erro ao processar provedor'
                ];
            }

            $slug = $gameData['slugs'][0]['slug'] ?? null;
            if (!$slug) {
                return [
                    'status' => 'error',
                    'message' => 'Slug não encontrado'
                ];
            }

            // Normalizar o nome do jogo para evitar duplicações
            $originalName = $gameData['name'];
            $normalizedName = $this->normalizeGameName($originalName);
            
            // Log para debug
            Log::info("Processando jogo: Nome original='{$originalName}', Nome normalizado='{$normalizedName}', Slug='{$slug}'");
            
            // VERIFICAÇÃO: Buscar jogo pelo slug, provedor e distribuição diretamente na games_api
            $existingGame = GamesApi::where('slug', $slug)
                ->where('distribution', $this->distribution)
                ->where('provider_id', $provider->id)
                ->first();
            
            if ($existingGame) {
                Log::info("Slug '{$slug}' já existe para Inove (provedor: {$provider->name}) no jogo: {$existingGame->name}");
                
                // Atualizar dados do jogo existente (incluindo normalização do nome se necessário)
                $this->updateGame($existingGame, $gameData, $provider, $normalizedName);
                
                return [
                    'status' => 'already_exists',
                    'name' => $normalizedName,
                    'provider_name' => $originalProviderName,
                    'category' => ucfirst($gameData['category']),
                    'message' => 'Jogo já existe para distribuição Inove e mesmo provedor'
                ];
            } else {
                // Jogo não existe - criar novo com nome normalizado
                $game = $this->createGame($gameData, $provider, $slug, $normalizedName);

                return [
                    'status' => 'imported',
                    'name' => $normalizedName,
                    'provider_name' => $originalProviderName,
                    'category' => ucfirst($gameData['category']),
                    'message' => 'Novo jogo importado'
                ];
            }

        } catch (\Exception $e) {
            Log::error("Erro ao processar jogo: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    private function normalizeProviderName($providerName)
    {
        if (!$providerName) return '';

        return strtoupper(str_replace('_', ' ', $providerName));
    }

    private function findOrCreateProvider($providerCode, $providerName, $originalProviderName = null)
    {
        // Buscar provedor pelo provider_name (código do sistema) E distribuição
        $provider = Providers::where('provider_name', $providerCode)
                            ->where('distribution', $this->distribution)
                            ->first();

        if (!$provider) {
            // Se não encontrou pelo código, buscar pelo name (nome completo em maiúsculo)
            $provider = Providers::where('name', strtoupper($originalProviderName ?: $providerName))
                                ->where('distribution', $this->distribution)
                                ->first();
        }

        if (!$provider) {
            // Se ainda não encontrou, buscar por LIKE no name para casos parciais
            $provider = Providers::where('name', 'LIKE', '%' . strtoupper($originalProviderName ?: $providerName) . '%')
                                ->where('distribution', $this->distribution)
                                ->first();
        }

        if (!$provider) {
            $provider = Providers::create([
                'name' => strtoupper($originalProviderName ?: $providerName), // Nome completo em MAIÚSCULO (ex: PRAGMATIC PLAY LIVE)
                'provider_name' => $providerCode, // Código do sistema (ex: PRAGMATICLIVE)
                'active' => 1,
                'distribution' => $this->distribution,
                'img' => null
            ]);
            Log::info("Novo provedor criado para {$this->distribution}: {$providerCode} (nome: " . strtoupper($originalProviderName ?: $providerName) . ")");
        } else {
            // Atualizar name se estiver vazio
            if ($originalProviderName && empty($provider->name)) {
                $provider->name = strtoupper($originalProviderName);
                $provider->save();
                Log::info("Name atualizado para {$providerCode}: " . strtoupper($originalProviderName));
            }
        }

        return $provider;
    }

    private function createGame($gameData, $provider, $slug, $normalizedName = null)
    {
        // Usar nome normalizado se fornecido, senão usar o nome original
        $gameName = $normalizedName ?: $gameData['name'];
        
        // A imagem está dentro do array slugs na estrutura atual da API
        $imageToUse = $gameData['slugs'][0]['image'] ?? null;

        $game = GamesApi::create([
            'name' => $gameName,
            'category' => ucfirst($gameData['category']),
            'image' => $imageToUse,
            'distribution' => $this->distribution,
            'status' => $gameData['active'] ? 1 : 0,
            'slug' => $slug,
            'provider_id' => $provider->id
        ]);

        Log::info("Novo jogo criado: {$gameName} ({$slug}) com imagem: " . ($imageToUse ?: 'sem imagem'));

        return $game;
    }

    private function updateGame($game, $gameData, $provider, $normalizedName = null)
    {
        $updateData = [
            'category' => ucfirst($gameData['category']),
            'status' => $gameData['active'] ? 1 : 0
        ];

        // Normalizar o nome se fornecido e for diferente do nome atual
        if ($normalizedName && $game->name !== $normalizedName) {
            $updateData['name'] = $normalizedName;
            Log::info("Nome do jogo normalizado de '{$game->name}' para '{$normalizedName}'");
        }

        // Atualizar imagem apenas se não existe ou se não for uma URL válida
        if (empty($game->image) || !$this->isImageUrl($game->image)) {
            // A imagem está dentro do array slugs na estrutura atual da API
            $newImage = $gameData['slugs'][0]['image'] ?? null;
            
            if (!empty($newImage)) {
                $updateData['image'] = $newImage;
            }
        }

        $game->update($updateData);

        $gameName = $normalizedName ?: $gameData['name'];
        Log::info("Jogo atualizado: {$gameName}");
    }

    private function buildResponseMessage($imported, $updated, $errors)
    {
        $parts = [];

        if ($imported > 0) {
            $parts[] = "{$imported} jogos importados";
        }

        if ($updated > 0) {
            $parts[] = "{$updated} jogos atualizados";
        }

        if ($errors > 0) {
            $parts[] = "{$errors} erros";
        }

        if (empty($parts)) {
            return 'Nenhuma alteração realizada';
        }

        return 'Processamento concluído: ' . implode(', ', $parts);
    }

    public function getProviders()
    {
        try {
            $response = Http::timeout(30)->get($this->providersApiUrl);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao conectar com a API da Inove: ' . $response->status()
                ]);
            }

            $providers = $response->json();

            if (empty($providers)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhum provedor encontrado na API da Inove'
                ]);
            }

            return response()->json([
                'success' => true,
                'providers' => $providers
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao buscar provedores da Inove: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ]);
        }
    }

    public function importProviders()
    {
        try {
            $response = Http::timeout(30)->get($this->providersApiUrl);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao conectar com a API da Inove: ' . $response->status()
                ]);
            }

            $apiProviders = $response->json();

            if (empty($apiProviders)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhum provedor encontrado na API da Inove'
                ]);
            }

            $importedCount = 0;
            $updatedCount = 0;
            $processedProviders = [];

            foreach ($apiProviders as $apiProvider) {
                try {
                    $normalizedName = $apiProvider['provider']; // Usar o código como nome
                    $providerCode = $apiProvider['provider'];
                    $originalProviderName = $apiProvider['provider_name'];

                    // Limpar caracteres especiais como \r\n do nome normalizado para busca
                    $normalizedName = trim(str_replace(["\r", "\n"], '', $normalizedName));

                    // Buscar provedor primeiro pelo provider_name (código do sistema)
                    $existingProvider = Providers::where('provider_name', $providerCode)
                                                  ->where('distribution', $this->distribution)
                                                  ->first();

                    // Se não encontrou pelo código, buscar pelo name (nome completo em maiúsculo)
                    if (!$existingProvider) {
                        $existingProvider = Providers::where('name', strtoupper($originalProviderName))
                                                      ->where('distribution', $this->distribution)
                                                      ->first();
                    }

                    // Se ainda não encontrou, buscar por LIKE no name para casos parciais
                    if (!$existingProvider) {
                        $existingProvider = Providers::where('name', 'LIKE', '%' . strtoupper($originalProviderName) . '%')
                                                      ->where('distribution', $this->distribution)
                                                      ->first();
                    }

                    if ($existingProvider) {
                        // Atualizar provedor existente
                        $updatedFields = [];

                        // Atualizar name (nome completo em maiúsculo) SEMPRE se diferente
                        $expectedName = strtoupper($originalProviderName);
                        if ($existingProvider->name !== $expectedName) {
                            $existingProvider->name = $expectedName;
                            $updatedFields[] = 'name';
                        }

                        // Atualizar provider_name (código do sistema) SEMPRE se diferente
                        if ($existingProvider->provider_name !== $providerCode) {
                            $existingProvider->provider_name = $providerCode;
                            $updatedFields[] = 'provider_name';
                        }

                        if (empty($existingProvider->img) && !empty($apiProvider['image'])) {
                            $existingProvider->img = $apiProvider['image'];
                            $updatedFields[] = 'img';
                        }

                        if (empty($existingProvider->distribution)) {
                            $existingProvider->distribution = $this->distribution;
                            $updatedFields[] = 'distribution';
                        }

                        if ($existingProvider->active !== $apiProvider['active']) {
                            $existingProvider->active = $apiProvider['active'];
                            $updatedFields[] = 'active';
                        }

                        if (!empty($updatedFields)) {
                            $existingProvider->save();
                            $updatedCount++;

                            $processedProviders[] = [
                                'name' => $normalizedName,
                                'provider_code' => $providerCode,
                                'status' => 'updated',
                                'updated_fields' => $updatedFields
                            ];
                        }
                    } else {
                        // Criar novo provedor
                        Providers::create([
                            'name' => strtoupper($originalProviderName), // Nome completo em MAIÚSCULO (ex: PRAGMATIC PLAY LIVE)
                            'provider_name' => $providerCode, // Código do sistema (ex: PRAGMATICLIVE)
                            'active' => $apiProvider['active'],
                            'distribution' => $this->distribution,
                            'img' => $apiProvider['image'] ?? null
                        ]);

                        $importedCount++;
                        $processedProviders[] = [
                            'name' => strtoupper($originalProviderName),
                            'provider_code' => $providerCode,
                            'status' => 'imported'
                        ];
                    }
                } catch (\Exception $e) {
                    Log::error("Erro ao processar provedor: " . $e->getMessage(), ['provider' => $apiProvider]);
                }
            }

            $message = $this->buildProvidersResponseMessage($importedCount, $updatedCount);

            return response()->json([
                'success' => true,
                'message' => $message,
                'imported_count' => $importedCount,
                'updated_count' => $updatedCount,
                'providers' => $processedProviders
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao importar provedores da Inove: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro na importação: ' . $e->getMessage()
            ]);
        }
    }

    public function updateProviders()
    {
        try {
            $response = Http::timeout(30)->get($this->providersApiUrl);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao conectar com a API da Inove: ' . $response->status()
                ]);
            }

            $apiProviders = $response->json();
            $updatedCount = 0;
            $processedProviders = [];

                        foreach ($apiProviders as $apiProvider) {
                $normalizedName = $apiProvider['provider']; // Usar o código como nome
                $providerCode = $apiProvider['provider'];
                $originalProviderName = $apiProvider['provider_name'];

                    // Log para debug
                    Log::info("Processando provedor para atualização: {$normalizedName} (provider_name: {$originalProviderName})");

                // Buscar provedor primeiro pelo provider_name (código do sistema)
                $provider = Providers::where('provider_name', $providerCode)
                                   ->where('distribution', $this->distribution)
                                   ->first();

                // Se não encontrou pelo código, buscar pelo name (nome completo em maiúsculo)
                if (!$provider) {
                    $provider = Providers::where('name', strtoupper($originalProviderName))
                                       ->where('distribution', $this->distribution)
                                       ->first();
                }

                // Se ainda não encontrou, buscar por LIKE no name para casos parciais
                if (!$provider) {
                    $provider = Providers::where('name', 'LIKE', '%' . strtoupper($originalProviderName) . '%')
                                       ->where('distribution', $this->distribution)
                                       ->first();
                }

                // Se ainda não encontrou, buscar provedores com name null ou vazio que tenham código similar
                if (!$provider) {
                    $provider = Providers::where('provider_name', 'LIKE', $providerCode . '%')
                                       ->where('distribution', $this->distribution)
                                       ->where(function($query) {
                                           $query->whereNull('name')
                                                 ->orWhere('name', '');
                                       })
                                       ->first();
                }

                if ($provider) {
                    Log::info("Provedor encontrado: {$provider->name} (ID: {$provider->id}, provider_name atual: " . ($provider->provider_name ?? 'NULL') . ")");
                    
                    $updatedFields = [];

                    // Forçar atualização do name (nome completo em maiúsculo) SEMPRE
                    $expectedName = strtoupper($originalProviderName);
                    if ($provider->name !== $expectedName) {
                        Log::info("Atualizando name de '{$provider->name}' para '{$expectedName}'");
                        $provider->name = $expectedName;
                        $updatedFields[] = 'name';
                    }

                    // Forçar atualização do provider_name (código do sistema) SEMPRE
                    if ($provider->provider_name !== $providerCode) {
                        Log::info("Atualizando provider_name de '{$provider->provider_name}' para '{$providerCode}'");
                        $provider->provider_name = $providerCode;
                        $updatedFields[] = 'provider_name';
                    }

                    if (empty($provider->img) && !empty($apiProvider['image'])) {
                        $provider->img = $apiProvider['image'];
                        $updatedFields[] = 'img';
                    }

                    if (empty($provider->distribution)) {
                        $provider->distribution = $this->distribution;
                        $updatedFields[] = 'distribution';
                    }

                    if (!empty($updatedFields)) {
                        Log::info("Salvando provedor {$provider->name} com campos atualizados: " . implode(', ', $updatedFields));
                        $provider->save();
                        $updatedCount++;

                        $processedProviders[] = [
                            'name' => strtoupper($originalProviderName),
                            'provider_code' => $providerCode,
                            'status' => 'updated',
                            'updated_fields' => $updatedFields
                        ];
                    } else {
                        Log::info("Nenhum campo precisa ser atualizado para o provedor {$provider->name}");
                    }
                } else {
                    Log::warning("Provedor não encontrado na base de dados: {$providerCode} (nome: {$originalProviderName})");
                }
            }

            return response()->json([
                'success' => true,
                'message' => $updatedCount > 0 ?
                    "Atualização concluída: {$updatedCount} provedores atualizados" :
                    'Nenhum provedor precisava de atualização',
                'updated_count' => $updatedCount,
                'providers' => $processedProviders
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao atualizar provedores da Inove: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro na atualização: ' . $e->getMessage()
            ]);
        }
    }

    private function buildProvidersResponseMessage($imported, $updated)
    {
        $parts = [];

        if ($imported > 0) {
            $parts[] = "{$imported} provedores importados";
        }

        if ($updated > 0) {
            $parts[] = "{$updated} provedores atualizados";
        }

        if (empty($parts)) {
            return 'Nenhuma alteração realizada';
        }

        return 'Processamento concluído: ' . implode(', ', $parts);
    }

    /**
     * Normaliza o nome do jogo para formato humanizado
     * Ex: AUTO_MEGA_ROULETTE -> Auto Mega Roulette
     */
    private function normalizeGameName($name)
    {
        if (!$name) return '';

        // Converter para minúsculas e substituir underscores por espaços
        $normalized = strtolower(str_replace('_', ' ', $name));
        
        // Capitalizar cada palavra
        $normalized = ucwords($normalized);
        
        return $normalized;
    }

    /**
     * Verifica se uma string é uma URL de imagem válida
     */
    private function isImageUrl($imageString)
    {
        // Verificar se é uma URL válida (começa com http ou https)
        if (filter_var($imageString, FILTER_VALIDATE_URL) &&
            (strpos($imageString, 'http://') === 0 || strpos($imageString, 'https://') === 0)) {
            return true;
        }

        // Se não for URL, é considerado caminho local
        return false;
    }
}