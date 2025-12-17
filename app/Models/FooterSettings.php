<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class FooterSettings extends Model
{
    use HasFactory;

    /**
     * A tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'footer_settings';

    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'footer_text',
        'footer_subtext',
        'contact_button_url',
        'show_autorizado_cassino',
        'show_social_links',
        'topbar_text',
        'topbar_button_text',
        'topbar_button_url',
        'show_topbar',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'show_autorizado_cassino' => 'boolean',
        'show_social_links' => 'boolean',
        'show_topbar' => 'boolean',
        'updated_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * Retorna as configurações do rodapé ou cria um registro padrão se não existir.
     *
     * @return \App\Models\FooterSettings
     */
    public static function getSettings()
    {
        return Cache::remember('inline_footer_cache', now()->addHours(6), function () {
            $settings = self::first();

            if (!$settings) {
                $settings = self::create([
                    'footer_text' => config('app.name') . ' e é o melhor site de cassino 🎰 e apostas esportivas ⚽ com diversas opções de esportes para apostar, jogos de cassino para jogar e promoções exclusivas 🎁. Com uma plataforma intuitiva e segura, oferecemos milhares de jogos de cassino, jogos de esportes e suporte ao cliente 24/7.',
                    'footer_subtext' => config('app.name') . ' é uma empresa registrada no Brasil, sob o CNPJ 56.875.122/0001-86',
                    'contact_button_url' => '#',
                    'show_autorizado_cassino' => true,
                    'show_social_links' => true,
                    'topbar_text' => 'Indique um amigo e ganhe R$10 em bônus!',
                    'topbar_button_text' => 'Resgatar',
                    'topbar_button_url' => '#',
                    'show_topbar' => true,
                ]);
            }

            return $settings;
        });
    }
}
