<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'is_admin',
        'message',
        'has_attachment',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
        'has_attachment' => 'boolean',
    ];

    /**
     * Relacionamento com o ticket ao qual a mensagem pertence
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Relacionamento com o usuário que enviou a mensagem
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com os anexos da mensagem
     */
    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class);
    }
}