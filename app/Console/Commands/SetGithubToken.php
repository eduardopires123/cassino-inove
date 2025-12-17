<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetGithubToken extends Command
{
    /**
     * O nome e a assinatura do comando console.
     *
     * @var string
     */
    protected $signature = 'github:token {token : O token de acesso pessoal do GitHub}';

    /**
     * A descrição do comando console.
     *
     * @var string
     */
    protected $description = 'Define o token de acesso do GitHub para o sistema de atualização';

    /**
     * Execute o comando console.
     */
    public function handle()
    {
        $token = $this->argument('token');
        
        // Atualizar no arquivo .env
        try {
            $envPath = base_path('.env');
            if (File::exists($envPath)) {
                $envContent = File::get($envPath);
                
                // Verificar se a entrada já existe
                if (strpos($envContent, 'GITHUB_TOKEN=') !== false) {
                    // Substituir valor existente
                    $envContent = preg_replace('/GITHUB_TOKEN=(.*)/', 'GITHUB_TOKEN=' . $token, $envContent);
                } else {
                    // Adicionar nova entrada
                    $envContent .= "\n# Token do GitHub para atualizações\nGITHUB_TOKEN=" . $token . "\n";
                }
                
                // Salvar o arquivo .env atualizado
                File::put($envPath, $envContent);
                
                $this->info('Token do GitHub configurado com sucesso no arquivo .env');
            } else {
                $this->error('Arquivo .env não encontrado!');
                return 1;
            }
        } catch (\Exception $e) {
            $this->error('Erro ao atualizar arquivo .env: ' . $e->getMessage());
            return 1;
        }
        
        $this->info('Token do GitHub configurado com sucesso!');
    }
} 