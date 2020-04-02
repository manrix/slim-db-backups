<?php

$settings = [
    // Show error details
    'displayErrorDetails' => false,

    // Path where backups will be saved
    'backups_path' => __DIR__ . '/../var/backups',

    // Path where routes cache file will be saved
    'routes_cache' => __DIR__ . '/../var/cache/routes.php',

    // Session settings
    'session' => [
        'name' => 'slim-db-backups',
        'cookie_httponly' => true,
    ],

    // Twig renderer settings
    'twig' => [
        'path' => __DIR__ . '/../templates',
        'options' => [
            'cache_path' => __DIR__ . '/../var/cache/twig',
            'debug' => false,
        ]
    ],

    // Database connection settings
    'db' => [
        'driver' => 'sqlite',
        'name' => __DIR__ . '/../var/database/database.sqlite',
    ],

    // Database dumper settings
    'dumper' => [
        'mysql_binary' => null,
        'pgsql_binary' => null,
        'sqlite_binary' => null,
    ],
];

if (file_exists(__DIR__ . '/../env.php')) {
    require __DIR__ . '/../env.php';
}

return $settings;
