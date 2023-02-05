<?php

declare(strict_types=1);

namespace Cschindl\OpenApiMockServer;

use Cschindl\OpenApiMockMiddleware\OpenApiMockMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PrepareOpenApiMockMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $handler->handle($request->withAddedHeader(OpenApiMockMiddleware::HEADER_OPENAPI_MOCK_ACTIVE, 'true'));
    }
}
