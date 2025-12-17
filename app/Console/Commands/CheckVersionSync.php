<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class CheckVersionSync extends Command
{
    /**
     * Nome do comando
     *
     * @var string
     */
    protected $signature = 'version:sync';

    /**
     * Descrição do comando
     *
     * @var string
     */
    protected $description = 'Verifica e aplica sincronização de versão entre plataforma cliente e plataforma mãe';

    /**
     * Construtor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Executa o comando
     */
    public function handle()
    {
        $this->info('Iniciando verificação de sincronização de versão...');
        
        try {
            // Verificar se existe um arquivo de sincronização
            $syncFile = storage_path('app/version_sync.json');
            
            if (!File::exists($syncFile)) {
                $this->info('Nenhum arquivo de sincronização encontrado.');
                return;
            }
            
            // Ler o arquivo de sincronização
            $syncContent = File::get($syncFile);
            $syncData = json_decode($syncContent, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->error('Erro ao decodificar arquivo de sincronização: ' . json_last_error_msg());
                return;
            }
            
            // Obter a nova versão
            $newVersion = $syncData['version'] ?? null;
            $updatedAt = $syncData['updated_at'] ?? date('Y-m-d H:i:s');
            $updateSource = $syncData['update_source'] ?? 'unknown';
            
            if (!$newVersion) {
                $this->error('Arquivo de sincronização não contém número de versão.');
                return;
            }
            
            // Atualizar versão no arquivo version.json
            $this->updateVersionJsonFile($newVersion, $updateSource);
            
            // Atualizar versão no arquivo config/app.php
            $this->updateAppConfigFile($newVersion);
            
            // Renomear arquivo de sincronização para evitar processamento duplicado
            $processedFile = storage_path('app/version_sync_processed_' . date('Ymd_His') . '.json');
            File::move($syncFile, $processedFile);
            
            $this->info("Sincronização de versão concluída com sucesso. Nova versão: {$newVersion}");
            Log::info("Sincronização de versão aplicada. Nova versão: {$newVersion}, Origem: {$updateSource}");
            
            // Limpar cache
            $this->call('cache:clear');
            
        } catch (\Exception $e) {
            $this->error("Erro ao sincronizar versão: " . $e->getMessage());
            Log::error("Erro ao sincronizar versão: " . $e->getMessage());
        }
    }
    
    /**
     * Atualiza o arquivo version.json com a nova versão
     */
    private function updateVersionJsonFile($newVersion, $updateSource)
    {
        $versionFile = base_path('version.json');
        
        if (File::exists($versionFile)) {
            // Ler o conteúdo atual
            $content = File::get($versionFile);
            $versionData = json_decode($content, true);
            
            if (json_last_error() === JSON_ERROR_NONE) {
                // Preservar a versão antiga para o changelog
                $oldVersion = $versionData['version'] ?? '1.0.0';
                
                // Atualizar versão preservando outros dados
                $versionData['version'] = $newVersion;
                $versionData['update_date'] = date('Y-m-d');
                
                // Adicionar entrada ao changelog se existir
                if (isset($versionData['changelog']) && is_array($versionData['changelog'])) {
                    $updateMessage = "Atualizado de v{$oldVersion} para v{$newVersion} via {$updateSource}";
                    array_unshift($versionData['changelog'], $updateMessage);
                    
                    // Limitar a quantidade de entradas no changelog (manter apenas 10)
                    if (count($versionData['changelog']) > 10) {
                        $versionData['changelog'] = array_slice($versionData['changelog'], 0, 10);
                    }
                }
                
                // Salvar o arquivo atualizado
                File::put($versionFile, json_encode($versionData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                $this->info("Arquivo version.json atualizado para versão {$newVersion}");
            } else {
                $this->error("Erro ao decodificar version.json: " . json_last_error_msg());
            }
        } else {
            // Criar novo arquivo version.json se não existir
            $versionData = [
                'version' => $newVersion,
                'description' => 'Atualização via sincronização',
                'release_date' => date('Y-m-d'),
                'update_date' => date('Y-m-d'),
                'min_php_version' => '8.0.0',
                'changelog' => ["Versão sincronizada via {$updateSource}"]
            ];
            
            File::put($versionFile, json_encode($versionData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            $this->info("Novo arquivo version.json criado com versão {$newVersion}");
        }
    }
    
    /**
     * Atualiza o arquivo config/app.php com a nova versão
     */
    private function updateAppConfigFile($newVersion)
    {
        $configFile = config_path('app.php');
        
        if (File::exists($configFile)) {
            $content = File::get($configFile);
            
            // Substituir a versão
            $pattern = "/'version'\s*=>\s*'([^']*)'/";
            if (preg_match($pattern, $content)) {
                $updatedContent = preg_replace(
                    $pattern,
                    "'version' => '{$newVersion}'",
                    $content
                );
                
                File::put($configFile, $updatedContent);
                $this->info("Arquivo config/app.php atualizado para versão {$newVersion}");
            } else {
                $this->error("Não foi possível encontrar a linha de versão no arquivo config/app.php");
            }
        } else {
            $this->error("Arquivo config/app.php não encontrado");
        }
    }
} 