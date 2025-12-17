<?php

namespace App\Observers;

use App\Models\Transactions;
use App\Models\User;
use App\Services\WhatsappService;
use Illuminate\Support\Facades\Log;

class TransactionObserver
{
    protected $whatsappService;

    public function __construct(WhatsappService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Handle the Transactions "created" event.
     */
    public function created(Transactions $transaction): void
    {
        // Não fazemos nada quando a transação é criada, pois precisamos aguardar a aprovação
    }

    /**
     * Handle the Transactions "updated" event.
     */
    public function updated(Transactions $transaction): void
    {
        // Verificar se o status mudou para 1 (aprovado)
        if ($transaction->isDirty('status') && $transaction->status == 1) {
            $this->notifyTransaction($transaction);
        }
    }

    /**
     * Enviar notificação sobre a transação via WhatsApp
     */
    protected function notifyTransaction(Transactions $transaction): void
    {
        try {
            // Verificar se a transação já foi notificada anteriormente
            if (isset($transaction->notified) && $transaction->notified === true) {
                Log::info('Ignorando notificação duplicada para a transação #' . $transaction->id);
                return;
            }
            
            // Verificar se o WhatsApp está conectado
            if (!$this->whatsappService->isConnected()) {
                Log::info('WhatsApp não está conectado para enviar notificação de transação #' . $transaction->id);
                return;
            }

            $user = User::find($transaction->user_id);
            
            if (!$user) {
                Log::error('Usuário não encontrado para a transação #' . $transaction->id);
                return;
            }

            // Se for um saque (type = 1) e está aprovado (status = 1)
            if ($transaction->type == 1 && $transaction->status == 1) {
                $this->whatsappService->sendWithdrawalNotification($user, $transaction);
                Log::info('Notificação de saque aprovado enviada para a transação #' . $transaction->id);
            }
            // Se for um depósito (type = 0)
            elseif ($transaction->type == 0) {
                $this->whatsappService->sendDepositNotification($user, $transaction);
                Log::info('Notificação de depósito enviada para a transação #' . $transaction->id);
            }
            // Se for uma aposta (outro tipo)
            else {
                $this->whatsappService->sendBetNotification($user, $transaction);
                Log::info('Notificação de aposta enviada para a transação #' . $transaction->id);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao enviar notificação de WhatsApp: ' . $e->getMessage());
        }
    }

    /**
     * Handle the Transactions "deleted" event.
     */
    public function deleted(Transactions $transaction): void
    {
        //
    }

    /**
     * Handle the Transactions "restored" event.
     */
    public function restored(Transactions $transaction): void
    {
        //
    }

    /**
     * Handle the Transactions "force deleted" event.
     */
    public function forceDeleted(Transactions $transaction): void
    {
        //
    }
}
