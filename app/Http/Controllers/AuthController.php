<?php

namespace App\Http\Controllers;

use App\Models\Sessions;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use App\Models\Wallet;
use App\Models\Affiliates;

class AuthController extends Controller
{
    /**
     * Mostrar formulário de login.
     */
    public function showLoginForm()
    {
        // Redirecionando para a home que já deve ter o modal de login
        return redirect()->route('home');

        // Ou retorne a view que realmente existe no seu sistema
        // return view('auth.login-modal');
    }

    /**
     * Processar tentativa de login.
     */
    public function login(Request $request)
    {

        try {
            // Verifica se é um e-mail ou CPF
            $campo = filter_var($request->email, FILTER_VALIDATE_EMAIL)
                ? 'email'
                : 'cpf';

            if ($campo === 'email')
            {
                $emailOuCpf = strtolower(trim($request->email));
            }else{
                $emailOuCpf =  preg_replace('/[^0-9]/', '', $request->email);
            }

            // Validação tradicional do Laravel
            $request->validate([
                'email' => 'required',
                'password' => 'required',
            ]);

            $remember = $request->boolean('remember');

            // Credenciais para tentativa de login
            $credentials = [
                $campo => $emailOuCpf,
                'password' => $request->password
            ];

            $user = User::where($campo, $emailOuCpf)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'As credenciais fornecidas não correspondem aos nossos registros.',
                ], 401);
            }

            if ($user->banned == 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sua conta encontra-se bloqueada, entre em contato com o suporte!',
                ], 403);
            }

            Sessions::Where('user_id', $user->id)->delete();

            if (Auth::attempt($credentials, $remember)) {
                session()->forget('current_tab_id');

                $request->session()->regenerate();

                $user = Auth::user();
                $user->logged_in = 1;
                $user->playing = 0;
                $user->last_login = now();
                $user->save();

                session(['login_timestamp' => time()]);

                // Após login bem-sucedido, definir idioma
                // Definir idioma do usuário, se existir
                $userLanguage = $user->language ?? 'pt_BR';

                Session::put('locale', $userLanguage);
                App::setLocale($userLanguage);
                Cookie::queue('user_locale', $userLanguage, 525600); // Cookie com duração de 1 ano

                // Verificar se é tentativa de login no painel administrativo
                if ($request->has('admin') && $request->admin >= 1) {
                    // Verificar se o usuário é realmente administrador
                    if ($user->is_admin >= 1) {
                        if ($request->wantsJson()) {
                            return response()->json([
                                'success' => true,
                                'redirect' => url('/adm'),
                                'message' => 'Login realizado com sucesso!',
                                'showLoading' => true,
                                'user' => $user
                            ]);
                        }

                        return redirect()->intended(url('/adm'));
                    } else {
                        // Deslogar o usuário se ele não tem permissão de administrador
                        Auth::logout();
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();

                        if ($request->wantsJson()) {
                            return response()->json([
                                'success' => false,
                                'message' => 'Você não tem permissão para acessar o painel administrativo.',
                            ], 403);
                        }

                        return back()
                            ->withErrors([
                                'email' => 'Você não tem permissão para acessar o painel administrativo.',
                            ]);
                    }
                }

                return response()->json([
                    'success' => true,
                    'redirect' => '',
                    'message' => 'Login realizado com sucesso!',
                    'showLoading' => true,
                    'user' => $user
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'As credenciais fornecidas não correspondem aos nossos registros.',
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocorreu um erro ao processar seu login. Detalhes: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mostrar formulário de registro.
     */
    public function showRegistrationForm()
    {
        return view('auth.register-modal');
    }

    /**
     * Processar registro de novo usuário.
     */
    public function register(Request $request)
    {
        try {
            // Extrair CPF antes da validação para verificar duplicatas
            $cpf = preg_replace('/[^0-9]/', '', $request->cpf);

            // Verificação manual de email e CPF duplicados
            if ($cpf) {
                // Verificar se o CPF já existe (em qualquer formato)
                $existingCpf = User::where(function($query) use ($cpf) {
                    $query->where('cpf', $cpf)
                        ->orWhere('pix', $cpf);

                    // Verificar também com formato de pontuação
                    if (strlen($cpf) === 11) {
                        $formattedCpf = substr($cpf, 0, 3) . '.' .
                            substr($cpf, 3, 3) . '.' .
                            substr($cpf, 6, 3) . '-' .
                            substr($cpf, 9, 2);

                        $query->orWhere('cpf', $formattedCpf)
                            ->orWhere('pix', $formattedCpf);
                    }
                })->first();

                if ($existingCpf) {
                    if ($request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'CPF já cadastrado',
                            'errors' => [
                                'pix' => ['Este CPF já está cadastrado no sistema. Por favor, faça login ou use outro CPF.']
                            ]
                        ], 422);
                    }

                    return redirect()->back()
                        ->withErrors(['pix' => 'Este CPF já está cadastrado no sistema. Por favor, faça login ou use outro CPF.'])
                        ->withInput($request->except('password'));
                }
            }

            // Verificar duplicação de email
            if ($request->email) {
                $existingEmail = User::where('email', $request->email)->first();

                if ($existingEmail) {
                    if ($request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Email já cadastrado',
                            'errors' => [
                                'email' => ['Este email já está cadastrado no sistema. Por favor, faça login ou use outro email.']
                            ]
                        ], 422);
                    }

                    return redirect()->back()
                        ->withErrors(['email' => 'Este email já está cadastrado no sistema. Por favor, faça login ou use outro email.'])
                        ->withInput($request->except('password'));
                }
            }

            // Validação normal após verificação de duplicatas
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:191|unique:users',
                'password' => 'required|string|min:8',
                'phone' => 'nullable|string|max:20',
                'cpf' => 'required|string|max:20', // Validação do CPF
                'terms_agreement' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erro de validação',
                        'errors' => $validator->errors()
                    ], 422);
                }

                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput($request->except('password'));
            }

            // Formatando o nome a partir do email (opcional)
            $name = $request->name ?? explode('@', $request->email)[0];

            // Garantir que o CPF esteja sem formatação
            $cleanedCpf = $cpf ?: preg_replace('/[^0-9]/', '', $request->pix);

            $ref = $request->ref ?? 0;

            if (!User::find($ref)) {
                $ref = 0;
            }

            $user = User::create([
                'name' => $name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'cpf' => $cleanedCpf,
                'pix' => $cleanedCpf,
                'nascimento' => $request->nascimento,
                'status' => 'active',
                'is_admin' => 0,
                'is_affiliate' => 0,
                'logged_in' => 0,
                'banned' => 0,
                'playing' => 0,
                'played' => 0,
                'image' => 'img/avatar/15.png', // Avatar padrão
                'inviter' => $ref,
            ]);

            // Criar carteira para o novo usuário
            Wallet::create([
                'user_id' => $user->id,
                'balance' => 0,
                'balance_bonus' => 0,
                'balance_bonus_rollover' => 0,
                'balance_bonus_rollover_used' => 0,
                'hide_balance' => 0,
                'hide_balancerefer' => 0,
                'total_bet' => 0,
                'total_won' => 0,
                'total_lose' => 0,
                'last_won' => 0,
                'last_lose' => 0,
                'referPercent' => 0,
                'refer_rewards' => 0,
                'coin' => 0,
            ]);

            if ($ref !== 0) {
                Affiliates::Create([
                    'user_id' => $user->id,
                    'inviter' => $request->ref,
                    'status' => 1,
                ]);
            }

            Auth::login($user);

            // Após registro bem-sucedido
            if (Auth::check()) {
                $user = Auth::user();

                // Salvar o idioma preferido na tabela de usuário
                $user->language = 'pt_BR';
                $user->save();

                Session::put('locale', 'pt_BR');
                App::setLocale('pt_BR');
                Cookie::queue('user_locale', 'pt_BR', 525600); // Cookie com duração de 1 ano
            }

            // Chamar o método registered para enviar email de boas-vindas
            $this->registered($request, $user);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'redirect' => route('home'),
                    'message' => 'Registro realizado com sucesso!'
                ]);
            }

            return redirect()->route('home');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ocorreu um erro ao processar seu registro. Detalhes: ' . $e->getMessage(),
                ], 500);
            }

            return back()
                ->withErrors([
                    'error' => 'Ocorreu um erro ao processar seu registro. Tente novamente mais tarde.',
                ]);
        }
    }

    protected function registered(Request $request, $user)
    {
        // Autentica o usuário após o registro
        Auth::login($user);

        try {
            // Criar token de verificação
            $token = \App\Models\VerificationToken::createToken($user, 'email', 24);

            // Montar URL de verificação
            $verificationUrl = url('/verify-email/' . $user->id . '/' . $token->token);

            // Dados para o template de email
            $templateData = [
                'nome' => $user->name,
                'email' => $user->email,
                'site_name' => config('app.name'),
                'link_verificacao' => $verificationUrl,
                'logo_url' => config('app.url') . '/img/logo/default.png',
                'facebook_url' => '#',
                'instagram_url' => '#',
                'twitter_url' => '#',
                'social_facebook_icon' => config('app.url') . '/img/social/facebook.png',
                'social_instagram_icon' => config('app.url') . '/img/social/instagram.png',
                'social_twitter_icon' => config('app.url') . '/img/social/twitter.png',
                'termos_url' => url('/termos'),
                'privacidade_url' => url('/politica-de-privacidade'),
                'year' => date('Y')
            ];

            // Enviar email de boas-vindas usando o BrevoService
            $brevoService = app(\App\Services\BrevoService::class);
            $brevoService->enviarEmailTemplate(
                $user->email, // Passar apenas o email como string
                'welcome', // Slug do template de boas-vindas
                $templateData
            );
        } catch (\Exception $e) {
            // Falha silenciosa no envio de email
        }

        return redirect()->intended(route('home'));
    }

    /**
     * Processar logout.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        $user->logged_in = 0;
        $user->save();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true
            ]);
        }

        // Redirecionar para a página inicial
        return redirect('/');
    }

    /**
     * Método otimizado para logout via AJAX
     */
    public function logoutAjax(Request $request)
    {
        try {
            // Registrar o usuário atual para detalhes de log
            $userId = Auth::id();

            $user = Auth::user();
            $user->logged_in = 0;
            $user->save();

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            \Log::info('Logout AJAX realizado com sucesso', ['user_id' => $userId]);

            return response()->json([
                'success' => true,
                'message' => 'Você saiu da sua conta!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro no logout AJAX', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar logout: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Processar validação de CPF.
     */
    public function validateCpf(Request $request)
    {
        $cpf = $request->cpf;
        $exists = User::where('cpf', $cpf)->exists();

        return response()->json(['valid' => !$exists]);
    }

    /**
     * Mostrar formulário de "esqueci minha senha".
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Enviar link de redefinição de senha.
     */
    public function sendResetLink(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('=== AUTHCONTROLLER SENDRESETLINK INTERCEPTADO ===', [
            'method' => 'AuthController@sendResetLink',
            'redirecting_to' => 'ForgotPasswordController@sendResetLinkEmailCustom'
        ]);
        
        // Redirecionar para nosso método customizado
        $forgotPasswordController = app(\App\Http\Controllers\Auth\ForgotPasswordController::class);
        return $forgotPasswordController->sendResetLinkEmailCustom($request);
    }

    /**
     * Mostrar formulário de redefinição de senha.
     */
    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    /**
     * Redefinir a senha do usuário.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        \Illuminate\Support\Facades\Log::info('Tentativa de redefinição de senha', [
            'token' => substr($request->token, 0, 10) . '...',
            'email' => substr($request->email, 0, 3) . '***' . strstr($request->email, '@')
        ]);

        // Verificar se o token existe e é válido
        $tokenRecord = \Illuminate\Support\Facades\DB::table('password_resets')
            ->where('email', $request->email)
            ->first();

        if (!$tokenRecord) {
            \Illuminate\Support\Facades\Log::warning('Nenhum registro de token encontrado para o email fornecido', [
                'email' => substr($request->email, 0, 3) . '***' . strstr($request->email, '@')
            ]);
            return back()->withErrors(['email' => 'Token não encontrado para este email.']);
        }

        // Verificar o hash do token manualmente
        $isValid = \Illuminate\Support\Facades\Hash::check($request->token, $tokenRecord->token);
        \Illuminate\Support\Facades\Log::info('Verificação manual do token', [
            'token_valido' => $isValid ? 'Sim' : 'Não'
        ]);

        if (!$isValid) {
            \Illuminate\Support\Facades\Log::warning('Token não corresponde ao hash armazenado');
            return back()->withErrors(['email' => 'Este token de redefinição de senha é inválido.']);
        }

        // Verificar expiração
        $createdAt = \Carbon\Carbon::parse($tokenRecord->created_at);
        $expiresAt = $createdAt->addMinutes(config('auth.passwords.users.expire', 60));
        $now = \Carbon\Carbon::now();

        if ($now->gt($expiresAt)) {
            \Illuminate\Support\Facades\Log::warning('Token expirado', [
                'criado_em' => $createdAt->format('Y-m-d H:i:s'),
                'expira_em' => $expiresAt->format('Y-m-d H:i:s'),
                'agora' => $now->format('Y-m-d H:i:s')
            ]);
            return back()->withErrors(['email' => 'Este token de redefinição de senha já expirou.']);
        }

        // Buscar o usuário
        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            \Illuminate\Support\Facades\Log::warning('Usuário não encontrado com o email fornecido');
            return back()->withErrors(['email' => 'Não foi possível encontrar um usuário com este endereço de email.']);
        }

        // Atualizar a senha manualmente
        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->setRememberToken(\Illuminate\Support\Str::random(60));
        $user->save();

        // Remover o token usado
        \Illuminate\Support\Facades\DB::table('password_resets')
            ->where('email', $request->email)
            ->delete();

        event(new PasswordReset($user));

        // Fazer login do usuário
        \Illuminate\Support\Facades\Auth::login($user);

        return redirect()->route('home')->with('status', 'Sua senha foi redefinida com sucesso!');
    }

    /**
     * Autenticação via Google
     */
    public function redirectToGoogle()
    {
        return \Laravel\Socialite\Facades\Socialite::driver('google')->redirect();
    }

    /**
     * Callback do Google
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = \Laravel\Socialite\Facades\Socialite::driver('google')->user();

            // Verificar se o usuário já existe pelo google_id
            $user = User::where('google_id', $googleUser->getId())->first();

            // Se não existir pelo google_id, verificar pelo email
            if (!$user && $googleUser->getEmail()) {
                $user = User::where('email', $googleUser->getEmail())->first();

                // Se encontrar o usuário pelo email, atualizar o google_id
                if ($user) {
                    $user->google_id = $googleUser->getId();
                    $user->save();
                }
            }

            // Se não existir, criar um novo usuário
            if (!$user) {
                // Criando nome de usuário com base no nome do Google
                $name = $googleUser->getName() ?? explode('@', $googleUser->getEmail())[0];
                $email = $googleUser->getEmail();

                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(24)),
                    'google_id' => $googleUser->getId(),
                    'status' => 'active',
                    'is_admin' => 0,
                    'is_affiliate' => 0,
                    'logged_in' => 0,
                    'banned' => 0,
                    'playing' => 0,
                    'played' => 0,
                    'image' => $googleUser->getAvatar(),
                ]);

                // Criar wallet para o novo usuário
                Wallet::create([
                    'user_id' => $user->id,
                    'balance' => 0,
                    'balance_bonus' => 0,
                    'balance_bonus_rollover' => 0,
                    'balance_bonus_rollover_used' => 0,
                    'hide_balance' => 0,
                    'hide_balancerefer' => 0,
                    'total_bet' => 0,
                    'total_won' => 0,
                    'total_lose' => 0,
                    'last_won' => 0,
                    'last_lose' => 0,
                    'referPercent' => 0,
                    'refer_rewards' => 0,
                    'coin' => 0
                ]);
            } else {
                // Verificar se o usuário já tem wallet
                if (!$user->wallet) {
                    // Criar wallet para o usuário existente que não tem
                    Wallet::create([
                        'user_id' => $user->id,
                        'balance' => 0,
                        'balance_bonus' => 0,
                        'balance_bonus_rollover' => 0,
                        'balance_bonus_rollover_used' => 0,
                        'hide_balance' => 0,
                        'hide_balancerefer' => 0,
                        'total_bet' => 0,
                        'total_won' => 0,
                        'total_lose' => 0,
                        'last_won' => 0,
                        'last_lose' => 0,
                        'referPercent' => 0,
                        'refer_rewards' => 0,
                        'coin' => 0
                    ]);
                }
            }

            // Fazer login
            \Illuminate\Support\Facades\Auth::login($user);

            $user->logged_in = 1;
            $user->last_login = now();
            $user->save();

            // Configurar idioma do usuário
            $userLanguage = $user->language ?? 'pt_BR';
            \Illuminate\Support\Facades\Session::put('locale', $userLanguage);
            \Illuminate\Support\Facades\App::setLocale($userLanguage);
            \Illuminate\Support\Facades\Cookie::queue('user_locale', $userLanguage, 525600);

            return redirect()->intended(route('home'));

        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Erro ao realizar login com Google: ' . $e->getMessage());
        }
    }

    /**
     * Autenticação via Twitch
     */
    public function redirectToTwitch()
    {
        return \Laravel\Socialite\Facades\Socialite::driver('twitch')->redirect();
    }

    /**
     * Callback da Twitch
     */
    public function handleTwitchCallback()
    {
        try {
            $twitchUser = \Laravel\Socialite\Facades\Socialite::driver('twitch')->user();

            // Verificar se o usuário já existe pelo twitch_id
            $user = User::where('twitch_id', $twitchUser->getId())->first();

            // Se não existir pelo twitch_id, verificar pelo email
            if (!$user && $twitchUser->getEmail()) {
                $user = User::where('email', $twitchUser->getEmail())->first();

                // Se encontrar o usuário pelo email, atualizar o twitch_id
                if ($user) {
                    $user->twitch_id = $twitchUser->getId();
                    $user->save();
                }
            }

            // Se não existir, criar um novo usuário
            if (!$user) {
                // Criando nome de usuário com base no nome do Twitch
                $name = $twitchUser->getName() ?? $twitchUser->getNickname();
                $email = $twitchUser->getEmail();

                // Se não tiver email, criar um email temporário
                if (!$email) {
                    $email = $twitchUser->getId() . '@twitch.user';
                }

                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(24)),
                    'twitch_id' => $twitchUser->getId(),
                    'status' => 'active',
                    'is_admin' => 0,
                    'is_affiliate' => 0,
                    'logged_in' => 0,
                    'banned' => 0,
                    'playing' => 0,
                    'played' => 0,
                    'image' => $twitchUser->getAvatar(),
                ]);

                // Criar wallet para o novo usuário
                Wallet::create([
                    'user_id' => $user->id,
                    'balance' => 0,
                    'balance_bonus' => 0,
                    'balance_bonus_rollover' => 0,
                    'balance_bonus_rollover_used' => 0,
                    'hide_balance' => 0,
                    'hide_balancerefer' => 0,
                    'total_bet' => 0,
                    'total_won' => 0,
                    'total_lose' => 0,
                    'last_won' => 0,
                    'last_lose' => 0,
                    'referPercent' => 0,
                    'refer_rewards' => 0,
                    'coin' => 0
                ]);
            } else {
                // Verificar se o usuário já tem wallet
                if (!$user->wallet) {
                    // Criar wallet para o usuário existente que não tem
                    Wallet::create([
                        'user_id' => $user->id,
                        'balance' => 0,
                        'balance_bonus' => 0,
                        'balance_bonus_rollover' => 0,
                        'balance_bonus_rollover_used' => 0,
                        'hide_balance' => 0,
                        'hide_balancerefer' => 0,
                        'total_bet' => 0,
                        'total_won' => 0,
                        'total_lose' => 0,
                        'last_won' => 0,
                        'last_lose' => 0,
                        'referPercent' => 0,
                        'refer_rewards' => 0,
                        'coin' => 0
                    ]);
                }
            }

            // Fazer login
            \Illuminate\Support\Facades\Auth::login($user);

            $user->logged_in = 1;
            $user->last_login = now();
            $user->save();

            // Configurar idioma do usuário
            $userLanguage = $user->language ?? 'pt_BR';
            \Illuminate\Support\Facades\Session::put('locale', $userLanguage);
            \Illuminate\Support\Facades\App::setLocale($userLanguage);
            \Illuminate\Support\Facades\Cookie::queue('user_locale', $userLanguage, 525600);

            // Retornar a view para fechar o popup com sucesso
            return view('auth.twitch.popup-close', [
                'success' => true,
                'message' => 'Login realizado com sucesso!'
            ]);

        } catch (\Exception $e) {
            // Retornar a view para fechar o popup com erro
            return view('auth.twitch.popup-close', [
                'success' => false,
                'message' => 'Erro ao realizar login com Twitch: ' . $e->getMessage()
            ]);
        }
    }

    public function getLoginModal()
    {
        return view('auth.login-modal');
    }

    public function getRegisterModal()
    {
        return view('auth.register-modal');
    }

    /**
     * Método para definir idioma durante o registro
     */
    public function setInitialLanguage(Request $request)
    {
        $validatedData = $request->validate([
            'language' => 'required|in:pt_BR,es,en'
        ]);

        $language = $validatedData['language'];

        // Se o usuário estiver autenticado
        if (Auth::check()) {
            $user = Auth::user();
            $user->language = $language;
            $user->save();
        }

        // Definir idioma na sessão
        Session::put('locale', $language);
        App::setLocale($language);
        Cookie::queue('user_locale', $language, 525600); // Cookie com duração de 1 ano

        return response()->json([
            'success' => true,
            'language' => $language
        ]);
    }

    /**
     * Verificar se o usuário está autenticado como administrador
     */
    public function checkAdminAuth(Request $request)
    {
        if (Auth::check() && Auth::user()->is_admin >= 1) {
            return response()->json([
                'isAdmin' => true
            ]);
        }

        return response()->json([
            'isAdmin' => false
        ]);
    }

    /**
     * Verifica se um email já existe no banco de dados
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkEmail(Request $request)
    {
        $email = $request->input('email');

        if (!$email) {
            return response()->json(['error' => 'Email não fornecido'], 400);
        }

        $exists = User::where('email', $email)->exists();

        return response()->json([
            'available' => !$exists,
            'exists' => $exists,
            'email' => $email
        ]);
    }

    /**
     * Verifica se um CPF já existe no banco de dados
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkCpf(Request $request)
    {
        $cpf = $request->input('cpf');

        if (!$cpf) {
            return response()->json(['error' => 'CPF não fornecido'], 400);
        }

        // Limpar o CPF (apenas números)
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        if (strlen($cpf) !== 11) {
            return response()->json(['error' => 'CPF inválido'], 400);
        }

        // Verificar se o CPF já existe na tabela de usuários
        // Verifica tanto no campo CPF quanto no campo PIX
        $existsAsCpf = User::where('cpf', $cpf)->exists();
        $existsAsPix = User::where('pix', $cpf)->exists();
        $exists = $existsAsCpf || $existsAsPix;

        return response()->json([
            'available' => !$exists,
            'exists' => $exists,
            'cpf' => $cpf
        ]);
    }

    /**
     * Endpoint combinado para verificar email e CPF simultaneamente
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateRegistration(Request $request)
    {
        $email = $request->input('email');
        $cpf = $request->input('cpf');

        $response = ['email_exists' => false, 'cpf_exists' => false];

        if ($email) {
            $response['email_exists'] = User::where('email', $email)->exists();
        }

        if ($cpf) {
            // Limpar o CPF (apenas números)
            $cpf = preg_replace('/[^0-9]/', '', $cpf);

            if (strlen($cpf) === 11) {
                $existsAsCpf = User::where('cpf', $cpf)->exists();
                $existsAsPix = User::where('pix', $cpf)->exists();
                $response['cpf_exists'] = $existsAsCpf || $existsAsPix;
            }
        }

        return response()->json($response);
    }

    /**
     * Verifica se o email e CPF já existem no banco de dados
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyRegistrationData(Request $request)
    {
        $email = $request->input('email');
        $cpf = $request->input('cpf');

        // Remover formatação do CPF
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        $response = [
            'email_exists' => false,
            'cpf_exists' => false
        ];

        // Verificar email
        if ($email) {
            $response['email_exists'] = User::where('email', $email)->exists();
        }

        // Verificar CPF - verificação mais abrangente
        if ($cpf && strlen($cpf) === 11) {
            // Verificar se existe no campo 'cpf'
            $existsAsCpf = User::where('cpf', $cpf)->exists();

            // Verificar se existe no campo 'pix'
            $existsAsPix = User::where('pix', $cpf)->exists();

            // Verificar se existe com formatação (para garantir)
            $formattedCpf = substr($cpf, 0, 3) . '.' .
                substr($cpf, 3, 3) . '.' .
                substr($cpf, 6, 3) . '-' .
                substr($cpf, 9, 2);

            $existsFormatted = User::where('cpf', $formattedCpf)
                ->orWhere('pix', $formattedCpf)
                ->exists();

            $response['cpf_exists'] = $existsAsCpf || $existsAsPix || $existsFormatted;

            // Verificação adicional para outros formatos possíveis
            if (!$response['cpf_exists']) {
                // Formato apenas com pontos
                $formatDots = substr($cpf, 0, 3) . '.' .
                    substr($cpf, 3, 3) . '.' .
                    substr($cpf, 6, 5);

                // Formato apenas com traço
                $formatDash = substr($cpf, 0, 9) . '-' .
                    substr($cpf, 9, 2);

                $existsOtherFormat = User::where('cpf', $formatDots)
                    ->orWhere('pix', $formatDots)
                    ->orWhere('cpf', $formatDash)
                    ->orWhere('pix', $formatDash)
                    ->exists();

                $response['cpf_exists'] = $response['cpf_exists'] || $existsOtherFormat;
            }
        }

        return response()->json($response);
    }

    /**
     * Verifica se um campo específico já existe no banco de dados
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkDuplicate(Request $request)
    {
        $field = $request->input('field', '');
        $value = $request->input('cpf', '');

        if (empty($field) || empty($value)) {
            return response()->json([
                'exists' => false,
                'error' => 'Campo ou valor não especificado.[' . $field . ']['.$value.']'
            ]);
        }

        // Se for CPF, remover formatação
        if ($field === 'cpf') {
            $value = preg_replace('/[^0-9]/', '', $value);
        }

        $query = User::query();

        // Verificação específica para CPF e PIX
        if ($field === 'cpf') {
            $query->where(function($q) use ($value) {
                $q->where('cpf', $value)
                    ->orWhere('pix', $value);

                // Adicionar formatos alternativos
                if (strlen($value) === 11) {
                    $formatted = substr($value, 0, 3) . '.' .
                        substr($value, 3, 3) . '.' .
                        substr($value, 6, 3) . '-' .
                        substr($value, 9, 2);

                    $q->orWhere('cpf', $formatted)
                        ->orWhere('pix', $formatted);
                }
            });
        }

        $exists = $query->exists();

        return response()->json([
            'exists' => $exists,
            'duplicate' => $exists,
            'available' => !$exists,
            'field' => $field,
            'value' => $value
        ]);
    }
}
