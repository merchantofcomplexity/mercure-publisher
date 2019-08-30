<?php

use MerchantOfComplexity\MercurePublisher\MercureJwtKey;

return [

    'hub' => env('MERCURE_PUBLISHER_URL'),

    'jwt' => env('MERCURE_PUBLISHER_JWT'),

    'jwt_provider' => MercureJwtKey::class,

    'auto_discover' => true
];
