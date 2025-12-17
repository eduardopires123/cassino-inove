<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MenuItems extends Model
{
    protected $table = 'menu_items';

    protected $fillable = [
        'id',
        'id_cliente',
        'categoria',
        'nome',
        'slug',
        'ordem',
        'icone',
        'link',
        'active',
    ];

    protected static function booted()
    {
        parent::boot();

        static::updated(function (MenuItems $MenuItems) {
            $userId = Auth::id();

            $dirtyAttributes = $MenuItems->getDirty();
            unset($dirtyAttributes['updated_at']);

            if (!empty($dirtyAttributes)) {
                foreach ($dirtyAttributes as $column => $newValue) {
                    $originalValue = $MenuItems->getOriginal($column);

                    if ($column == 'categoria') {
                        $column = "Categoria";
                    }elseif ($column == 'nome') {
                        $column = "Nome";
                    }elseif ($column == 'ordem') {
                        $column = "Ordem";
                    }elseif ($column == 'active') {
                        $column = "Ativo";
                    }elseif ($column == 'icone') {
                        $column = "Ícone";
                    }elseif ($column == 'link') {
                        $column = "Link";
                    }

                    /*Logs::create([
                        'updated_by' => $userId,
                        'user_id' => 0,
                        'log' => "Items Menu: A coluna '{$column}' de '{$MenuItems->nome}' foi alterada. Valor original: '{$originalValue}', Novo valor: '{$newValue}'",
                        'type' => 1,
                    ]);*/
                }
            }
        });
    }

    public function Categoria()
    {
        return $this->belongsTo(MenuCategoria::class, 'categoria', 'id');
    }
}