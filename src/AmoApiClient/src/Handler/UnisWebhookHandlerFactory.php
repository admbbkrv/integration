<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use DataBase\Services\ApiToken\get\GetApiTokenService;
use DataBase\Services\Contact\create\Interfaces\SaveContactInterface;
use DataBase\Services\User\get\Interfaces\GetUserInterface;
use Psr\Container\ContainerInterface;
use UnisenderApi\Services\PrepareData\GetPreparedContactsForAddService;
use UnisenderApi\Services\PrepareData\GetPreparedContactsForDeleteService;
use UnisenderApi\Services\PrepareData\GetPreparedContactsForUpdateService;
use UnisenderApi\Services\UnisenderApi\Interfaces\GetUnisenderApiInterface;

/**
 * Фабрика генерации UnisWebhookHandler обработчика
 */
class UnisWebhookHandlerFactory
{
    public function __invoke(ContainerInterface $container) : UnisWebhookHandler
    {
        return new UnisWebhookHandler(
            $container->get(GetPreparedContactsForAddService::class),
            $container->get(GetPreparedContactsForDeleteService::class),
            $container->get(GetPreparedContactsForUpdateService::class),
            $container->get(GetApiTokenService::class),
            $container->get(GetUnisenderApiInterface::class),
            $container->get(SaveContactInterface::class),
            $container->get(GetUserInterface::class)
        );
    }
}
