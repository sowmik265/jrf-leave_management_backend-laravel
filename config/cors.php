<?php

return [

    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
        'login',
        'logout',
        'user', // FIXED (removed leading slash)
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://192.168.7.103:3000', // FIXED IP
        'http://localhost:3000',
        'http://127.0.0.1:3000',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'supports_credentials' => true,

    'exposed_headers' => [],

    'max_age' => 0,
];
