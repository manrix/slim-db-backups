<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Slim\App;

$containerBuilder = new ContainerBuilder();

// Set up settings
$containerBuilder->addDefinitions(__DIR__ . '/container.php');

// Build PHP-DI Container instance
$container = $containerBuilder->build();

// Create App instance
$app = $container->get(App::class);

// Register routes
$routes = require __DIR__ . '/routes.php';
($routes)($app);

return $app;
