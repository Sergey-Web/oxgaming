<?php

declare(strict_types=1);

namespace App\Repository;

use DateTime;
use Doctrine\ORM\EntityRepository;

class LogRepository extends EntityRepository
{
    public function getRange(
        DateTime $dateStart,
        DateTime $dateEnd,
        int $limit,
        int $offset = 0
    ): array
    {
        $qb = $this->_em->createQueryBuilder();

        return $qb
            ->select('l')
            ->from($this->getEntityName(), 'l')
            ->andWhere(
                $qb->expr()->between(
                    'l.createdAt',
                    "'".$dateStart->format('Y-m-d')."'",
                    "'".$dateEnd->format('Y-m-d')."'"
                )
            )
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    public function getCountRange(DateTime $dateStart, DateTime $dateEnd): int
    {
        $qb = $this->_em->createQueryBuilder();

        return (int) $qb
            ->select($qb->expr()->count('l'))
            ->from($this->getEntityName(), 'l')
            ->andWhere(
                    $qb->expr()->between(
                    'l.createdAt',
                    "'".$dateStart->format('Y-m-d')."'",
                    "'".$dateEnd->format('Y-m-d')."'"
                )
            )
            ->getQuery()
            ->getSingleResult()[1];
    }

    public function saveLog()
    {

    }
}