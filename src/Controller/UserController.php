<?php

declare(strict_types=1);

namespace App\Controller;

use App\Request\Request;
use App\Response\Response;
use App\Validation\Validation;

class UserController extends BaseController
{
    public function registration(Request $request): string
    {
        $errors = (new Validation('registration'))->check($request->toArray());

        if (!empty($errors)) {
            http_response_code(400);
            return (new Response($errors))->json();
        }

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