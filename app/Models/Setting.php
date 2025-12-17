<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * A tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'settings';

    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'subname',
        'logo',
        'aff_min_dep',
        'aff_amount',
        'min_saque_af',
        'max_saque_af',
        'max_saque_aut_af',
        'percent_aff',
        'min_saque_n',
        'max_saque_n',
        'max_saque_aut',
        'min_dep',
        'max_dep',
        'bonus_mult',
        'bonus_min_dep',
        'bonus_max_dep',
        'bonus_rollover',
        'bonus_expire_days',
        'revenabled',
        'cpaenabled',
        'tbshall',
        'tbskey',
        'playfiver_token',
        'playfiver_key',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'aff_min_dep' => 'decimal:2',
        'aff_amount' => 'decimal:2',
        'min_saque_af' => 'decimal:2',
        'max_saque_af' => 'decimal:2',
        'max_saque_aut_af' => 'decimal:2',
        'percent_aff' => 'integer',
        'min_saque_n' => 'decimal:2',
        'max_saque_n' => 'decimal:2',
        'max_saque_aut' => 'decimal:2',
        'min_dep' => 'decimal:2',
        'max_dep' => 'decimal:2',
        'bonus_mult' => 'integer',
        'bonus_min_dep' => 'decimal:2',
        'bonus_max_dep' => 'decimal:2',
        'bonus_rollover' => 'integer',
        'bonus_expire_days' => 'integer',
        'revenabled' => 'integer',
        'cpaenabled' => 'integer',
        'updated_at' => 'datetime',
    ];
} 