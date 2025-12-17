<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappConfig extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_config';

    protected $fillable = [
        'is_connected',
        'session_data',
        'connected_at',
        'last_qr_code',
        'phone_number',
        'min_odd_threshold',
        'min_return_threshold',
        'notify_only_threshold_bets',
        'min_deposit_threshold',
        'notify_only_threshold_deposits',
        'min_withdrawal_threshold',
        'notify_only_threshold_withdrawals',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'is_connected' => 'boolean',
        'session_data' => 'array',
        'connected_at' => 'datetime',
        'min_odd_threshold' => 'decimal:2',
        'min_return_threshold' => 'decimal:2',
        'notify_only_threshold_bets' => 'boolean',
        'min_deposit_threshold' => 'decimal:2',
        'notify_only_threshold_deposits' => 'boolean',
        'min_withdrawal_threshold' => 'decimal:2',
        'notify_only_threshold_withdrawals' => 'boolean',
    ];
} 