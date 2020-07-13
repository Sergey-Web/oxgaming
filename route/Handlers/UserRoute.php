<?php

declare(strict_types=1);

namespace Route\Handlers;

use App\Controller\UserController;
use App\Request\Request;
use Doctrine\ORM\EntityManagerInterface;

class UserRoute implements RouteInterface
{
    private Request $params;

    private string $url;

    private string $action;

    private array $actions = [
        'users/registration' => ['POST'],
        'users/login' => ['POST'],
    ];

    public function __construct(string $url, string $method, string $params)
    {
        $this->handleUrl($url);
        $this->validUrl();
        $this->validMethod($method);
        $this->handleAction();
        $this->params = new Request($params);
    }

    public function getAction(EntityManagerInterface $entityManager)
    {
        $action = $this->action;

        return (new UserController($entityManager))->$action($this->params);
    }

    private function handleUrl(string $url): void
    {
        $this->url = trim($url,'/');
    }

    private function validUrl(): void
    {
        if (array_key_exists($this->url, $this->actions) === false) {
            new \Exception('Route Not Found', 404);
        }
    }

    private function validMethod(string $method): void
    {
        if (array_key_exists($method, $this->actions[$this->url]) === false) {
            new \Exception('Route Not Found', 404);
        }
    }

    private function handleAction(): void
    {
        $this->action = str_replace('users/', '', $this->url);
    }
}