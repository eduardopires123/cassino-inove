<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Admin\FileVersionController;
use App\Http\Controllers\Api\UpdateServerController;

class GenerateUpdatePackage extends Command
{
    protected $signature = 'updates:generate {version} {--type=full} {--base-version=}';
    protected $description = 'Gera um pacote de atualização para a plataforma';

    public function handle()
    {
        $version = $this->argument('version');
        $type = $this->option('type');
        $baseVersion = $this->option('base-version');
        
        $this->info("Gerando pacote de atualização {$type} versão {$version}...");
        
        // Gerar manifesto
        $fileVersionController = new FileVersionController();
        $manifestResult = $fileVersionController->generateManifest();
        
        if (!$manifestResult['success']) {
            $this->error('Falha ao gerar manifesto: ' . $manifestResult['error']);
            return 1;
        }
        
        $this->info('Manifesto gerado com sucesso.');
        
        // Preparar pacote de atualização
        $updateServerController = new UpdateServerController();
        $updateResult = $updateServerController->prepareUpdate(new \Illuminate\Http\Request([
            'version' => $version,
            'notes' => $this->ask('Notas da atualização (opcional):'),
            'update_type' => $type,
            'base_version' => $type === 'incremental' ? $baseVersion : null,
        ]));
        
        if (!$updateResult['success']) {
            $this->error('Falha ao preparar atualização: ' . $updateResult['error']);
            return 1;
        }
        
        $this->info('Pacote de atualização gerado com sucesso.');
        return 0;
    }
}