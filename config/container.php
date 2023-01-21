<?php

use Cschindl\OpenApiMockMiddleware\OpenApiMockMiddleware;
use Cschindl\OpenApiMockMiddleware\OpenApiMockMiddlewareConfig;
use Cschindl\OpenApiMockMiddleware\OpenApiMockMiddlewareFactory;
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
        $settings = $container->get('settings')['cache'];

        return new RedisTagAwareAdapter(
            RedisAdapter::createConnection($settings['dsn']),
            '',
            $settings['ttl'],
        );
    },
    OpenApiMockMiddleware::class => function (ContainerInterface $container) {
        $settings = $container->get('settings')['openApi'];

        $options = (new Options())
            ->setMinItems($settings['faker']['minItems'])
            ->setMaxItems($settings['faker']['maxItems'])
            ->setAlwaysFakeOptionals($settings['faker']['alwaysFakeOptionals'])
            ->setStrategy($settings['faker']['strategy']);

        $config = new OpenApiMockMiddlewareConfig(
            $settings['validateRequest'],
            $settings['validateRsponse'],
            $options
        );

        return OpenApiMockMiddlewareFactory::createFromYamlFile(
            $settings['specFile'],
            $container->get(ResponseFactoryInterface::class),
            $container->get(StreamFactoryInterface::class),
            $config,
            $container->get(CacheItemPoolInterface::class),
        );
    },
];
