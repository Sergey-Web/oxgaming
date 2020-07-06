<?php

declare(strict_types=1);

namespace Route\Handlers;

use App\Controller\BookController;
use App\Request\Request;

class BookRoute implements RouteInterface
{
    private $action;

    private Request $params;

    private array $actions = [
        'POST' => 'add'
    ];


    public function __construct(string $method, string $params)
    {
        if (array_key_exists($method, $this->actions) === false) {
            new \Exception('Route Not Found', 404);
        }

        $this->action = $this->actions[$method];
        $this->params = new Request($params);
    }

    public function get()
    {
        $action = $this->action;

        return (new BookController())->$action($this->params);
    }
}