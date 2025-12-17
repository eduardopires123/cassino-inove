<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\WhatsappService;

use App\Models\Adm\Logs;

class Transactions extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'user_id',
        'isaf',
        'amount',
        'type',
        'with_type',
        'gateway',
        'token',
        'status',
        'chave_pix',
        'accept_bonus',
        'updated_at',
    ];

    /**
     * Boot the model.
     */
    protected static function booted()
    {
        parent::boot();

        // Para saques criados já com status=1 (aprovação automática)
        static::created(function (Transactions $transaction) {
            // Se for um saque (type = 1) e já está aprovado (status = 1)
            if ($transaction->type == 1 && $transaction->status == 1) {
                Log::info('SAQUE APROVADO AUTOMATICAMENTE - ID: ' . $transaction->id . ' - Valor: ' . $transaction->amount);

                try {
                    // Obter instância do WhatsappService
                    $whatsappService = app(WhatsappService::class);

                    // Verificar se o WhatsApp está conectado
                    if (!$whatsappService->isConnected()) {
                        Log::warning('WhatsApp não está conectado para enviar notificação de saque automático #' . $transaction->id);
                        return;
                    }

                    $user = User::find($transaction->user_id);

                    if (!$user) {
                        Log::error('Usuário não encontrado para a transação de saque automático #' . $transaction->id);
                        return;
                    }

                    // Enviar notificação de saque
                    $result = $whatsappService->sendWithdrawalNotification($user, $transaction);

                    if ($result) {
                        Log::info('Notificação de saque automático enviada com sucesso para a transação #' . $transaction->id);
                    } else {
                        Log::error('Falha ao enviar notificação de saque automático para a transação #' . $transaction->id);
                    }
                } catch (\Exception $e) {
                    Log::error('Erro ao enviar notificação de WhatsApp para saque automático: ' . $e->getMessage(), [
                        'exception' => get_class($e),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }
            // Novo: Se for um saque (type = 1) pendente (status = 0)
            elseif ($transaction->type == 1 && $transaction->status == 0) {
                Log::info('NOVO SAQUE PENDENTE - ID: ' . $transaction->id . ' - Valor: ' . $transaction->amount);

                try {
                    // Obter instância do WhatsappService
                    $whatsappService = app(WhatsappService::class);

                    // Verificar se o WhatsApp está conectado
                    if (!$whatsappService->isConnected()) {
                        Log::warning('WhatsApp não está conectado para enviar notificação de saque pendente #' . $transaction->id);
                        return;
                    }

                    $user = User::find($transaction->user_id);

                    if (!$user) {
                        Log::error('Usuário não encontrado para a transação de saque pendente #' . $transaction->id);
                        return;
                    }

                    // Para saques pendentes, enviamos apenas a notificação para o administrador
                    // e não para o usuário, que receberá apenas quando aprovar

                    // Adicionar flag para impedir que o observer envie mensagem duplicada
                    $transaction->notified = true;

                    // Enviar notificação para administrador
                    $resultAdmin = $whatsappService->sendPendingWithdrawalAdminNotification($user, $transaction);

                    if ($resultAdmin) {
                        Log::info('Notificação de saque pendente enviada com sucesso para administrador - transação #' . $transaction->id);
                    } else {
                        Log::error('Falha ao enviar notificação de saque pendente para administrador - transação #' . $transaction->id);
                    }
                } catch (\Exception $e) {
                    Log::error('Erro ao enviar notificação de WhatsApp para saque pendente: ' . $e->getMessage(), [
                        'exception' => get_class($e),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }
        });

        static::updated(function (Transactions $transaction) {
            // Log específico para rastrear quando uma transação é atualizada
            Log::debug('Transação ' . $transaction->id . ' foi atualizada', [
                'user_id' => $transaction->user_id,
                'type' => $transaction->type,
                'amount' => $transaction->amount,
                'old_status' => $transaction->getOriginal('status'),
                'new_status' => $transaction->status
            ]);

            $userId = Auth::id();

            // Log inicial para depuração
            Log::debug('Transação atualizada', [
                'transaction_id' => $transaction->id,
                'user_id' => $transaction->user_id,
                'type' => $transaction->type,
                'status' => $transaction->status,
                'amount' => $transaction->amount
            ]);

            $dirtyAttributes = $transaction->getDirty();
            unset($dirtyAttributes['updated_at']);

            // Log dos atributos alterados
            Log::debug('Atributos alterados', [
                'transaction_id' => $transaction->id,
                'dirty_attributes' => $dirtyAttributes
            ]);

            if (!empty($dirtyAttributes)) {
                foreach ($dirtyAttributes as $column => $newValue) {
                    $originalValue = $transaction->getOriginal($column);

                    Log::debug('Verificando alteração', [
                        'column' => $column,
                        'original' => $originalValue,
                        'new' => $newValue
                    ]);

                    if ($column == 'status') {
                        $column = "Status";

                        // Se o status foi alterado para 1 (aprovado) e é um saque (type = 1)
                        if ($newValue == 1) {
                            if ($transaction->type == 1 || $transaction->type === '1') {
                                Log::info('SAQUE APROVADO - ID: ' . $transaction->id . ' - Valor: ' . $transaction->amount);

                                // Verificar se o status anterior era 0 (pendente)
                                if ($originalValue == 0) {
                                    Log::info('Status de saque alterado de PENDENTE para APROVADO - ID: ' . $transaction->id);
                                }
                            } elseif ($transaction->type == 0 || $transaction->type === '0' || $transaction->type === 0) {
                                Log::info('DEPÓSITO APROVADO - ID: ' . $transaction->id . ' - Valor: ' . $transaction->amount);
                            }

                            Log::debug('Status alterado para aprovado. Tentando enviar notificação WhatsApp', [
                                'transaction_id' => $transaction->id,
                                'type' => $transaction->type,
                                'type_text' => $transaction->type == 0 ? 'DEPÓSITO' : ($transaction->type == 1 ? 'SAQUE' : 'OUTRO')
                            ]);

                            try {
                                // Informações detalhadas de diagnóstico para identificar o problema
                                Log::info('DIAGNÓSTICO DE TRANSAÇÃO:', [
                                    'transaction_id' => $transaction->id,
                                    'type_value' => $transaction->type,
                                    'type_class' => gettype($transaction->type),
                                    'is_deposit' => ($transaction->type == 0),
                                    'is_withdrawal' => ($transaction->type == 1),
                                    'is_other' => ($transaction->type != 0 && $transaction->type != 1),
                                    'status' => $transaction->status,
                                    'amount' => $transaction->amount
                                ]);

                                // Obter instância do WhatsappService
                                $whatsappService = app(WhatsappService::class);

                                Log::debug('WhatsappService obtido', [
                                    'service_initialized' => ($whatsappService !== null)
                                ]);

                                // Verificar se o WhatsApp está conectado
                                $isConnected = $whatsappService->isConnected();
                                Log::debug('Verificação de conexão WhatsApp', [
                                    'is_connected' => $isConnected
                                ]);

                                if (!$isConnected) {
                                    Log::warning('WhatsApp não está conectado para enviar notificação de transação #' . $transaction->id);
                                    return;
                                }

                                $user = User::find($transaction->user_id);

                                Log::debug('Usuário encontrado', [
                                    'user_exists' => ($user !== null),
                                    'user_id' => $transaction->user_id,
                                    'user_name' => $user ? $user->name : 'não encontrado'
                                ]);

                                if (!$user) {
                                    Log::error('Usuário não encontrado para a transação #' . $transaction->id);
                                    return;
                                }

                                // Se for um saque (type = 1)
                                if ($transaction->type == 1 || $transaction->type === '1') {
                                    Log::debug('Enviando notificação de SAQUE', [
                                        'transaction_id' => $transaction->id,
                                        'amount' => $transaction->amount,
                                        'user_id' => $user->id,
                                        'user_name' => $user->name
                                    ]);

                                    $result = $whatsappService->sendWithdrawalNotification($user, $transaction);

                                    // Adicionar flag para impedir que o observer envie mensagem duplicada
                                    $transaction->notified = true;

                                    Log::debug('Resultado do envio de notificação de saque', [
                                        'transaction_id' => $transaction->id,
                                        'success' => ($result !== false),
                                        'result' => $result
                                    ]);

                                    if ($result) {
                                        Log::info('Notificação de saque enviada com sucesso para a transação #' . $transaction->id);
                                    } else {
                                        Log::error('Falha ao enviar notificação de saque para a transação #' . $transaction->id);
                                    }
                                }
                                // Se for um depósito (type = 0)
                                elseif ($transaction->type == 0 || $transaction->type === '0' || $transaction->type === 0) {
                                    Log::debug('Enviando notificação de DEPÓSITO', [
                                        'transaction_id' => $transaction->id,
                                        'amount' => $transaction->amount,
                                        'user_id' => $user->id,
                                        'user_name' => $user->name,
                                        'type' => 'DEPÓSITO' // Certifica que é depósito
                                    ]);

                                    // Certifica-se que o WhatsappService tem uma instância válida
                                    if (!$whatsappService) {
                                        Log::error('WhatsappService não foi inicializado corretamente para enviar notificação de depósito');
                                        return;
                                    }

                                    // IMPORTANTE: Certifica-se de usar o método correto para depósitos
                                    $result = $whatsappService->sendDepositNotification($user, $transaction);

                                    // Adicionar flag para impedir que o observer envie mensagem duplicada
                                    $transaction->notified = true;

                                    Log::debug('Resultado do envio de notificação de depósito', [
                                        'transaction_id' => $transaction->id,
                                        'success' => ($result !== false),
                                        'result' => $result
                                    ]);

                                    if ($result) {
                                        Log::info('Notificação de depósito enviada com sucesso para a transação #' . $transaction->id);
                                    } else {
                                        Log::error('Falha ao enviar notificação de depósito para a transação #' . $transaction->id);
                                    }
                                }
                                // Se for outro tipo (aposta, etc)
                                else {
                                    // Verificando novamente se é um depósito para evitar erros
                                    if (strtolower(trim((string)$transaction->type)) === '0' || $transaction->type === 0) {
                                        Log::warning('Detectado depósito incorretamente como outro tipo. Corrigindo...', [
                                            'transaction_id' => $transaction->id,
                                            'type' => $transaction->type,
                                            'type_as_string' => (string)$transaction->type
                                        ]);

                                        // Usar o método de depósito em vez do método de aposta
                                        $result = $whatsappService->sendDepositNotification($user, $transaction);

                                        if ($result) {
                                            Log::info('Notificação de depósito (corrigida) enviada com sucesso para a transação #' . $transaction->id);
                                        } else {
                                            Log::error('Falha ao enviar notificação de depósito (corrigida) para a transação #' . $transaction->id);
                                        }

                                        return;
                                    }

                                    Log::debug('Enviando notificação de APOSTA', [
                                        'transaction_id' => $transaction->id,
                                        'amount' => $transaction->amount,
                                        'user_id' => $user->id,
                                        'user_name' => $user->name
                                    ]);

                                    $result = $whatsappService->sendBetNotification($user, $transaction);

                                    Log::debug('Resultado do envio de notificação de aposta', [
                                        'transaction_id' => $transaction->id,
                                        'success' => ($result !== false),
                                        'result' => $result
                                    ]);

                                    if ($result) {
                                        Log::info('Notificação de aposta enviada com sucesso para a transação #' . $transaction->id);
                                    } else {
                                        Log::error('Falha ao enviar notificação de aposta para a transação #' . $transaction->id);
                                    }
                                }
                            } catch (\Exception $e) {
                                Log::error('Erro ao enviar notificação de WhatsApp: ' . $e->getMessage(), [
                                    'exception' => get_class($e),
                                    'file' => $e->getFile(),
                                    'line' => $e->getLine(),
                                    'trace' => $e->getTraceAsString()
                                ]);
                            }
                        }
                    }

                    $Usuario = User::where('id', $transaction->user_id)->first();

                    /*Logs::create([
                        'updated_by' => $userId,
                        'user_id' => 0,
                        'log' => "Transação: A coluna '{$column}' da transação Nª '{$transaction->id}' ('{$Usuario->name}') foi alterada. Valor original: '{$originalValue}', Novo valor: '{$newValue}'",
                        'type' => 1,
                    ]);*/
                }
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function affiliate()
    {
        return $this->belongsTo(Affiliates::class, 'user_id', 'user_id');
    }
}
