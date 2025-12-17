<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashbackSetting extends Model
{
    use HasFactory;

    protected $table = 'cashback_settings';

    protected $fillable = [
        'user_id',
        'name',
        'percentage',
        'type',
        'min_loss',
        'max_cashback',
        'auto_apply',
        'expiry_days',
        'active',
        'is_global',
        'vip_level',
        'scheduled_day',
        'scheduled_hour',
        'scheduled_minute',
        'schedule_active',
        'scheduled_frequency',
        'last_run_at',
        'next_run_at'
    ];

    protected $casts = [
        'percentage' => 'decimal:2',
        'min_loss' => 'decimal:2',
        'max_cashback' => 'decimal:2',
        'auto_apply' => 'boolean',
        'active' => 'boolean',
        'is_global' => 'boolean',
        'expiry_days' => 'integer',
        'vip_level' => 'integer',
        'scheduled_day' => 'integer',
        'scheduled_hour' => 'integer',
        'scheduled_minute' => 'integer',
        'schedule_active' => 'boolean',
        'last_run_at' => 'datetime',
        'next_run_at' => 'datetime'
    ];

    /**
     * Get the user cashbacks for this setting
     */
    public function userCashbacks()
    {
        return $this->hasMany(UserCashback::class);
    }

    /**
     * Get the user this setting is specifically for (if any)
     * This is used for user-specific cashback settings
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate cashback amount for a loss
     * 
     * @param float $lossAmount
     * @return float
     */
    public function calculateCashback($lossAmount)
    {
        // Retorna 0 se o cashback não estiver ativo
        if (!$this->active) {
            return 0;
        }

        // Verifica se o valor da perda é maior que o mínimo necessário
        if ($lossAmount < $this->min_loss) {
            return 0;
        }

        // Calcula o valor do cashback
        $cashbackAmount = $lossAmount * ($this->percentage / 100);

        // Aplica limite máximo de cashback, se configurado
        if ($this->max_cashback && $cashbackAmount > $this->max_cashback) {
            $cashbackAmount = $this->max_cashback;
        }

        return round($cashbackAmount, 2);
    }

    /**
     * Get the VIP level associated with this cashback setting
     */
    public function vipLevel()
    {
        return $this->belongsTo(VipLevel::class, 'vip_level', 'level');
    }

    /**
     * Obter a data/hora do próximo agendamento com base nas configurações
     *
     * @return \Carbon\Carbon|null
     */
    public function calculateNextRun()
    {
        if (!$this->schedule_active) {
            return null;
        }

        $now = \Carbon\Carbon::now();
        $next = $now->copy();

        switch ($this->scheduled_frequency) {
            case 'daily':
                // Se o horário de hoje já passou, agenda para amanhã
                $next->setHour($this->scheduled_hour ?? 0)
                    ->setMinute($this->scheduled_minute ?? 0)
                    ->setSecond(0);
                
                if ($next->isPast()) {
                    $next->addDay();
                }
                break;
                
            case 'weekly':
                // Configura para o próximo dia da semana especificado
                $targetDay = $this->scheduled_day ?? 1; // Default segunda-feira
                $next->setHour($this->scheduled_hour ?? 0)
                    ->setMinute($this->scheduled_minute ?? 0)
                    ->setSecond(0);
                
                // Calcular dias até o próximo dia da semana desejado
                $daysToAdd = ($targetDay - $next->dayOfWeek + 7) % 7;
                if ($daysToAdd === 0 && $next->isPast()) {
                    $daysToAdd = 7;
                }
                
                $next->addDays($daysToAdd);
                break;
                
            case 'biweekly':
                // Configurar para o dia 1 ou 16 do mês (dependendo do dia programado)
                $next->setHour($this->scheduled_hour ?? 0)
                    ->setMinute($this->scheduled_minute ?? 0)
                    ->setSecond(0);
                
                // Determinar se estamos configurando para o dia 1 ou dia 16
                $targetDay = $this->scheduled_day === 16 ? 16 : 1;
                
                // Se estamos antes do dia alvo do mês atual
                if ($next->day < $targetDay) {
                    $next->setDay($targetDay);
                } 
                // Se já passamos do dia alvo e o alvo é 1, ir para o dia 16
                else if ($next->day >= $targetDay && $targetDay == 1 && $next->day < 16) {
                    $next->setDay(16);
                }
                // Se já passamos do dia 16 ou estamos no dia 16 mas o horário já passou
                else if (($next->day > 16) || ($next->day >= $targetDay && $next->isPast())) {
                    // Se estamos em uma data após o dia 16, ir para o dia 1 do próximo mês
                    $next->addMonth()->setDay(1);
                }
                break;
                
            case 'monthly':
                // Configura para o mesmo dia no próximo mês
                $targetDay = min($this->scheduled_day ?? 1, $now->daysInMonth);
                
                $next->setDay($targetDay)
                    ->setHour($this->scheduled_hour ?? 0)
                    ->setMinute($this->scheduled_minute ?? 0)
                    ->setSecond(0);
                
                // Se hoje já passou do dia programado, agenda para o próximo mês
                if ($next->isPast()) {
                    $next->addMonth();
                    // Garantir que o dia seja válido no próximo mês (ex: 31 de janeiro -> 28/29 de fevereiro)
                    $next->setDay(min($targetDay, $next->daysInMonth));
                }
                break;
                
            case 'once':
                // Para execução única, usar a data e hora configuradas diretamente
                if ($this->next_run_at) {
                    return $this->next_run_at;
                }
                
                // Se não houver next_run_at definido, criar a partir dos campos scheduled
                if ($this->scheduled_at) {
                    return $this->scheduled_at;
                }
                
                // Senão, criar a partir dos campos de hora e minuto
                if ($this->scheduled_hour !== null && $this->scheduled_minute !== null) {
                    // Verificar se há data específica configurada
                    if ($this->scheduled_day) {
                        // Se scheduled_day representa um dia do mês (1-31)
                        if ($this->scheduled_day <= 31) {
                            $next = $now->copy()->setDay($this->scheduled_day);
                            if ($next->isPast()) {
                                $next->addMonth();
                            }
                        }
                    }
                    
                    $next->setHour($this->scheduled_hour)
                        ->setMinute($this->scheduled_minute)
                        ->setSecond(0);
                    
                    // Se o horário já passou, agendar para o dia seguinte
                    if ($next->isPast()) {
                        $next->addDay();
                    }
                    
                    return $next;
                }
                
                // Fallback: retornar o momento atual +1 hora se nada estiver configurado
                return $now->addHour();
                break;
        }
        
        return $next;
    }

    /**
     * Atualiza a data/hora do próximo agendamento
     *
     * @return bool
     */
    public function updateNextRun()
    {
        $nextRun = $this->calculateNextRun();
        
        if ($nextRun) {
            $this->next_run_at = $nextRun;
            return $this->save();
        }
        
        return false;
    }

    /**
     * Marca que o processamento foi executado
     *
     * @return bool
     */
    public function markAsRun()
    {
        $this->last_run_at = now();
        
        // Se a frequência for 'once', desativar o agendamento após a execução
        if ($this->scheduled_frequency === 'once') {
            $this->schedule_active = false;
            // Registrar que foi executado para um agendamento único
            \Illuminate\Support\Facades\Log::info("Agendamento único finalizado para {$this->name} (ID: {$this->id})");
        } else {
            // Para outros tipos de frequência, calcular o próximo agendamento
            $this->updateNextRun();
        }
        
        return $this->save();
    }
} 