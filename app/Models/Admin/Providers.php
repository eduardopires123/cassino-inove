<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Providers extends Model
{
    protected $table = 'providers';

    protected $fillable = [
        'name',
        'provider_name',
        'name_home',
        'distribution',
        'showmain',
        'order_value',
        'active',
        'img',
        'wallets',
    ];

    public function games()
    {
        return $this->hasMany(\App\Models\GamesApi::class, 'provider_id');
    }

    protected static function booted()
    {
        parent::boot();

        static::updated(function (Providers $Providers) {
            $userId = Auth::id();

            $dirtyAttributes = $Providers->getDirty();
            unset($dirtyAttributes['updated_at']);

            if (!empty($dirtyAttributes)) {
                foreach ($dirtyAttributes as $column => $newValue) {
                    $originalValue = $Providers->getOriginal($column);

                    if ($column == 'showmain') {
                        $column = "Exibir na Home";
                    }elseif ($column == 'order_value') {
                        $column = "Ordem";
                    }elseif ($column == 'active') {
                        $column = "Ativo";
                    }

                    Logs::create([
                        'updated_by' => $userId,
                        'user_id' => 0,
                        'log' => "Provedores: A coluna '{$column}' de '{$Providers->name}' foi alterada. Valor original: '{$originalValue}', Novo valor: '{$newValue}'",
                        'type' => 1,
                    ]);
                }
            }
        });
    }
}