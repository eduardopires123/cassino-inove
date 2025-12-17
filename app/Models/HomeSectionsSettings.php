<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeSectionsSettings extends Model
{
    use HasFactory;

    /**
     * A tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'home_sections_settings';

    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'show_live_casino',
        'show_new_games',
        'show_most_viewed_games',
        'show_top_wins',
        'show_last_bets',
        'show_roulette',
        'show_whatsapp_float',
        'show_raspadinhas_home',
        'custom_title_live_casino',
        'custom_title_new_games',
        'custom_title_most_viewed_games',
        'custom_title_top_wins',
        'custom_title_most_paid',
        'custom_title_studios',
        'custom_title_top_raspadinhas',
        'custom_title_modo_surpresa',
        'custom_title_sports_icons',
        'custom_title_last_bets',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'show_live_casino' => 'boolean',
        'show_new_games' => 'boolean',
        'show_most_viewed_games' => 'boolean',
        'show_top_wins' => 'integer',
        'show_last_bets' => 'integer',
        'show_roulette' => 'boolean',
        'show_whatsapp_float' => 'boolean',
        'show_raspadinhas_home' => 'boolean',
    ];

    /**
     * Obter a configuração atual ou criar uma padrão se não existir
     *
     * @return HomeSectionsSettings
     */
    public static function getSettings()
    {
        $settings = self::first();
        
        if (!$settings) {
            $settings = self::create([
                'show_live_casino' => true,
                'show_new_games' => true,
                'show_most_viewed_games' => true,
                'show_top_wins' => 1,
                'show_last_bets' => 1,
                'show_roulette' => true,
                'show_whatsapp_float' => true,
                'show_raspadinhas_home' => true,
            ]);
        }
        
        return $settings;
    }

    /**
     * Obter título personalizado ou usar fallback
     *
     * @param string $field Campo do título (ex: 'custom_title_live_casino')
     * @param string $fallback Título padrão (tradução)
     * @return string
     */
    public function getSectionTitle($field, $fallback)
    {
        $customTitle = $this->$field;
        return !empty($customTitle) ? $customTitle : $fallback;
    }
}
