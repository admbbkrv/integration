<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use AmoApiClient\Services\AmoClient\Interfaces\GetAmoCRMApiClientInterface;
use DataBase\Services\ApiToken\create\Interfaces\SaveApiTokenInterface;
use DataBase\Services\Integration\Get\Interfaces\GetIntegrationInterface;
use DataBase\Services\User\create\Interfaces\SaveUserInterface;
use Psr\Container\ContainerInterface;

/**
 * Фабрика генерации AmoRedirectUriApiHandler обработчика
 */
class AmoRedirectUriApiHandlerFactory
{
    public function __invoke(ContainerInterface $container) : AmoRedirectUriApiHandler
    {
        return new AmoRedirectUriApiHandler(
           $container->get(GetIntegrationInterface::class),
           $container->get(GetAmoCRMApiClientInterface::class),
           $container->get(SaveUserInterface::class),
           $container->get(SaveApiTokenInterface::class)
        );
    }
}
