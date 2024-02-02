<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use AmoApiClient\Services\AmoClient\Interfaces\GetAmoCRMApiClientInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Фабрика генерации AuthApiHandler обработчика
 */
class AuthApiHandlerFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AuthApiHandler
    {
        return new AuthApiHandler(
            $container->get(GetAmoCRMApiClientInterface::class)
        );
    }
}
