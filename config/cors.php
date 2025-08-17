<?php

return [
    'paths' => ['api/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://127.0.0.1:5500'], // your frontend URL from Live Server
    'allowed_headers' => ['*'],
    'supports_credentials' => false,
];
