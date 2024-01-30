<?php

declare(strict_types=1);

use DataBase\Services\Interfaces\ConnectToDBInterface;
use \Phpmig\Adapter;
use Psr\Container\ContainerInterface;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->usePutenv()->loadEnv(__DIR__ . '/.env');

/** @var ContainerInterface $diContainer */
$diContainer = require 'config/container.php';

$config = $diContainer->get('config')['database'];

/** @var ConnectToDBInterface $connectToDbService */
$connectToDbService = $diContainer->get(ConnectToDBInterface::class);
$connectToDbService->connect($config);

$container = new ArrayObject();

// replace this with a better Phpmig\Adapter\AdapterInterface
$container['phpmig.adapter'] = new Adapter\File\Flat(__DIR__ . DIRECTORY_SEPARATOR . 'migrations/.migrations.log');

$container['phpmig.migrations_path'] = __DIR__ . DIRECTORY_SEPARATOR . 'migrations';

return $container;
