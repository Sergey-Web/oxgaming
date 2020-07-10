<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use stdClass;

class BookService
{
    public function checkSizeUnique(stdClass $request, EntityManagerInterface $entityManager): bool
    {
        /** @var $bookRepository BookRepository */
        $bookRepository = $entityManager->getRepository(Book::class);
        $data = $bookRepository->searchForSizeBook($request->name, mb_strlen($request->text));

        return empty($data);
    }

    public function checkTextUnique(stdClass $request, EntityManagerInterface $entityManager): bool
    {
        $res = true;
        /** @var $bookRepository BookRepository */
        $bookRepository = $entityManager->getRepository(Book::class);
        $data = $bookRepository->getText($request->name, 0);

        if (!empty($data)) {
            $res = $this->searchDuplicate($request, $data, $bookRepository);
        }

        return $res;
    }

    private function searchDuplicate(stdClass $request, array $data, BookRepository $bookRepository): bool
    {
        $res = true;
        $offset = 0;

        while(!empty($data)) {

            if(strcmp(unserialize($data[0]['text']), $request->text) === 0) {
                $res = false;
                break;
            }

            $offset++;
            $data = $bookRepository->getText($request->name, $offset);
        }

        return $res;
    }
}