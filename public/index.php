<?php

use Route\Router;
use Symfony\Component\Dotenv\Dotenv;


require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/../.env');

require_once __DIR__ . '/../bootstrap.php';

try {
    $params = file_get_contents("php://input");
    $router = new Router($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD'], $params);
    $router->buildRoute();
} catch (Throwable $e) {
    echo new Exception($e->getMessage(), $e->getCode());
}


