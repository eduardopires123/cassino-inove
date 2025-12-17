<?php

namespace App\Services;

use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Configuration;
use Brevo\Client\Model\SendSmtpEmail;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use App\Models\Settings;

class BrevoService
{
    protected $apiInstance;
    protected $domain;
    protected $displayName;
    protected $useLocalTemplates;

    public function __construct()
    {
        $config = Configuration::getDefaultConfiguration()
            ->setApiKey('api-key', config('services.brevo.key'));

        $this->apiInstance = new TransactionalEmailsApi(
            new Client(),
            $config
        );

        // Domínio para envio de emails - sempre usar o valor do .env
        $this->domain = env('BREVO_DEFAULT_DOMAIN');
        
        // Nome de exibição para o domínio - tenta buscar do banco de dados primeiro
        try {
            $settings = Settings::first();
            $this->displayName = $settings ? $settings->name : env('BREVO_DISPLAY_NAME', config('app.name'));
        } catch (\Exception $e) {
            // Em caso de falha (ex: durante migrations), usa o valor do .env
            $this->displayName = env('BREVO_DISPLAY_NAME', config('app.name'));
            Log::warning('Não foi possível obter nome do site das configurações. Usando fallback.', [
                'error' => $e->getMessage()
            ]);
        }

        // Usar templates locais ou do Brevo
        $this->useLocalTemplates = env('USE_LOCAL_EMAIL_TEMPLATES', true);
    }

    /**
     * Enviar um email simples
     *
     * @param string|array $to Destinatário (email ou array ['email' => 'nome'])
     * @param string $subject Assunto do email
     * @param string $htmlContent Conteúdo HTML do email
     * @param array $params Parâmetros adicionais (cc, bcc, reply_to, etc)
     * @return bool
     */
    public function enviarEmail($to, string $subject, string $htmlContent, array $params = []): bool
    {
        try {
            $sendSmtpEmail = new SendSmtpEmail();
            
            // Configurar destinatário(s)
            if (is_array($to) && !isset($to['email'])) {
                $sendSmtpEmail->setTo($to);
            } else {
                if (is_string($to)) {
                    $to = [['email' => $to]];
                } else {
                    $to = [['email' => $to['email'], 'name' => $to['name'] ?? null]];
                }
                $sendSmtpEmail->setTo($to);
            }

            // Definir remetente
            $sender = $params['sender'] ?? [
                'name' => $params['sender_name'] ?? $this->displayName,
                'email' => $params['sender_email'] ?? "noreply@{$this->domain}"
            ];
            $sendSmtpEmail->setSender($sender);

            // Configurar assunto e conteúdo
            $sendSmtpEmail->setSubject($subject);
            $sendSmtpEmail->setHtmlContent($htmlContent);

            // Configurações adicionais (cc, bcc, reply_to)
            if (isset($params['cc'])) {
                $sendSmtpEmail->setCc($params['cc']);
            }
            
            if (isset($params['bcc'])) {
                $sendSmtpEmail->setBcc($params['bcc']);
            }
            
            if (isset($params['reply_to'])) {
                $sendSmtpEmail->setReplyTo($params['reply_to']);
            }

            // Configurar anexos
            if (isset($params['attachments']) && is_array($params['attachments'])) {
                $sendSmtpEmail->setAttachment($params['attachments']);
            }

            // Configurar parâmetros de template
            if (isset($params['params']) && is_array($params['params'])) {
                $sendSmtpEmail->setParams($params['params']);
            }

            // Configurar conteúdo de texto simples (fallback)
            if (isset($params['text_content'])) {
                $sendSmtpEmail->setTextContent($params['text_content']);
            }

            // Enviar o email
            $result = $this->apiInstance->sendTransacEmail($sendSmtpEmail);
            Log::info('Email enviado com sucesso', [
                'message_id' => $result->getMessageId(),
                'domain' => $this->domain
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Erro ao enviar email com Brevo', [
                'erro' => $e->getMessage(),
                'destinatario' => $to,
                'assunto' => $subject
            ]);
            
            return false;
        }
    }

    /**
     * Enviar email usando um template do Brevo ou template local
     *
     * @param string|array $to Destinatário (email ou array ['email' => 'nome'])
     * @param int|string $templateIdOrSlug ID do template no Brevo ou slug do template local
     * @param array $params Variáveis para o template
     * @param array|null $sender Remetente personalizado ['email' => '', 'name' => '']
     * @param array $options Opções adicionais
     * @return bool
     */
    public function enviarEmailTemplate($to, $templateIdOrSlug, array $params = [], $sender = null, array $options = []): bool
    {
        // Adicionar o remetente às opções se fornecido
        if ($sender && isset($sender['email']) && !empty($sender['email'])) {
            $options['sender'] = $sender;
        }
        
        try {
            // SEMPRE usar templates locais do banco de dados
            // Verificar se o template está sendo buscado por ID (numérico)
            if (is_numeric($templateIdOrSlug)) {
                // Buscar o template pelo ID no banco de dados
                $template = \App\Models\EmailTemplate::find($templateIdOrSlug);
                
                if ($template) {
                    \Illuminate\Support\Facades\Log::info('Usando template local do banco por ID', [
                        'template_id' => $templateIdOrSlug,
                        'slug' => $template->slug
                    ]);
                    
                    // SEMPRE usar o template local, ignorando brevo_template_id
                    return $this->enviarEmailTemplateLocal($to, $template->slug, $params, $options);
                } else {
                    // Log de erro: template não encontrado
                    \Illuminate\Support\Facades\Log::error('Template de email não encontrado no banco de dados', [
                        'template_id' => $templateIdOrSlug
                    ]);
                    
                    return false;
                }
            }
            
            // Se for uma string (slug), buscar no banco de dados
            if (is_string($templateIdOrSlug)) {
                $template = \App\Models\EmailTemplate::findBySlug($templateIdOrSlug);
                
                if ($template) {
                    \Illuminate\Support\Facades\Log::info('Usando template local do banco por slug', [
                        'slug' => $templateIdOrSlug,
                        'template_id' => $template->id
                    ]);
                    
                    // SEMPRE usar template local, ignorando brevo_template_id
                    return $this->enviarEmailTemplateLocal($to, $templateIdOrSlug, $params, $options);
                } else {
                    \Illuminate\Support\Facades\Log::error('Template não encontrado no banco por slug', [
                        'slug' => $templateIdOrSlug
                    ]);
                    
                    return false;
                }
            }
            
            // Se chegou até aqui, não foi possível determinar o tipo de template
            \Illuminate\Support\Facades\Log::error('Tipo de template não identificado', [
                'template' => $templateIdOrSlug
            ]);
            
            return false;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erro ao enviar email com template', [
                'erro' => $e->getMessage(),
                'template' => $templateIdOrSlug
            ]);
            
            return false;
        }
    }

    /**
     * Enviar email usando um template local
     *
     * @param string|array $to Destinatário
     * @param string $slug Slug do template
     * @param array $params Variáveis para o template
     * @param array $options Opções adicionais
     * @return bool
     */
    protected function enviarEmailTemplateLocal($to, string $slug, array $params = [], array $options = []): bool
    {
        // Procurar o template pelo slug
        $template = EmailTemplate::findBySlug($slug);
        
        if (!$template) {
            Log::error('Template de email não encontrado', ['slug' => $slug]);
            return false;
        }
        
        \Illuminate\Support\Facades\Log::info('Processando template local', [
            'slug' => $slug,
            'template_id' => $template->id,
            'name' => $template->name
        ]);
        
        // Adicionar variáveis padrão aos parâmetros
        $params = array_merge([
            'site_name' => config('app.name'),
            'site_url' => config('app.url'),
            'year' => date('Y'),
        ], $params);
        
        // Renderizar o assunto e conteúdo com as variáveis
        $subject = $template->renderSubject($params);
        $htmlContent = $template->renderHtml($params);
        $textContent = $template->renderText($params);
        
        \Illuminate\Support\Facades\Log::info('Template local renderizado', [
            'slug' => $slug,
            'subject' => $subject,
            'has_html' => !empty($htmlContent),
            'has_text' => !empty($textContent)
        ]);
        
        // Adicionar o conteúdo de texto às opções
        if ($textContent) {
            $options['text_content'] = $textContent;
        }
        
        // Enviar o email usando o método simples
        return $this->enviarEmail($to, $subject, $htmlContent, array_merge($options, ['params' => $params]));
    }

    /**
     * Obter o ID do template do Brevo pelo slug
     *
     * @param string $slug
     * @return int|null
     */
    protected function getBrevoTemplateIdBySlug(string $slug): ?int
    {
        $templateMap = [
            'welcome' => env('BREVO_TEMPLATE_BOAS_VINDAS'),
            'password-reset' => env('BREVO_TEMPLATE_PASSWORD_RESET'),
            'verification-code' => env('BREVO_TEMPLATE_VERIFICATION_CODE'),
        ];
        
        return $templateMap[$slug] ?? null;
    }

    /**
     * Enviar email usando um template do Brevo
     *
     * @param string|array $to Destinatário
     * @param int $templateId ID do template no Brevo
     * @param array $params Variáveis para o template
     * @param array $options Opções adicionais
     * @return bool
     */
    protected function enviarEmailTemplateBrevo($to, int $templateId, array $params = [], array $options = []): bool
    {
        try {
            // Log do início do processo
            \Illuminate\Support\Facades\Log::info('Iniciando envio de email com template Brevo', [
                'template_id' => $templateId,
                'to' => is_array($to) && isset($to[0]) ? (is_array($to[0]) ? $to[0]['email'] : $to[0]) : $to,
                'params_count' => count($params),
                'domain' => $this->domain
            ]);

            $sendSmtpEmail = new SendSmtpEmail();
            
            // Configurar destinatário(s)
            if (is_array($to) && !isset($to['email'])) {
                $sendSmtpEmail->setTo($to);
            } else {
                if (is_string($to)) {
                    $to = [['email' => $to]];
                } else {
                    $to = [['email' => $to['email'], 'name' => $to['name'] ?? null]];
                }
                $sendSmtpEmail->setTo($to);
            }
            
            // Configurar remetente
            $sender = $options['sender'] ?? [
                'name' => $options['sender_name'] ?? $this->displayName,
                'email' => $options['sender_email'] ?? "noreply@{$this->domain}"
            ];
            
            // Garantir que o remetente seja válido
            if (empty($sender['email']) || !filter_var($sender['email'], FILTER_VALIDATE_EMAIL)) {
                // Log do problema
                \Illuminate\Support\Facades\Log::warning('Email do remetente inválido, usando padrão', [
                    'original_email' => $sender['email'] ?? 'não definido',
                    'domain' => $this->domain
                ]);
                
                // Usar o domínio configurado
                $sender['email'] = "noreply@{$this->domain}";
            }
            
            // Registrar detalhes do envio
            \Illuminate\Support\Facades\Log::info('Configurações do email', [
                'template_id' => $templateId,
                'to' => is_array($to[0]) ? $to[0]['email'] : $to[0],
                'sender' => $sender,
                'domain' => $this->domain,
                'params' => $params
            ]);
            
            $sendSmtpEmail->setSender($sender);
            
            // Configurar parâmetros de template
            $sendSmtpEmail->setTemplateId($templateId);
            
            if (!empty($params)) {
                $sendSmtpEmail->setParams($params);
            }
            
            // Configurações adicionais
            if (isset($options['reply_to'])) {
                $sendSmtpEmail->setReplyTo($options['reply_to']);
            }
            
            if (isset($options['bcc']) && is_array($options['bcc'])) {
                $sendSmtpEmail->setBcc($options['bcc']);
            }
            
            // Tentar enviar o email
            \Illuminate\Support\Facades\Log::info('Enviando email via API Brevo...');
            $result = $this->apiInstance->sendTransacEmail($sendSmtpEmail);
            
            \Illuminate\Support\Facades\Log::info('Email com template Brevo enviado com sucesso', [
                'message_id' => $result->getMessageId(),
                'to' => is_array($to[0]) ? $to[0]['email'] : $to[0],
                'template_id' => $templateId
            ]);
            
            return true;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erro ao enviar email com template Brevo', [
                'erro' => $e->getMessage(),
                'template_id' => $templateId,
                'to' => is_array($to) && isset($to[0]) && is_array($to[0]) ? $to[0]['email'] : (is_array($to) ? json_encode($to) : $to),
                'params' => $params,
                'domain' => $this->domain,
                'trace' => $e->getTraceAsString()
            ]);
            
            return false;
        }
    }
} 