<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Route\Router;

try {
    $params = file_get_contents("php://input");
    $router = new Router($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD'], $params);
    $router->buildRoute();
} catch (Throwable $e) {
    echo new Exception($e->getMessage(), $e->getCode());
}


