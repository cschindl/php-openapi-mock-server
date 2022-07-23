<?php

use Cschindl\OpenAPIMock\ErrorResponseGenerator;
use Cschindl\OpenAPIMock\OpenApiMockMiddleware;
use Cschindl\OpenAPIMock\ResponseFaker;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\Adapter\RedisTagAwareAdapter;

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
        $pathToYaml = $container->get('settings')['openApi']['specFile'];

        $validatorBuilder = (new ValidatorBuilder)->fromYamlFile($pathToYaml);
        $cache = $container->get(CacheItemPoolInterface::class);
        if ($cache instanceof CacheItemPoolInterface) {
            $validatorBuilder->setCache($cache);
        }

        return new OpenApiMockMiddleware(
            $validatorBuilder,
            new ResponseFaker(
                $container->get(ResponseFactoryInterface::class),
                $container->get(StreamFactoryInterface::class),
                $container->get('settings')['openApi']['faker']
            ),
            new ErrorResponseGenerator(
                $container->get(ResponseFactoryInterface::class),
                $container->get(StreamFactoryInterface::class),
            )
        );
    },
];
