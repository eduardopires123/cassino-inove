<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Adm\Logs;

class Settings extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'name',
        'subname',
        'logo',
        'favicon',
        'aff_min_dep',
        'aff_amount',
        'min_saque_af',
        'max_saque_af',
        'max_saque_aut_af',
        'percent_aff',
        'min_saque_n',
        'max_saque_n',
        'max_saque_aut',
        'max_saque_diario',
        'max_quantidade_saques_diario',
        'max_quantidade_saques_automaticos_diario',
        'rollover_saque',
        'min_dep',
        'max_dep',
        'bonus_mult',
        'bonus_min_dep',
        'bonus_max_dep',
        'bonus_rollover',
        'bonus_expire_days',
        'bonus_all_deposits',
        'revenabled',
        'cpaenabled',
        'tbshall',
        'tbskey',
        'playfiver_token',
        'playfiver_key',
        'sportpartnername',
        'sports_api_provider',
        'expire',
        'valor',
        'enable_sports',
        'enable_sports_bonus',
        'enable_cassino_bonus',
        'vip_level_lastreset',
        'tawkto_src',
        'tawkto_active',
        'jivochat_src',
        'jivochat_active',
        'default_home_page',
    ];

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
        'max_saque_diario' => 'decimal:2',
        'max_quantidade_saques_diario' => 'integer',
        'max_quantidade_saques_automaticos_diario' => 'integer',
        'rollover_saque' => 'integer',
        'min_dep' => 'decimal:2',
        'max_dep' => 'decimal:2',
        'bonus_mult' => 'integer',
        'bonus_min_dep' => 'decimal:2',
        'bonus_max_dep' => 'decimal:2',
        'bonus_rollover' => 'integer',
        'bonus_expire_days' => 'integer',
        'bonus_all_deposits' => 'integer',
        'revenabled' => 'integer',
        'cpaenabled' => 'integer',
        'tawkto_active' => 'integer',
        'jivochat_active' => 'integer',
        'default_home_page' => 'string',
        'updated_at' => 'datetime',
        'valor' => 'decimal:2',
        'expire' => 'datetime',
    ];

    /**
     * Verificar se a API Betby está ativa
     */
    public static function isBetbyActive()
    {
        $settings = self::first();
        return $settings && $settings->sports_api_provider === 'betby';
    }

    /**
     * Verificar se a API Digitain está ativa
     */
    public static function isDigitainActive()
    {
        $settings = self::first();
        return !$settings || $settings->sports_api_provider === 'digitain' || $settings->sports_api_provider === null;
    }

    /**
     * Obter o provedor de API de sports ativo
     */
    public static function getSportsApiProvider()
    {
        $settings = self::first();
        return $settings ? ($settings->sports_api_provider ?? 'digitain') : 'digitain';
    }

    protected static function booted()
    {
        parent::boot();

        static::updated(function (Settings $Settings) {
            $userId = Auth::id();

            $dirtyAttributes = $Settings->getDirty();
            unset($dirtyAttributes['updated_at']);

            if (!empty($dirtyAttributes)) {
                foreach ($dirtyAttributes as $column => $newValue) {
                    $originalValue = $Settings->getOriginal($column);

                    if ($column == 'name') {
                        $column = "Nome";
                    }elseif ($column == 'logo') {
                        $column = "Logo";
                    }elseif ($column == 'aff_min_dep') {
                        $column = "Mínimo Depósito Afiliado";
                    }elseif ($column == 'aff_amount') {
                        $column = "Bônus Afiliado por Indicação";
                    }elseif ($column == 'min_saque_af') {
                        $column = "Mín Saque Afiliado";
                    }elseif ($column == 'max_saque_af') {
                        $column = "Máx Saque Afiliado";
                    }elseif ($column == 'max_saque_aut_af') {
                        $column = "Máx Saque Automático Afiliado";
                    }elseif ($column == 'percent_aff') {
                        $column = "Porcentagem Padrão";
                    }elseif ($column == 'min_saque_n') {
                        $column = "Mín Saque";
                    }elseif ($column == 'max_saque_n') {
                        $column = "Máx Saque";
                    }elseif ($column == 'max_saque_aut') {
                        $column = "Máx Saque Automático";
                    }elseif ($column == 'max_saque_diario') {
                        $column = "Máx Saque Diário";
                    }elseif ($column == 'max_quantidade_saques_diario') {
                        $column = "Máx Quantidade Saques Diário";
                    }elseif ($column == 'max_quantidade_saques_automaticos_diario') {
                        $column = "Máx Quantidade Saques Automáticos Diário";
                    }elseif ($column == 'min_dep') {
                        $column = "Mín Dep";
                    }elseif ($column == 'max_dep') {
                        $column = "Máx Dep";
                    }elseif ($column == 'bonus_mult') {
                        $column = "Múltiplicador Bônus 1º Depósito";
                    }elseif ($column == 'bonus_min_dep') {
                        $column = "Mín Dep Bônus 1º Depósito";
                    }elseif ($column == 'bonus_max_dep') {
                        $column = "Máx Dep Bônus 1º Depósito";
                    }elseif ($column == 'bonus_rollover') {
                        $column = "Rollover de Bônus";
                    }elseif ($column == 'bonus_expire_days') {
                        $column = "Expiração de Bônus";
                    }elseif ($column == 'revenabled') {
                        $column = "Ativar Bonificação RevShare";
                    }elseif ($column == 'cpaenabled') {
                        $column = "Ativar Bonificação CPA";
                    }elseif ($column == 'tawkto_src') {
                        $column = "Link do Chat Tawk.to";
                    }elseif ($column == 'tawkto_active') {
                        $column = "Ativar Chat Tawk.to";
                    }elseif ($column == 'jivochat_src') {
                        $column = "Código do JivoChat";
                    }elseif ($column == 'jivochat_active') {
                        $column = "Ativar JivoChat";
                    }

                    /*Logs::create([
                        'updated_by' => $userId,
                        'user_id' => 0,
                        'log' => "Configurações: A coluna '{$column}' foi alterada. Valor original: '{$originalValue}', Novo valor: '{$newValue}'",
                        'type' => 1,
                    ]);*/
                }
            }
        });
    }
}
