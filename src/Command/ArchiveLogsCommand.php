<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Log;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ArchiveLogsCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(string $name = null, EntityManagerInterface $entityManager)
    {
        parent::__construct($name);

        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setName('archive:logs')
            ->setDescription('Save logs per month to the archive table');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->entityManager->getConnection()->beginTransaction();
        $dateTime = new \DateTime();
        $fileName = $dateTime->format('Y_m_d').'.csv';
        $mapping = (new ResultSetMapping())
            ->addEntityResult(Log::class, 'l')
            ->addFieldResult('l', 'id', 'id')
            ->addFieldResult('l', 'created_at', 'createdAt')
            ->addFieldResult('l', 'text', 'text');
        $this->entityManager->getConnection()->createNativeQuery("
                SELECT id, created_at, text
                FROM logs WHERE date_format(created_at, '%Y%m') = date_format(date_add(now(), interval -1 month), '%Y%m')
                INTO OUTFILE '". $_ENV['ARCHIVE_LOGS_PATH'] . $fileName ."'
            ",
                $mapping
            )
        ->getResult();

        $output->writeln('<info>File '. $fileName .' for exporting log archive created!</info>');

        return 0;
    }
}