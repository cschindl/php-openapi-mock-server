<?php

use Cschindl\OpenApiMockMiddleware\OpenApiMockMiddleware;
use Cschindl\OpenApiMockMiddlewareServer\PrepareOpenApiMockMiddleware;
use Slim\App;

return function (App $app) {
    // Register middleware
    $app->add(OpenApiMockMiddleware::class);
    $app->add(PrepareOpenApiMockMiddleware::class);
};