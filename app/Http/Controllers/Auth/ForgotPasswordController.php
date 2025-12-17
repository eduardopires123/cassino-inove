<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\BrevoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    protected $brevoService;

    public function __construct(BrevoService $brevoService)
    {
        $this->middleware('guest');
        $this->brevoService = $brevoService;
    }

    /**
     * Método padrão do Laravel - redirecionamos para o customizado
     */
    public function sendResetLinkEmail(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('=== MÉTODO PADRÃO INTERCEPTADO ===', [
            'method' => 'sendResetLinkEmail',
            'redirecting_to' => 'sendResetLinkEmailCustom'
        ]);
        
        return $this->sendResetLinkEmailCustom($request);
    }

    /**
     * Enviar email de recuperação de senha usando Brevo
     */
    public function sendResetLinkEmailCustom(Request $request)
    {
        // Log de debug para verificar se o método está sendo executado
        \Illuminate\Support\Facades\Log::info('=== MÉTODO CUSTOMIZADO EXECUTADO ===', [
            'method' => 'sendResetLinkEmailCustom',
            'input' => $request->all()
        ]);

        // Aceitar tanto 'email' quanto 'identifier'
        $identifier = $request->input('email') ?: $request->input('identifier');
        
        if (!$identifier) {
            $request->validate(['email' => 'required']);
        }

        // Verificar se é um CPF ou email
        $isCpf = preg_match('/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', $identifier) || 
                 preg_match('/^\d{11}$/', $identifier);

        if ($isCpf) {
            // Formatar CPF se necessário (remover pontos e traços)
            $cpf = preg_replace('/[^0-9]/', '', $identifier);
            
            // Validar se o CPF tem 11 dígitos
            if (strlen($cpf) !== 11) {
                \Illuminate\Support\Facades\Log::info('CPF inválido - não tem 11 dígitos', [
                    'identifier' => $identifier,
                    'cpf_limpo' => $cpf,
                    'tamanho' => strlen($cpf)
                ]);
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'CPF deve conter 11 dígitos.'
                    ], 422);
                }
                return back()->withErrors(['identifier' => 'CPF deve conter 11 dígitos.']);
            }
            
            // Buscar usuário pelo CPF (sem formatação e com formatação)
            $formattedCpf = substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
            
            \Illuminate\Support\Facades\Log::info('Buscando usuário por CPF', [
                'cpf_limpo' => $cpf,
                'cpf_formatado' => $formattedCpf
            ]);
            
            $user = User::where('cpf', $cpf)
                       ->orWhere('cpf', $formattedCpf)
                       ->first();
            
            \Illuminate\Support\Facades\Log::info('Resultado da busca por CPF', [
                'usuario_encontrado' => $user ? true : false,
                'user_id' => $user ? $user->id : null,
                'user_email' => $user ? $user->email : null
            ]);
            
            if (!$user) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Não encontramos um usuário com este CPF.'
                    ], 422);
                }
                return back()->withErrors(['identifier' => 'Não encontramos um usuário com este CPF.']);
            }
            
            $email = $user->email;
        } else {
            // Assumir que é um email
            $email = $identifier;
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Não encontramos um usuário com este e-mail.'
                    ], 422);
                }
                return back()->withErrors(['identifier' => 'Não encontramos um usuário com este e-mail.']);
            }
        }

        // Verificar se o usuário tem email válido
        if (!$user->email) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Este usuário não possui um e-mail cadastrado.'
                ], 422);
            }
            return back()->withErrors(['identifier' => 'Este usuário não possui um e-mail cadastrado.']);
        }

        // Gerar token de recuperação
        $token = Str::random(64);
        
        // Armazenar o token no banco
        DB::table('password_resets')->where('email', $email)->delete();
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => Hash::make($token),
            'created_at' => Carbon::now()
        ]);

        // Montar a URL de reset
        $resetUrl = url(route('password.reset', [
            'token' => $token,
            'email' => $email,
        ], false));

        // Dados para o template de email
        $templateData = [
            'nome' => $user->name,
            'link_reset' => $resetUrl,
            'site_name' => config('app.name'),
            'expiracao' => config('auth.passwords.users.expire', 60) . ' minutos',
            'year' => date('Y')
        ];

        // Log para debug
        \Illuminate\Support\Facades\Log::info('Enviando email de recuperação via Brevo', [
            'email' => $email,
            'identifier_used' => $identifier,
            'is_cpf' => $isCpf,
            'template_data' => $templateData
        ]);

        // Enviar o email usando o template local pelo slug
        $result = $this->brevoService->enviarEmailTemplate(
            [
                'email' => $email,
                'name' => $user->name
            ],
            'password-reset', // Slug do template local
            $templateData
        );

        \Illuminate\Support\Facades\Log::info('Resultado do envio de email', [
            'resultado' => $result ? 'sucesso' : 'falha'
        ]);

        if ($result) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Enviamos o link de recuperação de senha para o seu e-mail!'
                ]);
            }
            return back()->with('status', 'Enviamos o link de recuperação de senha para o seu e-mail!');
        } else {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Não foi possível enviar o e-mail de recuperação. Por favor, tente novamente mais tarde.'
                ], 500);
            }
            return back()->withErrors(['email' => 'Não foi possível enviar o e-mail de recuperação. Por favor, tente novamente mais tarde.']);
        }
    }
} 