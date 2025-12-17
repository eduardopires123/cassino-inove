<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class CustomCSS extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'custom_css';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'css_swiper_theme_color',
        'css_primary_color',
        'css_secondary_color',
        'css_accent_color',
        'css_background_color',
        'css_text_primary_color',
        'css_background_opacity',
        'css_background_opacity_hover',
        'css_header_color',
        'css_deposit_color',
        'css_gradient_color',
        'css_gradient_color_to',
        'css_tw_shadow',
        'css_text_top_color',
        'css_background_profile',
        'css_text_btn_primary',
        'css_color_button1',
        'css_color_button2',
        'css_color_button3',
        'css_color_button4',
        'css_color_texts',
        'css_sidebar_color',
        'custom',
        'active_theme',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'updated_at' => 'datetime',
    ];
}