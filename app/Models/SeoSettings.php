<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoSettings extends Model
{
    use HasFactory;

    protected $table = 'seo_settings';

    protected $fillable = [
        'site_title',
        'site_subtitle',
        'site_description',
        'site_keywords',
        'site_author',
        'site_robots',
        'canonical_url',
        'og_title',
        'og_description',
        'og_image',
        'og_type',
        'og_site_name',
        'og_locale',
        'twitter_card',
        'twitter_site',
        'twitter_creator',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'google_site_verification',
        'google_analytics_id',
        'google_tag_manager_id',
        'facebook_app_id',
        'facebook_pages_id',
        'facebook_pixel_id',
        'tiktok_pixel_id',
        'snapchat_pixel_id',
        'pinterest_pixel_id',
        'linkedin_pixel_id',
        'twitter_pixel_id',
        'custom_pixels',
        'structured_data',
        'custom_meta_tags',
        'custom_head_scripts',
        'custom_body_scripts',
        'favicon',
        'apple_touch_icon',
        'is_active',
        // PWA Fields
        'pwa_name',
        'pwa_short_name',
        'pwa_description',
        'pwa_theme_color',
        'pwa_background_color',
        'pwa_display',
        'pwa_icon_192',
        'pwa_icon_512',
        'pwa_start_url',
        'pwa_enabled'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'pwa_enabled' => 'boolean',
    ];

    /**
     * Obter configurações SEO ativas
     */
    public static function getSettings()
    {
        return self::where('is_active', true)->first() ?? new self();
    }

    /**
     * Obter configurações PWA (independente de is_active)
     * Permite que o PWA funcione mesmo quando o SEO está desativado
     */
    public static function getPwaSettings()
    {
        return self::first() ?? new self();
    }

    /**
     * Salvar ou atualizar configurações SEO
     */
    public static function saveSettings($data)
    {
        $settings = self::first();
        
        if ($settings) {
            $settings->update($data);
        } else {
            $settings = self::create($data);
        }
        
        return $settings;
    }

    /**
     * Obter título para SEO
     */
    public function getSeoTitle($pageTitle = null)
    {
        if ($pageTitle) {
            return $pageTitle . ' - ' . ($this->site_title ?? config('app.name'));
        }
        
        return $this->site_title ?? config('app.name');
    }

    /**
     * Obter descrição para SEO
     */
    public function getSeoDescription($pageDescription = null)
    {
        return $pageDescription ?? $this->site_description ?? 'Plataforma de jogos online';
    }

    /**
     * Obter imagem para SEO
     */
    public function getSeoImage($pageImage = null)
    {
        if ($pageImage) {
            return url($pageImage);
        }
        
        if ($this->og_image) {
            return url($this->og_image);
        }
        
        return url('img/logo/logo.png');
    }

    /**
     * Obter URL canônica
     */
    public function getCanonicalUrl($pageUrl = null)
    {
        return $pageUrl ?? $this->canonical_url ?? url()->current();
    }

    /**
     * Verificar se Google Analytics está configurado
     */
    public function hasGoogleAnalytics()
    {
        return !empty($this->google_analytics_id);
    }

    /**
     * Verificar se Google Tag Manager está configurado
     */
    public function hasGoogleTagManager()
    {
        return !empty($this->google_tag_manager_id);
    }

    /**
     * Verificar se Facebook App ID está configurado
     */
    public function hasFacebookAppId()
    {
        return !empty($this->facebook_app_id);
    }

    /**
     * Obter structured data como array
     */
    public function getStructuredDataArray()
    {
        if (empty($this->structured_data)) {
            return [];
        }
        
        return json_decode($this->structured_data, true) ?? [];
    }

    /**
     * Obter meta tags customizadas como array
     */
    public function getCustomMetaTagsArray()
    {
        if (empty($this->custom_meta_tags)) {
            return [];
        }
        
        return json_decode($this->custom_meta_tags, true) ?? [];
    }

    /**
     * Verificar se Facebook Pixel está configurado
     */
    public function hasFacebookPixel()
    {
        return !empty($this->facebook_pixel_id);
    }

    /**
     * Verificar se TikTok Pixel está configurado
     */
    public function hasTikTokPixel()
    {
        return !empty($this->tiktok_pixel_id);
    }

    /**
     * Verificar se Snapchat Pixel está configurado
     */
    public function hasSnapchatPixel()
    {
        return !empty($this->snapchat_pixel_id);
    }

    /**
     * Verificar se Pinterest Pixel está configurado
     */
    public function hasPinterestPixel()
    {
        return !empty($this->pinterest_pixel_id);
    }

    /**
     * Verificar se LinkedIn Pixel está configurado
     */
    public function hasLinkedInPixel()
    {
        return !empty($this->linkedin_pixel_id);
    }

    /**
     * Verificar se Twitter Pixel está configurado
     */
    public function hasTwitterPixel()
    {
        return !empty($this->twitter_pixel_id);
    }

    /**
     * Obter pixels personalizados como array
     */
    public function getCustomPixelsArray()
    {
        if (empty($this->custom_pixels)) {
            return [];
        }
        
        return json_decode($this->custom_pixels, true) ?? [];
    }

    /**
     * Obter todos os pixels configurados
     */
    public function getAllPixels()
    {
        return [
            'facebook' => $this->facebook_pixel_id,
            'tiktok' => $this->tiktok_pixel_id,
            'snapchat' => $this->snapchat_pixel_id,
            'pinterest' => $this->pinterest_pixel_id,
            'linkedin' => $this->linkedin_pixel_id,
            'twitter' => $this->twitter_pixel_id,
            'custom' => $this->getCustomPixelsArray()
        ];
    }
} 