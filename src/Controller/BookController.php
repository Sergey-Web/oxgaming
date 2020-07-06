<?php

declare(strict_types=1);

namespace App\Controller;

use App\Request\Request;
use App\Response\Response;

class BookController implements ControllerInterface
{
    public function add(Request $request): string
    {
        var_dump($request->toObject()->book);
//        return (new Response($request->toJson()))->json();
    }
}