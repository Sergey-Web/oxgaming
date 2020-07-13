<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\LogService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class ArchiveLogsCommand extends Command
{
    private EntityManagerInterface $entityManager;

    private LogService $logService;

    public function __construct(string $name = null, EntityManagerInterface $entityManager)
    {
        parent::__construct($name);

        $this->entityManager = $entityManager;
        $this->logService = new LogService($entityManager);
    }

    protected function configure(): void
    {
        $this
            ->setName('archive:logs:previous_month')
            ->setDescription('Save logs previous month to the archive table');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->entityManager->getConnection()->beginTransaction();
        $range = [
            'start' => new \DateTime('first day of -1 month midnight'),
            'end' => new \DateTime('last day of -1 month midnight'),
        ];

        try {
            $output->writeln('<info>- Creating file -</info>');
            $file = $this->logService->saveLogsToFile($range);
            $output->writeln('<info> [OK] - Data saved to file: '. $file .'</info>');
            $output->writeln('<info>- Saving file to DB -</info>');
            $this->logService->import($file, $range);
            $output->writeln('<info> [OK] - Data saved to table: '. $file .'</info>');
        } catch (Throwable $e) {
            $output->writeln('<error>'.$e->getMessage().'</error>');
        }

        return 0;
    }
}