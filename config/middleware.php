<?php

use Cschindl\OpenAPIMock\OpenApiMockMiddleware;
use Slim\App;

return function (App $app) {
    // Register middleware
    $app->add(OpenApiMockMiddleware::class);
};