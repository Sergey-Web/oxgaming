<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Book;
use Doctrine\ORM\EntityRepository;

class BookRepository extends EntityRepository
{
    public function searchForSizeBook(string $bookName, int $size)
    {
        /** @var $db EntityRepository */
        $db = $this->_em->getRepository(Book::class);

        return $db->createQueryBuilder('b')
            ->select('b.text')
            ->where('b.name = :name')
            ->andWhere('b.size = :size')
            ->setParameter(':name', $bookName)
            ->setParameter(':size', $size)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getText(string $bookName, int $offset, int $limit = 1): ?array
    {
        /** @var $db EntityRepository */
        $db = $this->_em->getRepository(Book::class);

        return $db->createQueryBuilder('b')
            ->select('b.text')
            ->where('b.name = :name')
            ->orderBy('b.id', 'ASC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->setParameter(':name', $bookName)
            ->getQuery()
            ->getResult();
    }
}