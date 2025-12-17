<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Admin\Logs;

class Gateways extends Model
{
    protected $table = 'gateways';

    protected $fillable = [
        'client_id',
        'nome',
        'api_url',
        'secret_key',
        'public_clientid_key',
        'pixkey',
        'secret_key_account',
        'public_clientid_key_account',
        'active',
    ];

    // Prevenindo erros com valores nulos
    protected $attributes = [
        'secret_key' => '',
        'public_clientid_key' => '',
        'active' => 0,
    ];

    protected static function booted()
    {
        parent::boot();

        static::updated(function (Gateways $Gateways) {
            $userId = Auth::id();

            $dirtyAttributes = $Gateways->getDirty();
            unset($dirtyAttributes['updated_at']);

            if (!empty($dirtyAttributes)) {
                foreach ($dirtyAttributes as $column => $newValue) {
                    $originalValue = $Gateways->getOriginal($column);

                    if ($column == 'active') {
                        $column = "Ativado";
                    }elseif ($column == 'secret_key') {
                        $column = "Secret Key";
                    }elseif ($column == 'public_clientid_key') {
                        $column = "Public & Client Id Key";
                    }

                    Logs::create([
                        'updated_by' => $userId,
                        'user_id' => 0,
                        'log' => "Gateways: A coluna '{$column}' de '{$Gateways->nome}' foi alterada. Valor original: '{$originalValue}', Novo valor: '{$newValue}'",
                        'type' => 1,
                    ]);
                }
            }
        });
    }
}
