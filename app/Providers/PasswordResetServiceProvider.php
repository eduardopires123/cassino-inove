<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;

class PasswordResetServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Customizar o e-mail de redefinição de senha
        ResetPassword::toMailUsing(function ($notifiable, $token) {
            $email = $notifiable->getEmailForPasswordReset();
            
            return (new \Illuminate\Mail\Mailable)
                ->subject('BETBR - Recuperação de Senha')
                ->markdown('emails.reset-password', [
                    'token' => $token,
                    'email' => $email,
                ]);
        });
    }
}