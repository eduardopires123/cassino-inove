<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone_number',
        'recipient_name',
        'message',
        'status',
        'error_message',
        'response_data'
    ];

    protected $casts = [
        'response_data' => 'array',
    ];

    /**
     * Obtém o usuário relacionado à mensagem.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
