<?php

$origins = array_filter(array_map(
    static fn (string $url): string => rtrim(trim($url), '/'),
    array_merge(
        ['http://localhost:5173'],
        [env('FRONTEND_URL', '')],
        explode(',', (string) env('FRONTEND_URLS', '')),
    )
));

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
    'allowed_origins' => array_values(array_unique($origins)),
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['Accept', 'Authorization', 'Content-Type', 'Origin', 'X-Requested-With'],
    'exposed_headers' => [],
    'max_age' => 600,
    'supports_credentials' => false,
];
