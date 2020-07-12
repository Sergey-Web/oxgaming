<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\ArchiveLog;
use App\Entity\Log;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Iterator;

class LogService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function saveLogsToFile(array $range): string
    {
        $countLogs = $this->entityManager
            ->getRepository(Log::class)
            ->getCountRange($range['start'], $range['end']);

        $dateTime = new \DateTime();
        $fileName = $dateTime->format('Y_m_d').'.csv';
        $pathFile = __DIR__ . '/../../' . $_ENV['ARCHIVE_LOGS_PATH'] . $fileName;

        $this->writeLogsToFile($pathFile, $range, $countLogs);

        return $pathFile;
    }

    public function getLogs(array $range, int $offset, int $limit): array
    {
        return $this->entityManager
            ->getRepository(Log::class)
            ->getRange(
                $range['start'],
                $range['end'],
                $limit,
                $offset
            );
    }

    public function import(string $file, array $range)
    {
        $countRows = $this->getCountRowsFile($file);
        $this->entityManager->getRepository(ArchiveLog::class)
//            ...@toDO NEXT..
    }

    private function getCountRowsFile(string $file)
    {
        return sizeof (file ($file));
    }

    private function writeLogsToFile(string $pathFile, array $range, int $countLogs): void
    {
        $limit = 4;

        try {
            $this->checkFile($pathFile);

            for ($offset = 0; $countLogs > $offset; $offset += $limit) {
                $logs = $this->getLogs($range, $offset, $limit);
                if (empty($logs)) break;
                $data = $this->generator($logs);
                file_put_contents($pathFile, iterator_to_array($data), FILE_APPEND);
                $this->notification(' --- '.$limit .' lines added');
            }
        } catch (Exception $e) {}
    }

    private function checkFile(string $pathFile): bool
    {
        $res = false;

        if (file_exists($pathFile) !== false) {
            $res = unlink($pathFile);
        }

        return $res;
    }

    private function generator(array $data): Iterator
    {
        /** @var Log $item */
        foreach ($data as $item) {
            yield implode(
                ',',
                [$item->getId(), $item->getCreatedAt()->format('Y-m-d h:i:s'), $item->getText()]
            ) . "\n";
        }
    }

    private function notification(string $text): void
    {
        echo "\033[32m $text \n";
    }
}