<?php

namespace App\Observers;

use App\Models\SportBetSummary;
use App\Models\User;
use App\Services\WhatsappService;
use Illuminate\Support\Facades\Log;

class SportBetObserver
{
    protected $whatsappService;

    public function __construct(WhatsappService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Handle the SportBetSummary "created" event.
     */
    public function created(SportBetSummary $sportBet): void
    {
        // Log detalhado para debug
        Log::debug('SportBetObserver: Nova aposta esportiva criada', [
            'id' => $sportBet->id,
            'provider' => $sportBet->provider,
            'operation' => $sportBet->operation,
            'status' => $sportBet->status,
            'user_id' => $sportBet->user_id,
            'amount' => $sportBet->amount,
            'transactionId' => $sportBet->transactionId
        ]);
        
        $this->notifySportBet($sportBet);
    }

    /**
     * Handle the SportBetSummary "updated" event.
     */
    public function updated(SportBetSummary $sportBet): void
    {
        // Para Betby, notificar apenas na criação (operation = 'make') ou mudança de status importante
        if ($sportBet->provider === 'betby') {
            // Para Betby, só notificar se houve mudança de status para resultado final
            if ($sportBet->isDirty('status') && in_array($sportBet->status, ['win', 'lost', 'discard'])) {
                Log::debug('SportBetObserver: Aposta Betby finalizada', [
                    'id' => $sportBet->id,
                    'status_anterior' => $sportBet->getOriginal('status'),
                    'status_atual' => $sportBet->status,
                    'user_id' => $sportBet->user_id
                ]);
                // Podemos adicionar aqui notificação de resultado se necessário no futuro
                // $this->notifySportBetResult($sportBet);
            }
        } else {
            // Para outros provedores, notificar apenas se o status foi alterado
            if ($sportBet->isDirty('status')) {
                Log::debug('SportBetObserver: Aposta esportiva atualizada', [
                    'id' => $sportBet->id,
                    'provider' => $sportBet->provider,
                    'status_anterior' => $sportBet->getOriginal('status'),
                    'status_atual' => $sportBet->status,
                    'user_id' => $sportBet->user_id
                ]);
                $this->notifySportBet($sportBet);
            }
        }
    }

    /**
     * Enviar notificação sobre a aposta esportiva via WhatsApp
     */
    protected function notifySportBet(SportBetSummary $sportBet): void
    {
        try {
            // Log de início do processo de notificação
            Log::debug('SportBetObserver: Iniciando processo de notificação', [
                'sportbet_id' => $sportBet->id,
                'provider' => $sportBet->provider,
                'operation' => $sportBet->operation,
                'status' => $sportBet->status,
                'user_id' => $sportBet->user_id
            ]);
            
            // Verificar se o WhatsApp está conectado
            if (!$this->whatsappService->isConnected()) {
                Log::info('WhatsApp não está conectado para enviar notificação de aposta esportiva #' . $sportBet->id);
                return;
            }

            $user = User::find($sportBet->user_id);
            
            if (!$user) {
                Log::error('Usuário não encontrado para a aposta esportiva #' . $sportBet->id);
                return;
            }

            // Verificar se deve notificar baseado no provider e operation
            $shouldNotify = false;
            
            if ($sportBet->provider === 'betby') {
                // Para Betby, notificar apenas apostas com operation 'make' (nova aposta)
                $shouldNotify = ($sportBet->operation === 'make');
                
                Log::debug('SportBetObserver: Verificação Betby', [
                    'operation' => $sportBet->operation,
                    'should_notify' => $shouldNotify
                ]);
            } else {
                // Para outros provedores, usar a lógica original (operation 'debit')
                $shouldNotify = ($sportBet->operation === 'debit');
                
                Log::debug('SportBetObserver: Verificação outros provedores', [
                    'provider' => $sportBet->provider,
                    'operation' => $sportBet->operation,
                    'should_notify' => $shouldNotify
                ]);
            }
            
            if (!$shouldNotify) {
                Log::info('Notificação ignorada para aposta esportiva', [
                    'sportbet_id' => $sportBet->id,
                    'provider' => $sportBet->provider,
                    'operation' => $sportBet->operation,
                    'reason' => 'Operation não elegível para notificação'
                ]);
                return;
            }

            // Enviar notificação de aposta esportiva
            $result = $this->whatsappService->sendSportBetNotification($user, $sportBet);
            
            if ($result) {
                Log::info('Notificação de aposta esportiva enviada com sucesso', [
                    'sportbet_id' => $sportBet->id,
                    'provider' => $sportBet->provider,
                    'user_id' => $user->id,
                    'amount' => $sportBet->amount,
                    'operation' => $sportBet->operation
                ]);
            } else {
                Log::error('Falha ao enviar notificação de aposta esportiva', [
                    'sportbet_id' => $sportBet->id,
                    'provider' => $sportBet->provider,
                    'user_id' => $user->id,
                    'operation' => $sportBet->operation
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao enviar notificação de WhatsApp para aposta esportiva: ' . $e->getMessage(), [
                'sportbet_id' => $sportBet->id,
                'provider' => $sportBet->provider ?? 'não definido',
                'operation' => $sportBet->operation ?? 'não definida',
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }
    }

    /**
     * Handle the SportBetSummary "deleted" event.
     */
    public function deleted(SportBetSummary $sportBet): void
    {
        //
    }

    /**
     * Handle the SportBetSummary "restored" event.
     */
    public function restored(SportBetSummary $sportBet): void
    {
        //
    }

    /**
     * Handle the SportBetSummary "force deleted" event.
     */
    public function forceDeleted(SportBetSummary $sportBet): void
    {
        //
    }
} 