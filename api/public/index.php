<?php

declare(strict_types=1);

use Slim\Factory\AppFactory;

http_response_code(500);

require __DIR__ . '/../vendor/autoload.php';

$container = require __DIR__ . '/../config/container.php';

$app = AppFactory::createFromContainer($container);

$app->run();
