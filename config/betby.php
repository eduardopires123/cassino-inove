<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Betby Sportsbook Configuration
    |--------------------------------------------------------------------------
    |
    | Configurações para integração com o Betby Sportsbook
    |
    */

    'brand_id' => '2569593371207282692',

    'operator_id' => '2569593371207282691',

    'api_url' => 'https://ui.invisiblesport.com',

    'bt_library_url' => 'https://ui.invisiblesport.com/bt-renderer.min.js',

    'external_api_url' => 'https://external-api.invisiblesport.com/api/v1/external_api/',

    'production_api_url' => 'https://URL.sptpub.com',

    'theme_name' => 'mago2bet',

    'currency' => 'BRL',

    'private_key' => storage_path('app/betby/private.pem'),

    'public_key' => storage_path('app/betby/public.pem'),

    'jwt_algorithm' => 'ES256',

    'token_expiry_hours' => 24,

    'is_production' => false,

    'feature_flags' => [
        'is_cashout_available' => true,
        'is_match_tracker_available' => true,
    ],

    'languages' => [
        'pt_BR' => 'pt',
        'en' => 'en',
        'es' => 'es',
    ],

    'bet_slip' => [
        'offset_top' => 80,
        'offset_bottom' => 0,
        'offset_right' => 0,
        'z_index' => 999,
    ],

    'banners' => [
        'hero_sliding_rate' => 5,
        'line_sliding_rate' => 6,
    ],

    'odds_format' => 'decimal',

    'widget_types' => [
        'soccer' => '/soccer',
        'live' => '/live',
        'popular' => '/popular',
        'today' => '/today',
        'tomorrow' => '/tomorrow',
        'basketball' => '/basketball',
        'tennis' => '/tennis',
        'esports' => '/esports',
        'football' => '/football',
        'volleyball' => '/volleyball',
    ],
];
