<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SportsBanner extends Model
{
    use HasFactory;

    /**
     * A tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'sports_banners';

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'image_url',
        'link_url',
        'order',
        'is_active',
    ];

    /**
     * Os atributos que devem ser convertidos.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Escopo para buscar apenas banners ativos.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Buscar todos os banners ativos ordenados por ordem.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getActiveBanners()
    {
        return self::active()
            ->orderBy('order')
            ->get();
    }

    /**
     * Verifica se o banner tem uma imagem local ou remota.
     *
     * @return bool
     */
    public function isLocalImage()
    {
        return strpos($this->image_url, '/img/sports-banner/') === 0;
    }

    /**
     * Obtém a URL absoluta da imagem.
     *
     * @return string
     */
    public function getImageUrlAttribute($value)
    {
        if (strpos($value, '/img/sports-banner/') === 0) {
            return url($value);
        }
        
        return $value;
    }
}