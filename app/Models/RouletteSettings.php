<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RouletteSettings extends Model
{
    use HasFactory;

    protected $table = 'roulette_settings';

    protected $fillable = [
        'enable_free_daily_spin',
        'max_spins_per_day',
        'guest_spins_enabled',
        'animation_duration',
        'show_confetti',
        'sound_enabled'
    ];

    protected $casts = [
        'enable_free_daily_spin' => 'boolean',
        'max_spins_per_day' => 'integer',
        'guest_spins_enabled' => 'boolean',
        'animation_duration' => 'decimal:1',
        'show_confetti' => 'boolean',
        'sound_enabled' => 'boolean'
    ];

    /**
     * Obter as configurações da roleta (singleton)
     */
    public static function getSettings()
    {
        return self::first() ?? self::create([
            'enable_free_daily_spin' => true,
            'max_spins_per_day' => 5,
            'guest_spins_enabled' => true,
            'animation_duration' => 4.0,
            'show_confetti' => true,
            'sound_enabled' => true,
        ]);
    }

    /**
     * Atualizar configurações da roleta
     */
    public static function updateSettings(array $data)
    {
        $settings = self::getSettings();
        $settings->update($data);
        return $settings;
    }

    /**
     * Verificar se giros grátis estão habilitados
     */
    public function areFreeSpinsEnabled()
    {
        return $this->enable_free_daily_spin;
    }

    /**
     * Verificar se giros para convidados estão habilitados
     */
    public function areGuestSpinsEnabled()
    {
        return $this->guest_spins_enabled;
    }

    /**
     * Obter limite máximo de giros por dia
     */
    public function getMaxSpinsPerDay()
    {
        return $this->max_spins_per_day;
    }

    /**
     * Obter duração da animação
     */
    public function getAnimationDuration()
    {
        return $this->animation_duration;
    }
} 