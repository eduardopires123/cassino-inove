<?php

namespace App\Models;

//use App\Models\Adm\Providers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

//use App\Models\Adm\Logs;

class GamesApi extends Model
{
    protected $table = 'games_api';

    protected $fillable = [
        'id',
        'name',
        'slug',
        'image',
        'order_value',
        'show_home',
        'destaque',
        'distribution',
        'views',
        'maintenance',
        'status',
        'category',
        'original',
        'provider_id'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['image_url'];

    /**
     * Retorna a URL completa da imagem.
     * Se a imagem já for uma URL completa, retorna como está.
     * Caso contrário, adiciona a URL base do site.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        $image = $this->attributes['image'] ?? '';

        // Verifica se a imagem já é uma URL completa
        if (empty($image) || preg_match('/^https?:\/\//i', $image)) {
            return $image;
        }

        // Adiciona a URL base do site ao caminho da imagem
        return url($image);
    }

    /**
     * Relacionamento com o provider
     */
    public function provider()
    {
        return $this->belongsTo(\App\Models\Admin\Providers::class, 'provider_id');
    }

    protected static function booted()
    {
        /*parent::boot();

        static::updated(function (GamesApi $GamesApi) {
            $userId = Auth::id();

            $dirtyAttributes = $GamesApi->getDirty();
            unset($dirtyAttributes['updated_at']);

            if (!empty($dirtyAttributes)) {
                foreach ($dirtyAttributes as $column => $newValue) {
                    $originalValue = $GamesApi->getOriginal($column);

                    if ($column == 'show_home') {
                        $column = "Exibir na Home";
                    }elseif ($column == 'destaque') {
                        $column = "Destaque";
                    }elseif ($column == 'status') {
                        $column = "Ativo";
                    }

                    Logs::create([
                        'updated_by' => $userId,
                        'user_id' => 0,
                        'log' => "Jogos: A coluna '{$column}' de '{$GamesApi->name}' foi alterada. Valor original: '{$originalValue}', Novo valor: '{$newValue}'",
                        'type' => 1,
                    ]);
                }
            }
        });*/
    }
}
