<?php

declare(strict_types=1);

namespace App;

use Cschindl\OpenAPIMock\OpenApiMockMiddleware;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\Adapter\RedisTagAwareAdapter;
use Vural\OpenAPIFaker\Options;

class OpenApiMockMiddlewareFactory implements FactoryInterface
{
    /**
     * Create an object
     *
     * @param  string             $requestedName
     * @param  null|array<mixed>  $options
     * @return OpenApiMockMiddleware
     * @throws ServiceNotFoundException If unable to resolve the service.
     * @throws ServiceNotCreatedException If an exception is raised when creating a service.
     * @throws ContainerExceptionInterface If any other error occurs.
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $pathToYaml = __DIR__ . '/../../../data/spec.yaml';

        $client = RedisAdapter::createConnection('redis://redis:6379');
        $cache = new RedisTagAwareAdapter($client);

        return new OpenApiMockMiddleware(
            $container->get(ResponseFactoryInterface::class),
            $container->get(StreamFactoryInterface::class),
            $cache,
            $pathToYaml,
            [
                'minItems' => 5,
                'maxItems' => 10,
                'alwaysFakeOptionals' => true,
                'strategy' => Options::STRATEGY_STATIC,
            ]
        );
    }
}
