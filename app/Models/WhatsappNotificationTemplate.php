<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappNotificationTemplate extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_notification_templates';

    protected $fillable = [
        'type',
        'name',
        'description',
        'message_template',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
} 