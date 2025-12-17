<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    
    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
        if ($token === null) {
            Log::warning('Token nulo recebido em showResetForm');
            return redirect()->route('password.request')
                ->with('error', 'Token de redefinição ausente. Por favor, solicite um novo link de redefinição de senha.');
        }

        $email = $request->email;
        
        Log::info('Tentativa de redefinição de senha', [
            'token' => substr($token, 0, 10) . '...',
            'email' => $email ? substr($email, 0, 3) . '***' . strstr($email, '@') : 'não fornecido'
        ]);
        
        // Verifica se o token é válido
        $tokenValid = false;
        $tokenUsed = false;
        
        if ($email) {
            $record = DB::table('password_resets')
                ->where('email', $email)
                ->first();
            
            if ($record) {
                // Verifica se o token está dentro do período de validade (60 minutos padrão)
                $createdAt = Carbon::parse($record->created_at);
                $expiresAt = $createdAt->addMinutes(config('auth.passwords.users.expire', 60));
                $now = Carbon::now();
                
                Log::info('Verificação de token', [
                    'token_criado_em' => $createdAt->format('Y-m-d H:i:s'),
                    'token_expira_em' => $expiresAt->format('Y-m-d H:i:s'),
                    'agora' => $now->format('Y-m-d H:i:s'),
                    'expirado' => $now->gt($expiresAt) ? 'Sim' : 'Não'
                ]);
                
                $tokenValid = $now->lt($expiresAt) && Hash::check($token, $record->token);
                
                Log::info('Resultado da validação', [
                    'token_valido' => $tokenValid ? 'Sim' : 'Não',
                    'hash_check' => Hash::check($token, $record->token) ? 'Passou' : 'Falhou'
                ]);
            } else {
                // Token não encontrado - pode ter sido usado/deletado
                $tokenUsed = true;
                Log::warning('Token não encontrado - possivelmente já foi utilizado', [
                    'email' => substr($email, 0, 3) . '***' . strstr($email, '@')
                ]);
            }
        } else {
            Log::warning('Email não fornecido na solicitação de redefinição');
        }
        
        // Se o token foi usado (não existe mais na tabela), redireciona para home com erro
        if ($tokenUsed) {
            Log::warning('Token já foi utilizado: redirecionando para home page');
            return redirect('/')
                ->with('error', 'Este link de redefinição de senha já foi utilizado. Por favor, solicite um novo link se necessário.');
        }
        
        if (!$tokenValid) {
            // Se o token não for válido, redireciona com uma mensagem
            Log::warning('Token inválido ou expirado: redirecionando para página de solicitação');
            return redirect()
                ->route('password.request')
                ->with('error', 'Este token de redefinição de senha é inválido ou já expirou. Por favor, solicite um novo link de redefinição.');
        }
        
        Log::info('Token validado com sucesso, retornando página de reset');
        return view('auth.passwords.reset', [
            'token' => $token,
            'email' => $email,
            'tokenValid' => true
        ]);
    }
    
    /**
     * Override do método reset para garantir validação consistente com showResetForm
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request)
    {
        $request->validate($this->rules(), $this->validationErrorMessages());

        $email = $request->email;
        $token = $request->token;
        
        Log::info('Processando redefinição de senha', [
            'token' => substr($token, 0, 10) . '...',
            'email' => substr($email, 0, 3) . '***' . strstr($email, '@'),
            'token_route' => $request->route('token'), // Adicional para debug
            'full_url' => $request->fullUrl(), // Adicional para debug
            'all_inputs' => $request->except(['password', 'password_confirmation']) // Adicional para debug
        ]);
        
        // Verificação manual do token
        $record = DB::table('password_resets')
            ->where('email', $email)
            ->first();
            
        if (!$record) {
            Log::warning('Token não encontrado para o email fornecido na redefinição de senha');
            return back()->withErrors(['email' => 'Não foi possível encontrar um token válido para este email.']);
        }
        
        // Verifica se o token é válido
        $createdAt = Carbon::parse($record->created_at);
        $expiresAt = $createdAt->addMinutes(config('auth.passwords.users.expire', 60));
        $now = Carbon::now();
        
        $isValid = $now->lt($expiresAt) && Hash::check($token, $record->token);
        
        Log::info('Verificação do token na redefinição', [
            'token_valido' => $isValid ? 'Sim' : 'Não',
            'hash_check' => Hash::check($token, $record->token) ? 'Passou' : 'Falhou',
            'expirado' => $now->gt($expiresAt) ? 'Sim' : 'Não'
        ]);
        
        if (!$isValid) {
            Log::warning('Token inválido ou expirado na redefinição de senha');
            return back()->withErrors(['email' => 'Este token de redefinição de senha é inválido.']);
        }

        // Se a validação manual passou com sucesso, vamos implementar a redefinição diretamente
        // ao invés de usar o broker, que pode estar causando problemas
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            Log::warning('Usuário não encontrado com o email fornecido');
            return back()->withErrors(['email' => 'Não foi possível encontrar um usuário com este email.']);
        }
        
        try {
            // Atualizar a senha diretamente
            $user->password = Hash::make($request->password);
            
            // Verificar se o modelo tem a coluna remember_token antes de tentar defini-la
            if (Schema::hasColumn('users', 'remember_token')) {
                $user->setRememberToken(Str::random(60));
            }
            
            $user->save();
            
            // Remover o token usado
            DB::table('password_resets')
                ->where('email', $email)
                ->delete();
            
            Log::info('Senha redefinida com sucesso', [
                'user_id' => $user->id,
                'email' => substr($user->email, 0, 3) . '***' . strstr($user->email, '@')
            ]);
            
            // Disparar evento de senha redefinida
            event(new \Illuminate\Auth\Events\PasswordReset($user));
            
            // Autenticar o usuário
            Auth::login($user);
            
            return redirect($this->redirectPath())
                ->with('status', 'Sua senha foi redefinida com sucesso!');
                
        } catch (\Exception $e) {
            Log::error('Erro ao redefinir senha: ' . $e->getMessage());
            
            // Tentar uma abordagem mais simples (apenas atualizar a senha)
            try {
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['password' => Hash::make($request->password)]);
                
                // Remover o token usado
                DB::table('password_resets')
                    ->where('email', $email)
                    ->delete();
                
                Log::info('Senha redefinida com sucesso (método alternativo)');
                
                // Autenticar o usuário
                Auth::login($user);
                
                return redirect($this->redirectPath())
                    ->with('status', 'Sua senha foi redefinida com sucesso!');
            } catch (\Exception $e2) {
                Log::error('Falha na abordagem alternativa: ' . $e2->getMessage());
                return back()->withErrors(['email' => 'Erro ao redefinir senha. Por favor, tente novamente mais tarde.']);
            }
        }
    }
} 