<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmailTemplate;
use App\Services\BrevoService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class CheckEmailConfiguration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:check-config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica e exibe configurações de email do sistema';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Verificando configurações de email do sistema...');
        $this->newLine();

        // Verificar configurações do .env
        $this->info('1. Configurações do .env:');
        $this->table(
            ['Chave', 'Valor'],
            [
                ['MAIL_MAILER', env('MAIL_MAILER', 'não definido')],
                ['MAIL_HOST', env('MAIL_HOST', 'não definido')],
                ['MAIL_PORT', env('MAIL_PORT', 'não definido')],
                ['MAIL_USERNAME', env('MAIL_USERNAME', 'não definido')],
                ['MAIL_PASSWORD', env('MAIL_PASSWORD') ? '****' : 'não definido'],
                ['MAIL_ENCRYPTION', env('MAIL_ENCRYPTION', 'não definido')],
                ['MAIL_FROM_ADDRESS', env('MAIL_FROM_ADDRESS', 'não definido')],
                ['MAIL_FROM_NAME', env('MAIL_FROM_NAME', 'não definido')],
                ['BREVO_DEFAULT_DOMAIN', env('BREVO_DEFAULT_DOMAIN', 'não definido')],
                ['BREVO_DISPLAY_NAME', env('BREVO_DISPLAY_NAME', 'não definido')],
                ['BREVO_TEMPLATE_BOAS_VINDAS', env('BREVO_TEMPLATE_BOAS_VINDAS', 'não definido')],
                ['BREVO_TEMPLATE_PASSWORD_RESET', env('BREVO_TEMPLATE_PASSWORD_RESET', 'não definido')],
                ['BREVO_TEMPLATE_VERIFICATION_CODE', env('BREVO_TEMPLATE_VERIFICATION_CODE', 'não definido')],
                ['APP_URL', env('APP_URL', 'não definido')],
            ]
        );
        $this->newLine();

        // Verificar se as configurações de email do Laravel estão carregadas corretamente
        $this->info('2. Configurações do Laravel:');
        $this->table(
            ['Chave', 'Valor'],
            [
                ['mail.from.address', config('mail.from.address', 'não definido')],
                ['mail.from.name', config('mail.from.name', 'não definido')],
                ['app.url', config('app.url', 'não definido')],
                ['app.name', config('app.name', 'não definido')],
            ]
        );
        $this->newLine();

        // Verificar configuração da API Brevo
        $this->info('3. Configuração da API Brevo:');
        $this->table(
            ['Chave', 'Valor'],
            [
                ['API Key', env('BREVO_API_KEY') ? substr(env('BREVO_API_KEY'), 0, 5) . '****' : 'não definido'],
                ['Ativo', env('BREVO_API_KEY') ? 'Sim' : 'Não'],
            ]
        );
        $this->newLine();

        // Listar templates de email do banco de dados
        $this->info('4. Templates de email do banco de dados:');
        $templates = EmailTemplate::all();
        
        if ($templates->count() > 0) {
            $templateData = [];
            foreach ($templates as $template) {
                $templateData[] = [
                    $template->id,
                    $template->name,
                    $template->slug,
                    $template->brevo_template_id ?: 'N/A',
                    $template->is_active ? 'Sim' : 'Não',
                ];
            }
            
            $this->table(
                ['ID', 'Nome', 'Slug', 'ID Brevo', 'Ativo'],
                $templateData
            );
        } else {
            $this->warn('     Nenhum template de email encontrado no banco de dados.');
        }
        $this->newLine();

        // Checar domínio de email
        $this->info('5. Verificação de domínio de email:');
        $mailFromAddress = config('mail.from.address');
        $appUrl = config('app.url');
        $domain = parse_url($appUrl, PHP_URL_HOST);
        
        if ($mailFromAddress) {
            $mailDomain = substr(strrchr($mailFromAddress, "@"), 1);
            $this->line("     Email do remetente: $mailFromAddress");
            $this->line("     Domínio do email: $mailDomain");
            
            if ($domain) {
                $this->line("     Domínio da aplicação: $domain");
                
                if ($mailDomain == $domain) {
                    $this->info("     ✓ O domínio do email corresponde ao domínio da aplicação.");
                } else {
                    $this->warn("     ⚠ O domínio do email NÃO corresponde ao domínio da aplicação.");
                    $this->line("       Para evitar problemas com serviços como Brevo, considere usar um email do mesmo domínio.");
                    $this->line("       Exemplo: noreply@$domain");
                }
            } else {
                $this->warn("     ⚠ Não foi possível extrair o domínio do APP_URL.");
            }
        } else {
            $this->error("     ✗ Email do remetente não configurado (MAIL_FROM_ADDRESS).");
        }
        $this->newLine();

        $this->info('Verificação concluída!');
        
        return 0;
    }
} 