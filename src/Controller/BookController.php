<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Book;
use App\Request\Request;
use App\Response\Response;
use App\Service\BookService;
use Exception;

class BookController extends BaseController implements ControllerInterface
{
    public function add(Request $request): string
    {
        $doubleText = (new BookService)->checkTextUnique($request->toObject(), $this->entityManager);

        if ($doubleText === false) {
            throw new Exception('Part of this book already exists', 400);
        }

        $book = new Book();
        $book->setName($request->toObject()->name);
        $book->setText(serialize($request->toObject()->text));
        $book->setSize(mb_strlen($request->toObject()->text));
        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return (new Response(['status' => 'ok']))->json();
    }
}