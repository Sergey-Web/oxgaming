<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Log;
use DateTime;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Exception;

class ArchiveLogRepository extends EntityRepository
{
    public function saveLog(string $file)
    {
        $pathFile = str_replace('\\', '/', $file);

        $mapping = (new ResultSetMapping())
            ->addEntityResult(Log::class, 'l')
            ->addFieldResult('l', 'id', 'id')
            ->addFieldResult('l', 'created_at', 'createdAt')
            ->addFieldResult('l', 'text', 'text');

        try {
            $this->_em->createNativeQuery("
                LOAD DATA
                INFILE '{$pathFile}'
                INTO TABLE archive_logs
                FIELDS TERMINATED BY ',' 
            ",
                $mapping
            )->getResult();
        } catch (Exception $e) {}
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
}