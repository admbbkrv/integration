<?php

declare(strict_types=1);

namespace DataBase\Handler;

use Psr\Container\ContainerInterface;

/**
 * Фабрика для генерации CreateIntegrationHandler
 */
class CreateIntegrationHandlerFactory
{
    public function __invoke(
        ContainerInterface $container
    ): CreateIntegrationHandler {

        return new CreateIntegrationHandler();
    }
}
