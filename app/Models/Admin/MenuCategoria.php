<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MenuCategoria extends Model
{
    protected $table = 'menu_categoria';

    protected $fillable = [
        'id',
        'id_cliente',
        'tipo',
        'ordem',
        'nome',
        'active',
    ];

    protected static function booted()
    {
        parent::boot();

        static::updated(function (MenuCategoria $MenuCategoria) {
            $userId = Auth::id();

            $dirtyAttributes = $MenuCategoria->getDirty();
            unset($dirtyAttributes['updated_at']);

            if (!empty($dirtyAttributes)) {
                foreach ($dirtyAttributes as $column => $newValue) {
                    $originalValue = $MenuCategoria->getOriginal($column);

                    if ($column == 'tipo') {
                        $column = "Tipo";
                    }elseif ($column == 'ordem') {
                        $column = "Ordem";
                    }elseif ($column == 'nome') {
                        $column = "Nome";
                    }elseif ($column == 'active') {
                        $column = "Ativo";
                    }

                    Logs::create([
                        'updated_by' => $userId,
                        'user_id' => 0,
                        'log' => "Categoria Menu: A coluna '{$column}' de '{$MenuCategoria->nome}' foi alterada. Valor original: '{$originalValue}', Novo valor: '{$newValue}'",
                        'type' => 1,
                    ]);
                }
            }
        });
    }
}