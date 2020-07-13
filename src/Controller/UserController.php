<?php

declare(strict_types=1);

namespace App\Controller;

use App\Request\Request;
use App\Response\Response;
use App\Service\RedisService;
use App\Validation\Validation;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends BaseController
{
    private RedisService $redisService;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
        $this->redisService = new RedisService();
    }

    public function registration(Request $request): string
    {
        $errors = (new Validation('registration'))->check($request->toArray());

        if (!empty($errors)) {
            http_response_code(400);

            return (new Response($errors))->json();
        }

        if (!$this->redisService->checkUser($request->toObject()->phone)) {
            http_response_code(400);

            return (new Response(['phone' => 'User with a phone already exists']))->json();
        }

        $this->redisService->createUser($request->toArray());

        return (new Response(['status' => 'ok']))->json();
    }

    public function login(Request $request): string
    {
        $errors = (new Validation('registration'))->check($request->toArray());

        if (!empty($errors)) {
            http_response_code(400);
            return (new Response($errors))->json();
        }

        return (new Response(['status' => 'ok']))->json();
    }
}