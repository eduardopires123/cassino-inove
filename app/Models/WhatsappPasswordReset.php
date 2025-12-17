<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappPasswordReset extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_password_resets';

    protected $fillable = [
        'phone',
        'token',
        'verification_code',
        'is_verified',
        'created_at',
        'expires_at',
        'user_id',
        'code_used',
        'code'
    ];

    public $timestamps = false;
} 