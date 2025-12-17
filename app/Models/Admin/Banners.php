<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Banners extends Model
{
    protected $table = 'banners';

    protected $fillable = [
        'id',
        'imagem',
        'link',
        'ordem',
        'active',
        'mobile',
        'tipo',
    ];

    protected static function booted()
    {
        parent::boot();

        static::updated(function (Banners  $Banners ) {
            $userId = Auth::id();

            $dirtyAttributes = $Banners->getDirty();
            unset($dirtyAttributes['updated_at']);

            if (!empty($dirtyAttributes)) {
                foreach ($dirtyAttributes as $column => $newValue) {
                    $originalValue = $Banners->getOriginal($column);

                    if ($column == 'imagem') {
                        $column = "Imagem";
                    }elseif ($column == 'ordem') {
                        $column = "Ordem";
                    }elseif ($column == 'active') {
                        $column = "Ativo";
                    }elseif ($column == 'mobile') {
                        $column = "Mobile";
                    }elseif ($column == 'tipo') {
                        $column = "Tipo";
                    }

                    Logs::create([
                        'updated_by' => $userId,
                        'user_id' => 0,
                        'log' => "Banners: A coluna '{$column}' foi alterada. Valor original: '{$originalValue}', Novo valor: '{$newValue}'",
                        'type' => 1,
                    ]);
                }
            }
        });
    }
}