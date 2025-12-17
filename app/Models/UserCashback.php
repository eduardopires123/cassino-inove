<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\NotificationService;

class UserCashback extends Model
{
    use HasFactory;

    protected $table = 'user_cashbacks';

    protected $fillable = [
        'user_id',
        'cashback_setting_id',
        'total_loss',
        'cashback_amount',
        'percentage_applied',
        'type',
        'status',
        'start_date',
        'end_date',
        'credited_at',
        'expires_at',
        'notes'
    ];

    protected $casts = [
        'total_loss' => 'decimal:2',
        'cashback_amount' => 'decimal:2',
        'percentage_applied' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'credited_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    /**
     * Usuário a quem pertence este cashback
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Configuração de cashback associada
     */
    public function setting()
    {
        return $this->belongsTo(CashbackSetting::class, 'cashback_setting_id');
    }

    /**
     * Aplicar o cashback na conta do usuário
     * 
     * @return bool
     */
    public function apply()
    {
        // Verifica se já foi creditado ou expirado
        if ($this->status !== 'pending') {
            return false;
        }

        // Busca a carteira do usuário
        $wallet = $this->user->wallet;
        
        if (!$wallet) {
            return false;
        }

        // Adiciona o valor do cashback ao saldo principal (balance)
        $oldBalance = $wallet->balance;
        $wallet->balance += $this->cashback_amount;
        $newBalance = $wallet->balance;
        
        // Atualiza status para creditado e salva a data
        $this->status = 'credited';
        $this->credited_at = now();
        
        // Salva as mudanças
        $wallet->save();
        $this->save();
        
        // Registrar o log da operação
        \App\Models\Admin\Logs::create([
            'field_name' => 'CashBack - Usuario',
            'old_value' => $oldBalance,
            'new_value' => $newBalance,
            'updated_by' => auth()->id() ?? 1,
            'user_id' => $this->user_id,
            'type' => 1,
            'log' => "Pagamento de cashback para {$this->user->name}: {$this->cashback_amount} - " . $this->getTypeText($this->type)
        ]);
        
        // Enviar notificação para o usuário
        $this->sendCreditedNotification();
        
        return true;
    }

    /**
     * Verificar se o cashback expirou
     * 
     * @return bool
     */
    public function checkExpiration()
    {
        if ($this->status !== 'pending') {
            return false;
        }

        if ($this->expires_at && now()->gt($this->expires_at)) {
            $this->status = 'expired';
            $this->save();
            return true;
        }

        return false;
    }

    /**
     * Hook para depois de criar um cashback: enviar notificação
     */
    protected static function booted()
    {
        parent::booted();
        
        // Não enviamos mais notificações no momento da criação do cashback
        // A notificação só será enviada quando o cashback for creditado
    }
    
    /**
     * Envia notificação quando um cashback é creditado
     */
    public function sendCreditedNotification()
    {
        try {
            $notificationService = app(NotificationService::class);
            
            if ($this->user) {
                $formattedAmount = number_format($this->cashback_amount, 2, ',', '.');
                $typeText = $this->getTypeText($this->type);
                
                $data = [
                    'title_pt_br' => '💰 Cashback Creditado',
                    'title_en' => '💰 Cashback Credited',
                    'title_es' => '💰 Cashback Acreditado',
                    
                    'content_pt_br' => "Seu cashback de R$ {$formattedAmount} para {$typeText} foi creditado na sua conta. Divirta-se!",
                    'content_en' => "Your cashback of R$ {$formattedAmount} for {$this->getTypeText($this->type, 'en')} has been credited to your account. Have fun!",
                    'content_es' => "Tu cashback de R$ {$formattedAmount} para {$this->getTypeText($this->type, 'es')} ha sido acreditado en tu cuenta. ¡Diviértete!",
                    
                    'link' => '/wallet'
                ];
                
                $notificationService->sendToUser($this->user->id, $data);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erro ao enviar notificação de cashback creditado: ' . $e->getMessage());
        }
    }
    
    /**
     * Retorna o texto do tipo de jogo em diferentes idiomas
     */
    private function getTypeText($type, $lang = 'pt_br')
    {
        if ($lang === 'pt_br') {
            switch ($type) {
                case 'sports': return 'Apostas Esportivas';
                case 'virtual': return 'Cassino';
                case 'all':
                default: return 'Todos os Jogos';
            }
        } elseif ($lang === 'en') {
            switch ($type) {
                case 'sports': return 'Sports Betting';
                case 'virtual': return 'Casino';
                case 'all':
                default: return 'All Games';
            }
        } elseif ($lang === 'es') {
            switch ($type) {
                case 'sports': return 'Apuestas Deportivas';
                case 'virtual': return 'Casino';
                case 'all':
                default: return 'Todos los Juegos';
            }
        }
        
        return 'All Games';
    }
} 