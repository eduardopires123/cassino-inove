<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;
    public $name;
    public $email;
    public $site_name;
    public $year;
    protected $fromEmail;
    protected $fromName;

    /**
     * Create a new message instance.
     *
     * @param string $code O código de verificação
     * @param array $data Dados adicionais para o template
     * @param array|null $sender Remetente personalizado ['email' => '', 'name' => '']
     */
    public function __construct($code, array $data = [], $sender = null)
    {
        $this->code = $code;
        $this->name = $data['name'] ?? 'Usuário';
        $this->email = $data['email'] ?? '';
        $this->site_name = $data['site_name'] ?? config('app.name');
        $this->year = date('Y');
        
        // Configurar remetente personalizado se fornecido
        $this->fromEmail = $sender['email'] ?? config('mail.from.address');
        $this->fromName = $sender['name'] ?? config('mail.from.name');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Seu código de verificação - ' . $this->site_name)
                    ->from($this->fromEmail, $this->fromName)
                    ->view('emails.verification-code');
    }
}