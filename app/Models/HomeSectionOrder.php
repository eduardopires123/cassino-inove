<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class HomeSectionOrder extends Model
{
    use HasFactory;

    protected $table = 'home_sections_order';

    protected $fillable = [
        'section_key',
        'section_name',
        'position',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'position' => 'integer',
    ];

    /**
     * Obter todas as seções ordenadas pela posição
     */
    public static function getOrderedSections()
    {
        return Cache::remember('home_sections_order', 3600, function () {
            return self::where('is_active', true)
                ->orderBy('position')
                ->get();
        });
    }

    /**
     * Obter seções como array com chave sendo a section_key
     */
    public static function getSectionsMap()
    {
        return Cache::remember('home_sections_map', 3600, function () {
            return self::where('is_active', true)
                ->orderBy('position')
                ->pluck('position', 'section_key')
                ->toArray();
        });
    }

    /**
     * Atualizar a ordem das seções
     */
    public static function updateOrder(array $sectionsOrder)
    {
        foreach ($sectionsOrder as $sectionKey => $position) {
            self::where('section_key', $sectionKey)->update([
                'position' => $position
            ]);
        }

        // Limpar cache
        self::clearCache();
    }

    /**
     * Ativar/desativar seção
     */
    public static function toggleSection($sectionKey, $isActive)
    {
        self::where('section_key', $sectionKey)->update([
            'is_active' => $isActive
        ]);

        self::clearCache();
    }

    /**
     * Limpar cache das seções
     */
    public static function clearCache()
    {
        Cache::forget('home_sections_order');
        Cache::forget('home_sections_map');
    }

    /**
     * Verificar se uma seção está ativa
     */
    public static function isSectionActive($sectionKey)
    {
        $sectionsMap = self::getSectionsMap();
        return isset($sectionsMap[$sectionKey]);
    }

    /**
     * Obter posição de uma seção
     */
    public static function getSectionPosition($sectionKey)
    {
        $sectionsMap = self::getSectionsMap();
        return $sectionsMap[$sectionKey] ?? 999;
    }
} 