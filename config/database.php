<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/../.env');

return [
    'doctrine' => [
        'dev_mode' => false,
        'cache_dir' => __DIR__ . '/../var/cache/doctrine/cache',
        'proxy_dir' => __DIR__ . '/../var/cache/doctrine/proxy',
        'connection' => [
            'driver' => 'pdo_mysql',
            'host' => getenv('DB_HOST'),
            'user' => getenv('DB_USER'),
            'password' => getenv('DB_PASSWORD'),
            'dbname' => getenv('DB_NAME'),
        ],
        'subscribers' => [],
        'metadata_dirs' => [
            __DIR__ . '/../src/Entity'
        ],
    ],
];