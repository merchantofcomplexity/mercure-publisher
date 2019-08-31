<?php

use Symfony\Component\Mercure\Jwt\StaticJwtProvider;

return [

    'hub' => env('MERCURE_PUBLISHER_URL', 'http://127.0.0.1:3000/hub'),

    'jwt' => env('MERCURE_PUBLISHER_JWT'),

    'jwt_provider' => StaticJwtProvider::class,

    'auto_discover' => true
];
