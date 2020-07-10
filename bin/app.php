<?php

declare(strict_types=1);

use App\Command\ArchiveLogsCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/../.env');

require_once __DIR__ . '/../bootstrap.php';

$cli = new Application('Console');

$cli->add(new ArchiveLogsCommand(null, $entityManager));

$cli->run();