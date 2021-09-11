<?php
declare(strict_types=1);

namespace Test\Functional;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Slim\App;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Factory\AppFactory;

class WebTestCase extends TestCase
{
    protected function app(): App
    {
        $container = require __DIR__.'/../../config/container.php';

        $app = AppFactory::createFromContainer($container);

        (require __DIR__ . '/../../config/middleware.php')($app, $container);
        (require __DIR__ . '/../../config/routes.php')($app);

        return $app;
    }

    protected static function request(string $method, string $path): ServerRequestInterface
    {
        return (new ServerRequestFactory())->createServerRequest($method, $path);
    }

    protected static function json(string $method, string $path): ServerRequestInterface
    {
        return self::request($method, $path)
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json');
    }
}