<?php

// Should be set to 0 in production

use Vural\OpenAPIFaker\Options;

error_reporting(E_ALL);

// Should be set to '0' in production
ini_set('display_errors', '1');

// Settings
$settings = [
    'cache' => [
        'dsn' => 'redis://redis:6379',
        'ttl' => 300,
    ],
    'openApi' => [
        'specFile' => __DIR__ . '/../data/spec.yaml',
        'validateRequest' => true,
        'validateRsponse' => true,
        'faker' => [
            'minItems' => 1,
            'maxItems' => 10,
            'alwaysFakeOptionals' => false,
            'strategy' => Options::STRATEGY_STATIC,
        ],
    ],
];

return $settings;