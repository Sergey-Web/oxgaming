<?php

require_once __DIR__ . '/../vendor/autoload.php';

try {
    $router = new Router($_SERVER["REQUEST_URI"]);
    $router->route($_REQUEST);
} catch (\Throwable $e) {

}


