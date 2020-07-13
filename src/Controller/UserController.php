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

        $save = $this->redisService->createUser($request->toArray());

        if (!$save) {
            http_response_code(500 );

            return (new Response(['phone' => 'Internal server error, user not created']))->json();
        }

        return (new Response(['status' => 'ok']))->json();
    }

    public function login(Request $request): string
    {
        $errors = (new Validation('login'))->check($request->toArray());

        if (!empty($errors)) {
            http_response_code(400);

            return (new Response($errors))->json();
        }

        if ($this->redisService->checkUser($request->toObject()->phone)) {
            http_response_code(400);

            return (new Response(['phone' => 'Invalid phone or user password']))->json();
        }

        $password = $this->redisService->checkPassword(
            $request->toObject()->phone,
            $request->toObject()->password
        );

        if ($password === false) {
            http_response_code(400);

            return (new Response(['phone' => 'Invalid phone or user password']))->json();
        }

        $user = $this->redisService->getUserData($request->toObject()->phone);

        return (new Response($user))->json();
    }
}