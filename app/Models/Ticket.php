<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject',
        'status',
        'priority',
        'department',
        'last_reply',
    ];

    protected $casts = [
        'last_reply' => 'datetime',
    ];

    /**
     * Relacionamento com o usuário que criou o ticket
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com as mensagens do ticket
     */
    public function messages()
    {
        return $this->hasMany(TicketMessage::class);
    }

    /**
     * Método para obter a última mensagem do ticket
     */
    public function getLastMessageAttribute()
    {
        return $this->messages()->latest()->first();
    }

    /**
     * Escopo para tickets abertos
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Escopo para tickets em andamento
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Escopo para tickets fechados
     */
    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    /**
     * Verifica se o ticket está aberto
     */
    public function isOpen()
    {
        return $this->status === 'open';
    }

    /**
     * Verifica se o ticket está em andamento
     */
    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }

    /**
     * Verifica se o ticket está fechado
     */
    public function isClosed()
    {
        return $this->status === 'closed';
    }

    /**
     * Obter o texto da prioridade
     */
    public function getPriorityTextAttribute()
    {
        return [
            'low' => 'Baixa',
            'medium' => 'Média',
            'high' => 'Alta',
        ][$this->priority] ?? 'Desconhecida';
    }

    /**
     * Obter o texto do departamento
     */
    public function getDepartmentTextAttribute()
    {
        return [
            'general' => 'Geral',
            'billing' => 'Pagamentos',
            'technical' => 'Suporte Técnico',
        ][$this->department] ?? 'Desconhecido';
    }

    /**
     * Obter o texto do status
     */
    public function getStatusTextAttribute()
    {
        return [
            'open' => 'Aberto',
            'in_progress' => 'Em Andamento',
            'closed' => 'Fechado',
        ][$this->status] ?? 'Desconhecido';
    }

    /**
     * Obter a classe de cor do status para Bootstrap
     */
    public function getStatusColorAttribute()
    {
        return [
            'open' => 'success',
            'in_progress' => 'warning',
            'closed' => 'secondary',
        ][$this->status] ?? 'primary';
    }

    /**
     * Obter a classe de cor da prioridade para Bootstrap
     */
    public function getPriorityColorAttribute()
    {
        return [
            'low' => 'success',
            'medium' => 'warning',
            'high' => 'danger',
        ][$this->priority] ?? 'primary';
    }
}