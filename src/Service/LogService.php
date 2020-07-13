<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\ArchiveLog;
use App\Entity\Log;
use App\Repository\ArchiveLogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Iterator;

class LogService
{
    private const LIMIT_WRITE_LOGS = 4;

    private EntityManagerInterface $entityManager;

    private NotificationService $notification;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->notification = new NotificationService();
    }

    public function saveLogsToFile(array $range): string
    {
        $countLogs = $this->entityManager
            ->getRepository(Log::class)
            ->getCountRange($range['start'], $range['end']);

        if ($countLogs === 0) {
            throw new Exception('No logs for the indicated period');
        }

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
        $countRowsFile = $this->getCountRowsFile($file);
        /** @var ArchiveLogRepository $archiveLogRepository */
        $archiveLogRepository = $this->entityManager->getRepository(ArchiveLog::class);
        $archiveLogRepository->saveLog($file);
        $countRowsAlr = $archiveLogRepository->getCountRange($range['start'], $range['end']);

        if ($countRowsFile !== $countRowsAlr) {
            throw new Exception('Error of importing logs to the database');
        }
    }

    private function getCountRowsFile(string $file): int
    {
        return sizeof(file($file));
    }

    private function writeLogsToFile(string $pathFile, array $range, int $countLogs): void
    {
        try {
            $this->checkFile($pathFile);

            for ($offset = 0; $countLogs > $offset; $offset += static::LIMIT_WRITE_LOGS) {
                $logs = $this->getLogs($range, $offset, static::LIMIT_WRITE_LOGS);
                if (empty($logs)) break;
                $data = $this->generator($logs);
                file_put_contents($pathFile, iterator_to_array($data), FILE_APPEND);
                $this->notification->view(' ... ' . static::LIMIT_WRITE_LOGS . ' rows added');
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
}