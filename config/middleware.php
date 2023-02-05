<?php

declare(strict_types=1);

use Cschindl\OpenApiMockMiddleware\OpenApiMockMiddleware;
use Cschindl\OpenApiMockServer\PrepareOpenApiMockMiddleware;
use Slim\App;

return function (App $app) {
    // Register middleware
    $app->add(OpenApiMockMiddleware::class);
    $app->add(PrepareOpenApiMockMiddleware::class);
};