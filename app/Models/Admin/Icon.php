<?php

namespace App\Models\Admin;

use App\Models\GamesApi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Icon extends Model
{
    protected $table = 'icons';

    protected $fillable = [
        'id',
        'name',
        'svg',
        'link',
        'game_id',
        'ordem',
        'active',
        'type',
        'hot',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
        'hot' => 'integer',
    ];

    /**
     * Get the game associated with the icon.
     */
    public function game()
    {
        return $this->belongsTo(GamesApi::class, 'game_id', 'id');
    }

    /**
     * Formata o nome do ícone processando as tags small
     *
     * @return string
     */
    public function getFormattedNameAttribute()
    {
        if (strpos($this->name, '<small>') !== false && strpos($this->name, '</small>') !== false) {
            return str_replace(['<small>', '</small>'], ['<small>', '</small>'], $this->name);
        }
        
        return $this->name;
    }

    protected static function booted()
    {
        parent::boot();

        static::updated(function (Icon $icon) {
            $userId = Auth::id();

            $dirtyAttributes = $icon->getDirty();
            unset($dirtyAttributes['updated_at']);

            if (!empty($dirtyAttributes)) {
                foreach ($dirtyAttributes as $column => $newValue) {
                    $originalValue = $icon->getOriginal($column);

                    if ($column == 'name') {
                        $column = "Nome";
                    } elseif ($column == 'svg') {
                        $column = "SVG";
                    } elseif ($column == 'link') {
                        $column = "Link";
                    } elseif ($column == 'game_id') {
                        $column = "ID do Jogo";
                    } elseif ($column == 'ordem') {
                        $column = "Ordem";
                    } elseif ($column == 'active') {
                        $column = "Ativo";
                    }

                    // If you have a Logs model, you can use it here
                    // Logs::create([
                    //     'updated_by' => $userId,
                    //     'user_id' => 0,
                    //     'log' => "Icon: A coluna '{$column}' foi alterada. Valor original: '{$originalValue}', Novo valor: '{$newValue}'",
                    //     'type' => 1,
                    // ]);
                }
            }
        });
    }
} 