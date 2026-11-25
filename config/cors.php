<?php

$rawOrigins = (string) (env('CORS_ALLOWED_ORIGINS') ?: env('APP_URL', 'http://localhost:8000'));

$allowedOrigins = array_values(array_filter(
    array_map(
        static fn (string $origin): string => rtrim(trim($origin), '/'),
        explode(',', $rawOrigins)
    ),
    static fn (string $origin): bool => $origin !== '' && $origin !== '*'
));

if ($allowedOrigins === []) {
    $allowedOrigins = ['http://localhost:8000'];
}

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Membatasi permintaan lintas origin ke endpoint API hanya dari origin
    | frontend aplikasi sendiri (CORS_ALLOWED_ORIGINS / APP_URL), tanpa
    | wildcard '*' yang membuka akses ke situs pihak ketiga.
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    'allowed_origins' => $allowedOrigins,

    'allowed_origins_patterns' => [],

    'allowed_headers' => [
        'Accept',
        'Authorization',
        'Content-Type',
        'Origin',
        'X-CSRF-TOKEN',
        'X-Requested-With',
        'X-XSRF-TOKEN',
    ],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
