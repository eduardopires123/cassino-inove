<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class SyncParentVersionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'platform:sync-version';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza a versão da plataforma mãe a partir do arquivo de sincronização';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Iniciando sincronização de versão com a plataforma mãe...');
        
        try {
            // Verificar se o arquivo de sincronização existe
            $syncFile = storage_path('app/version_sync.json');
            
            if (!File::exists($syncFile)) {
                $this->info('Nenhum arquivo de sincronização encontrado.');
                return Command::SUCCESS;
            }
            
            // Ler o conteúdo do arquivo de sincronização
            $syncContent = File::get($syncFile);
            $syncData = json_decode($syncContent, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->error('Erro ao decodificar arquivo de sincronização: ' . json_last_error_msg());
                return Command::FAILURE;
            }
            
            // Verificar se os dados necessários estão presentes
            if (!isset($syncData['version'])) {
                $this->error('Arquivo de sincronização não contém informações de versão.');
                return Command::FAILURE;
            }
            
            $newVersion = $syncData['version'];
            $this->info("Nova versão detectada: {$newVersion}");
            
            // Atualizar o arquivo version.json da plataforma mãe
            $parentVersionFile = base_path('version.json');
            if (File::exists($parentVersionFile)) {
                $versionContent = File::get($parentVersionFile);
                $versionData = json_decode($versionContent, true);
                
                if (json_last_error() === JSON_ERROR_NONE) {
                    // Preservar a versão antiga para o changelog
                    $oldVersion = $versionData['version'] ?? '1.0.0';
                    
                    // Atualizar versão
                    $versionData['version'] = $newVersion;
                    $versionData['update_date'] = date('Y-m-d');
                    
                    // Adicionar entrada ao changelog se existir
                    if (isset($versionData['changelog']) && is_array($versionData['changelog'])) {
                        $updateMessage = "Atualizado via plataforma cliente de v{$oldVersion} para v{$newVersion}";
                        array_unshift($versionData['changelog'], $updateMessage);
                        
                        // Limitar a quantidade de entradas no changelog
                        if (count($versionData['changelog']) > 10) {
                            $versionData['changelog'] = array_slice($versionData['changelog'], 0, 10);
                        }
                    }
                    
                    // Salvar o arquivo atualizado
                    File::put($parentVersionFile, json_encode($versionData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                    $this->info("Arquivo version.json atualizado com sucesso!");
                    
                    // Também atualizar o arquivo config/app.php
                    $this->updateConfigAppFile($newVersion);
                } else {
                    $this->error("Erro ao decodificar version.json: " . json_last_error_msg());
                    return Command::FAILURE;
                }
            } else {
                // Criar um novo arquivo version.json
                $versionData = [
                    'version' => $newVersion,
                    'description' => 'Plataforma Inove iGaming',
                    'release_date' => date('Y-m-d'),
                    'update_date' => date('Y-m-d'),
                    'min_php_version' => '8.0.0',
                    'changelog' => ["Versão {$newVersion} sincronizada via plataforma cliente"]
                ];
                
                File::put($parentVersionFile, json_encode($versionData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                $this->info("Novo arquivo version.json criado com versão {$newVersion}");
                
                // Também atualizar o arquivo config/app.php
                $this->updateConfigAppFile($newVersion);
            }
            
            // Remover o arquivo de sincronização após processamento
            File::delete($syncFile);
            $this->info("Arquivo de sincronização processado e removido.");
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("Erro durante a sincronização: " . $e->getMessage());
            Log::error("Erro durante a sincronização de versão: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
    
    /**
     * Atualiza o arquivo config/app.php com a nova versão
     */
    private function updateConfigAppFile($newVersion)
    {
        try {
            $configFile = config_path('app.php');
            
            if (File::exists($configFile)) {
                $content = File::get($configFile);
                
                // Substituir a versão
                $updatedContent = preg_replace(
                    "/'version' => '(.*?)'/",
                    "'version' => '{$newVersion}'",
                    $content
                );
                
                if ($updatedContent !== $content) {
                    File::put($configFile, $updatedContent);
                    $this->info("Arquivo config/app.php atualizado com a versão {$newVersion}");
                    return true;
                } else {
                    $this->warn("Versão já atualizada ou padrão não encontrado em config/app.php");
                }
            } else {
                $this->warn("Arquivo config/app.php não encontrado");
            }
            
            return false;
        } catch (\Exception $e) {
            $this->error("Erro ao atualizar config/app.php: " . $e->getMessage());
            return false;
        }
    }
} 