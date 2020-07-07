<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Book;
use App\Request\Request;
use App\Response\Response;
use Doctrine\ORM\EntityManagerInterface;

class BookController extends BaseController implements ControllerInterface
{
    private EntityManagerInterface $em;

    public function add(Request $request): string
    {
        $res = $this->entityManager->find(Book::class, 1);
        var_dump($res);die;
        return (new Response($request->toArray()))->json();
    }
}