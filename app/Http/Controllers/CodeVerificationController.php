<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;
use App\Services\BrevoService;
use Illuminate\Support\Facades\Log;

class CodeVerificationController extends Controller
{
    protected $brevoService;
    
    public function __construct(BrevoService $brevoService)
    {
        $this->brevoService = $brevoService;
    }
    
    // Gera e envia código de verificação por email
    public function sendEmailCode(Request $request)
    {
        $user = auth()->user();
        
        // Gerar código aleatório de 5 dígitos
        $code = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
        
        // Salvar código no banco de dados - Usar updateOrCreate para evitar códigos duplicados
        VerificationCode::updateOrCreate(
            ['user_id' => $user->id, 'type' => 'email'],
            ['code' => $code, 'expires_at' => now()->addMinutes(10)]
        );
        
        try {
            // Dados para o template de email
            $templateData = [
                'code' => $code,
                'name' => $user->name ?? 'Usuário',
                'email' => $user->email,
                'site_name' => config('app.name'),
                'year' => date('Y')
            ];
            
            // Log para debug
            Log::info('Enviando código de verificação de email', [
                'user_id' => $user->id,
                'email' => substr($user->email, 0, 3) . '***' . strstr($user->email, '@'),
                'code' => $code
            ]);
            
            // Configurar remetente válido para o Brevo
            $siteUrl = config('app.url');
            $domain = env('BREVO_DEFAULT_DOMAIN') ?: parse_url($siteUrl, PHP_URL_HOST);
            
            $sender = [
                'email' => config('mail.from.address') ?: "noreply@{$domain}",
                'name' => config('mail.from.name') ?: config('app.name')
            ];
            
            // Validar o email do remetente
            if (!filter_var($sender['email'], FILTER_VALIDATE_EMAIL)) {
                // Tentar usar um email de remetente padrão com o domínio configurado
                $sender['email'] = "noreply@{$domain}";
                
                Log::info('Email do remetente inválido, usando padrão', [
                    'email' => $sender['email']
                ]);
            }
            
            // Registrar informações de envio
            Log::info('Detalhes do envio de email', [
                'sender_email' => $sender['email'],
                'sender_name' => $sender['name'],
                'recipient' => $user->email,
                'template' => 'verification-code',
                'brevo_domain' => $domain
            ]);
            
            // Enviar email usando o slug do template de verificação com o serviço Brevo
            $enviado = $this->brevoService->enviarEmailTemplate(
                [
                    'email' => $user->email,
                    'name' => $user->name ?? 'Usuário'
                ],
                'verification-code',  // Slug do template que será mapeado para o ID através das variáveis de ambiente
                $templateData,
                $sender
            );
            
            if ($enviado) {
                // Adicionando mensagem para o usuário verificar a pasta de spam/lixo eletrônico
                return response()->json([
                    'success' => true,
                    'message' => 'Código de verificação enviado para seu email! Se não encontrar, verifique também sua pasta de spam ou lixo eletrônico.'
                ]);
            } else {
                // Log do erro
                Log::error('Falha no envio do código por Brevo, tentando método alternativo');
                
                // Tentar enviar diretamente pelo mailer do Laravel
                try {
                    // Enviar email básico com o código
                    Mail::raw("Seu código de verificação é: $code", function ($message) use ($user, $sender, $code) {
                        $message->to($user->email, $user->name ?? 'Usuário')
                                ->subject('Seu código de verificação - ' . config('app.name'))
                                ->from($sender['email'], $sender['name']);
                    });
                    
                    Log::info('Email enviado usando método alternativo');
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Código de verificação enviado para seu email como mensagem simples! Se não encontrar, verifique sua pasta de spam ou lixo eletrônico.'
                    ]);
                } catch (\Exception $e) {
                    Log::error('Falha no envio alternativo', [
                        'error' => $e->getMessage()
                    ]);
                    
                    return response()->json([
                        'success' => false,
                        'message' => 'Erro ao enviar código de verificação. Por favor, tente novamente mais tarde ou contate o suporte.'
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Log do erro
            Log::error('Erro ao enviar código de verificação: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'email' => $user->email,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar código de verificação. Por favor, tente novamente mais tarde ou contate o suporte.'
            ]);
        }
    }
    
    // Gera e envia código de verificação por WhatsApp
    public function sendPhoneCode(Request $request)
    {
        $user = auth()->user();
        
        // Gerar código aleatório de 5 dígitos
        $code = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
        
        // Salvar código no banco de dados
        VerificationCode::updateOrCreate(
            ['user_id' => $user->id, 'type' => 'phone'],
            ['code' => $code, 'expires_at' => now()->addMinutes(10)]
        );
        
        try {
            // Preparar o número de telefone
            $phoneNumber = preg_replace('/[^0-9]/', '', $user->phone);
            
            // Adicionar o código do país se não estiver presente
            if (!str_starts_with($phoneNumber, '55')) {
                $phoneNumber = '55' . $phoneNumber;
            }
            
            // Preparar a mensagem para o WhatsApp
            $message = "Seu código de verificação é: *$code*\n\n";
            $message .= "Use este código para verificar seu número de telefone em " . config('app.name') . ".";
            
            // Usar o serviço de WhatsApp existente para enviar a mensagem
            $whatsappService = app(\App\Services\WhatsappService::class);
            $result = $whatsappService->sendCustomMessage($user, $phoneNumber, $message);
            
            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Código de verificação enviado para seu WhatsApp!'
                ]);
            } else {
                // Registrar o erro
                \Log::error("Falha ao enviar código via WhatsApp para o telefone $phoneNumber");
                
                return response()->json([
                    'success' => false,
                    'message' => 'Não foi possível enviar o código via WhatsApp. Por favor, tente novamente mais tarde.'
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar código de verificação via WhatsApp: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar código de verificação: ' . $e->getMessage()
            ]);
        }
    }
    
    // Verifica o código de email
    public function verifyEmailCode(Request $request)
    {
        return $this->verifyCode($request, 'email');
    }
    
    // Verifica o código de telefone
    public function verifyPhoneCode(Request $request)
    {
        return $this->verifyCode($request, 'phone');
    }
    
    // Método genérico para verificação de código
    private function verifyCode(Request $request, $type)
    {
        $request->validate([
            'code' => 'required|string|size:5'
        ]);
        
        $user = auth()->user();
        $code = $request->input('code');
        
        // Buscar código na base de dados
        $verificationCode = VerificationCode::where('user_id', $user->id)
            ->where('type', $type)
            ->where('code', $code)
            ->where('expires_at', '>', now())
            ->first();
        
        if (!$verificationCode) {
            return response()->json([
                'success' => false,
                'message' => 'Código inválido ou expirado.'
            ]);
        }
        
        // Atualizar status de verificação do usuário
        if ($type === 'email') {
            $user->email_verified_at = now();
        } else if ($type === 'phone') {
            $user->phone_verified_at = now();
        }
        
        $user->save();
        
        // Remover o código usado
        $verificationCode->delete();
        
        return response()->json([
            'success' => true,
            'message' => $type === 'email' ? 'Email verificado com sucesso!' : 'Telefone verificado com sucesso!'
        ]);
    }
} 