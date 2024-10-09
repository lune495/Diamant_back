<?php

return [

    /*
    |--------------------------------------------------------------------------
    | FICHIER DE CONFIGURATION DES VARIABLES D'ENVIRONNMENT
    |--------------------------------------------------------------------------
    |
    | Chaque fois qu'une variable est modifiée dans ce fichier, il faudra
    | faire à nouveau php artisan config:cache pour rendre la modification disponible.
    |
    */
    'APP_URL' => env('APP_URL', 'https://chroflix.com'),
    'PAYMENT_REDIRECT_PATH' => env('PAYMENT_REDIRECT_PATH', '/payment/checkout/'),
    'PAYMENT_REQUEST_PATH' => env('PAYMENT_REQUEST_PATH', '/api/payment/request-payment'),
    'MOBILE_CANCEL_URL' => env('MOBILE_CANCEL_URL', 'https://paytech.sn/mobile/cancel'),
    'MOBILE_SUCCESS_URL' => env('MOBILE_SUCCESS_URL', 'https://paytech.sn/mobile/success'),
];