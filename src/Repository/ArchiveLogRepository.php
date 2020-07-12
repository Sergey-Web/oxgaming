<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Log;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Exception;

class ArchiveLogRepository extends EntityRepository
{
    public function saveLog(string $file)
    {
        $mapping = (new ResultSetMapping())
            ->addEntityResult(Log::class, 'l')
            ->addFieldResult('l', 'id', 'id')
            ->addFieldResult('l', 'created_at', 'createdAt')
            ->addFieldResult('l', 'text', 'text');

        try {
            $this->_em->createNativeQuery("
                LOAD DATA
                INFILE '{$file}'
                INTO TABLE archive_logs
                FIELDS TERMINATED BY ',' 
            ",
                $mapping
            )->getResult();
        } catch (Exception $e) {}
    }
}