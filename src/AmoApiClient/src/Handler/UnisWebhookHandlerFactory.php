<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use DataBase\Services\ApiToken\get\GetApiTokenService;
use DataBase\Services\Contact\create\Interfaces\SaveContactInterface;
use Psr\Container\ContainerInterface;
use UnisenderApi\Services\PrepareData\Interfaces\GetPreparedContactsForDeleteInterface;
use UnisenderApi\Services\PrepareData\Interfaces\GetPreparedContactsForImportInterface;
use UnisenderApi\Services\PrepareData\Interfaces\GetPreparedContactsForUpdateInterface;
use UnisenderApi\Services\UnisenderApi\Interfaces\GetUnisenderApiInterface;

/**
 * Фабрика генерации UnisWebhookHandler обработчика
 */
class UnisWebhookHandlerFactory
{
    public function __invoke(ContainerInterface $container) : UnisWebhookHandler
    {
        return new UnisWebhookHandler(
            $container->get(GetPreparedContactsForImportInterface::class),
            $container->get(GetPreparedContactsForDeleteInterface::class),
            $container->get(GetPreparedContactsForUpdateInterface::class),
            $container->get(GetApiTokenService::class),
            $container->get(GetUnisenderApiInterface::class),
            $container->get(SaveContactInterface::class)
        );
    }
}
