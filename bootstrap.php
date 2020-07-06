<?php

declare(strict_types=1);

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Doctrine\ORM\Tools\Setup;

$settings =  require_once __DIR__ . '/config/database.php';

$config = Setup::createAnnotationMetadataConfiguration(
    $settings['doctrine']['metadata_dirs'],
    $settings['doctrine']['dev_mode'],
    $settings['doctrine']['proxy_dir'],
    $settings['doctrine']['cache_dir'] ? new FilesystemCache($settings['doctrine']['cache_dir']) : new ArrayCache(),
    false
);

$config->setNamingStrategy(new UnderscoreNamingStrategy());

$eventManager = new EventManager();

$entityManager = EntityManager::create(
    $settings['doctrine']['connection'],
    $config,
    $eventManager
);