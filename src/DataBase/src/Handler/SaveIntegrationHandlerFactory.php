<?php

declare(strict_types=1);

namespace DataBase\Handler;

use DataBase\Services\Integration\Create\Interfaces\SaveIntegrationInterface;
use Psr\Container\ContainerInterface;

/**
 * Фабрика генерация SaveIntegrationHandler обработчика
 */
class SaveIntegrationHandlerFactory
{
    public function __invoke(
        ContainerInterface $container
    ): SaveIntegrationHandler {

        return new SaveIntegrationHandler(
            $container->get(SaveIntegrationInterface::class)
        );
    }
}
