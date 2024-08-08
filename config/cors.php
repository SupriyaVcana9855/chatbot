<?php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'chatbot-script/*', 'botman'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
