<?php

declare(strict_types=1);


namespace Route;

use Exception;
use Route\Handlers\{BookRoute, LogRoute, RouteInterface, UserRoute};
use stdClass;


class Router implements RouterInterface
{
    private string $method;

    private string $params;

    private string $requestUri;

    private RouteInterface $route;

    private array $routes = [
        'books' => BookRoute::class,
        'logs' => LogRoute::class,
        'users' => UserRoute::class,
    ];

    public function __construct(string $requestUri, string $method, string $params)
    {
        $this->requestUri = $requestUri;
        $this->method = $method;
        $this->params = $params;
        $this->routeSelection();

    }

    public function buildRoute()
    {
        return $this->route->get();
    }

    private function routeSelection(): void
    {
        $uri = explode('/', $this->requestUri);

        if (array_key_exists($uri[1], $this->routes) === false) {
            new Exception('Route Not Found', 404);
        }

        $this->route = new $this->routes[$uri[1]](
            $this->method,
            $this->params
        );
    }

//    public function __call(string $name, array $arguments): void
//    {
//        new \Exception('Route Not Found', 404);
//    }

//    private function chooseAction()
//    {
//        if (array_key_exists($this->method, $this->actions) === false) {
//            new \Exception('Route Not Found', 404);
//        }
//
//        $this->actions[$this->method]($this->params);
//    }
}