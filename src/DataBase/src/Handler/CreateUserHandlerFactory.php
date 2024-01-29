<?php

declare(strict_types=1);

namespace DataBase\Handler;

use DataBase\Services\Interfaces\ConnectToDBInterface;
use DataBase\Services\Interfaces\CreateUserInterface;
use Psr\Container\ContainerInterface;

/**
 * Фабрика генерации CreateUserHandler обработчика
 */
class CreateUserHandlerFactory
{
    public function __invoke(ContainerInterface $container): CreateUserHandler
    {
        $configDB = $container->get('config')['database'];
        $connectToDBService = $container->get(ConnectToDBInterface::class);
        $connectToDBService->connect($configDB);

        return new CreateUserHandler(
            $container->get(CreateUserInterface::class)
        );
    }
}
