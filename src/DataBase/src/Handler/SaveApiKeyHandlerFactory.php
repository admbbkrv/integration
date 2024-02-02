<?php

declare(strict_types=1);

namespace DataBase\Handler;

use DataBase\Services\ApiToken\create\Interfaces\SaveApiKeyInterface;
use DataBase\Services\User\get\Interfaces\GetUserInterface;
use Psr\Container\ContainerInterface;

/**
 * Фабрика генерации SaveApiKeyHandler обработчика
 */
class SaveApiKeyHandlerFactory
{
    public function __invoke(ContainerInterface $container): SaveApiKeyHandler
    {
        return new SaveApiKeyHandler(
            $container->get(GetUserInterface::class),
            $container->get(SaveApiKeyInterface::class),
        );
    }
}
