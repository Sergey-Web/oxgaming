<?php

declare(strict_types=1);

namespace Route\Handlers;

use App\Controller\BookController;
use App\Request\Request;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class BookRoute implements RouteInterface
{
    private string $action;

    private Request $params;

    private array $actions = [
        'POST' => 'add'
    ];

    public function __construct(string $url, string $method, string $params)
    {
        if (array_key_exists($method, $this->actions) === false) {
            new \Exception('Route Not Found', 404);
        }

        $this->action = $this->actions[$method];
        $this->params = new Request($params);
    }

    public function getAction(EntityManagerInterface $entityManager)
    {
        $action = $this->action;

        return (new BookController($entityManager))->$action($this->params);
    }
}