<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use Psr\Container\ContainerInterface;

/**
 * Фабрика генерации AuthApiTemlateHandler обработчика
 */
class AuthApiTemlateHandlerFactory
{
    public function __invoke(ContainerInterface $container) : AuthApiTemlateHandler
    {
        return new AuthApiTemlateHandler();
    }
}
