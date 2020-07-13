<?php

declare(strict_types=1);

namespace App\Controller;

use App\Request\Request;
use App\Response\Response;

class UserController extends BaseController
{
    public function registration(Request $request): string
    {
        return (new Response(['status' => 'ok']))->json();
    }

    public function login(Request $request): string
    {
        return (new Response(['status' => 'ok']))->json();
    }
}