<?php

declare(strict_types=1);

use Vural\OpenAPIFaker\Options;

// Should be set to 0 in production
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
        'specFile' => __DIR__ . '/../data/petstore.yml',
        'validateRequest' => true,
        'validateResponse' => true,
        'faker' => [
            'minItems' => 1,
            'maxItems' => 1,
            'alwaysFakeOptionals' => true,
            'strategy' => Options::STRATEGY_STATIC,
        ],
    ],
];

return $settings;