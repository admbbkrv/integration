<?php

declare(strict_types=1);

namespace DataBase\Middleware;

use DataBase\Services\Interfaces\ConnectToDBInterface;
use Psr\Container\ContainerInterface;

/**
 * Фабрика генерации DoConnectToDBMiddleware middleware
 */
class DoConnectToDBMiddlewareFactory
{
    public function __invoke(
        ContainerInterface $container
    ): DoConnectToDBMiddleware {

        return new DoConnectToDBMiddleware(
            $container->get(ConnectToDBInterface::class),
            $container->get('config')['database']
        );
    }
}
