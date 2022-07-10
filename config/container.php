<?php

use Cschindl\OpenAPIMock\ErrorResponseGenerator;
use Cschindl\OpenAPIMock\OpenApiMockMiddleware;
use Cschindl\OpenAPIMock\RequestValidator;
use Cschindl\OpenAPIMock\ResponseFaker;
use Cschindl\OpenAPIMock\ResponseValidator;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\Adapter\RedisTagAwareAdapter;
use Vural\OpenAPIFaker\Options;

return [
    'settings' => function () {
        return require __DIR__ . '/settings.php';
    },

    App::class => function (ContainerInterface $container) {
        AppFactory::setContainer($container);

        return AppFactory::create();
    },

    ResponseFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },
    StreamFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },
    CacheItemPoolInterface::class => function (ContainerInterface $container) {
        $client = RedisAdapter::createConnection('redis://redis:6379');

        return new RedisTagAwareAdapter($client);
    },

    OpenApiMockMiddleware::class => function (ContainerInterface $container) {
        $pathToYaml = __DIR__ . '/../data/spec.yaml';

        return new OpenApiMockMiddleware(
            RequestValidator::fromPath($pathToYaml, $container->get(CacheItemPoolInterface::class)),
            ResponseValidator::fromPath($pathToYaml, $container->get(CacheItemPoolInterface::class)),
            new ResponseFaker(
                $container->get(ResponseFactoryInterface::class),
                $container->get(StreamFactoryInterface::class),
                [
                    'minItems' => 5,
                    'maxItems' => 10,
                    'alwaysFakeOptionals' => true,
                    'strategy' => Options::STRATEGY_STATIC,
                ]
            ),
            new ErrorResponseGenerator(
                $container->get(ResponseFactoryInterface::class),
                $container->get(StreamFactoryInterface::class),
            )
        );
    },
];
