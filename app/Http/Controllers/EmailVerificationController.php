<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\VerificationToken;
use Illuminate\Support\Facades\Log;
use App\Services\BrevoService;

class EmailVerificationController extends Controller
{
    /**
     * Verifica o email do usuário com o token fornecido
     *
     * @param int $userId
     * @param string $token
     * @return \Illuminate\Http\Response
     */
    public function verify($userId, $token)
    {
        $user = User::findOrFail($userId);
        $verificationToken = VerificationToken::findValidToken($userId, $token, 'email');

        if (!$verificationToken) {
            return redirect()->route('home')->with('error', 'Link de verificação inválido ou expirado.');
        }

        // Verificar se há um usuário logado
        $currentUser = auth()->user();
        
        // Se há um usuário logado e não é o dono da conta, deslogar
        if ($currentUser && $currentUser->id != $userId) {
            auth()->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
        }
        
        // Se não há usuário logado ou é um usuário diferente, fazer login automático
        if (!$currentUser || $currentUser->id != $userId) {
            auth()->login($user);
        }

        // Marcar o token como verificado
        $verificationToken->markAsVerified();
        
        // Atualizar o usuário para marcá-lo como verificado
        if (!$user->email_verified_at) {
            $user->email_verified_at = now();
            $user->save();
        }

        // Redirecionar para a página inicial com mensagem de sucesso
        return redirect()->route('home')->with('email_verified', true);
    }

    /**
     * Reenviar o link de verificação para o usuário
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Você precisa estar logado para reenviar o link de verificação.');
        }
        
        if ($user->email_verified_at) {
            return redirect()->route('home')->with('info', 'Seu email já foi verificado.');
        }

        try {
            // Criar um novo token
            $token = VerificationToken::createToken($user, 'email', 24);
            
            // Enviar o email de verificação
            $brevoService = app(BrevoService::class);
            $result = $brevoService->enviarEmailTemplate(
                $user->email,
                'welcome',
                [
                    'user' => $user,
                    'verificationToken' => $token->token
                ]
            );

            if ($result) {
                return redirect()->back()->with('success', 'Link de verificação enviado com sucesso para seu email.');
            } else {
                Log::error('Falha ao enviar email de verificação', ['user_id' => $user->id]);
                return redirect()->back()->with('error', 'Não foi possível enviar o email de verificação. Por favor, tente novamente mais tarde.');
            }
        } catch (\Exception $e) {
            Log::error('Erro ao reenviar verificação de email', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Ocorreu um erro ao processar sua solicitação. Por favor, tente novamente mais tarde.');
        }
    }
    
    /**
     * Exibe uma página informando que o email precisa ser verificado
     *
     * @return \Illuminate\Http\Response
     */
    public function notice()
    {
        return view('auth.verify-email');
    }
}
