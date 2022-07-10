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
    ],
    'openApi' => [
        'specFile' => __DIR__ . '/../data/spec.yaml',
        'faker' => [
            'minItems' => 5,
            'maxItems' => 10,
            'alwaysFakeOptionals' => true,
            'strategy' => Options::STRATEGY_STATIC,
        ],
    ],
];

return $settings;