<?php

return [

    /*
    |--------------------------------------------------------------------------
    | GGR Display Settings
    |--------------------------------------------------------------------------
    |
    | Estas configurações controlam a exibição das seções de GGR 
    | (Gross Gaming Revenue) no dashboard administrativo.
    |
    */

    'show_ggr_cassino_clones' => true,
    
    'show_ggr_cassino_originais' => true,
    
    'show_ggr_esportes' => true,
    
    /*
    |--------------------------------------------------------------------------
    | GGR Calculation Rates
    |--------------------------------------------------------------------------
    |
    | Taxas de cálculo do GGR para diferentes tipos de jogos
    |
    */
    
    'rates' => [
        'clones' => 0.10,      // 10% para jogos clones
        'originais' => 0.20,   // 20% para jogos originais
        'esportes' => 0.20,    // 20% para esportes
    ],

];
