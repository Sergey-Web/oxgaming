<?php

declare(strict_types=1);

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Doctrine\ORM\Tools\Setup;
use Predis\Client;

$settingsMySql =  require_once __DIR__ . '/config/database.php';

$config = Setup::createAnnotationMetadataConfiguration(
    $settingsMySql['doctrine']['metadata_dirs'],
    $settingsMySql['doctrine']['dev_mode'],
    $settingsMySql['doctrine']['proxy_dir'],
    $settingsMySql['doctrine']['cache_dir'] ? new FilesystemCache($settingsMySql['doctrine']['cache_dir']) : new ArrayCache(),
    false
);

$config->setNamingStrategy(new UnderscoreNamingStrategy());

$eventManager = new EventManager();

$entityManager = EntityManager::create(
    $settingsMySql['doctrine']['connection'],
    $config,
    $eventManager
);

Predis\Autoloader::register();

$clientRedis = new Client();

