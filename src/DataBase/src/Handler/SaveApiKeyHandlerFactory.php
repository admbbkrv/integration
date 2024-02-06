<?php

declare(strict_types=1);

namespace DataBase\Handler;

use AmoApiClient\Services\AmoClient\Interfaces\GetAmoCRMApiClientInterface;
use AmoApiClient\Services\AmoClient\Webhooks\Interfaces\GenerateWebhookModelInterface;
use AmoApiClient\Services\ContactServices\Interfaces\FilterWithEmailInterface;
use AmoApiClient\Services\ContactServices\Interfaces\GetAllContactsInterface;
use DataBase\Services\ApiToken\create\Interfaces\SaveApiKeyInterface;
use DataBase\Services\ApiToken\get\Interfaces\GetAccessTokenInterface;
use DataBase\Services\Contact\create\SaveContactService;
use DataBase\Services\User\get\Interfaces\GetUserInterface;
use Mezzio\Router\RouterInterface;
use Psr\Container\ContainerInterface;
use UnisenderApi\Services\ImportContactsInterface;
use UnisenderApi\Services\PrepareForImportInterface;
use UnisenderApi\Services\UnisenderApi\Interfaces\GetUnisenderApiInterface;

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
            $container->get(GetAmoCRMApiClientInterface::class),
            $container->get(RouterInterface::class),
            $container->get(GenerateWebhookModelInterface::class),
            $container->get(GetAccessTokenInterface::class),
            $container->get(GetUnisenderApiInterface::class),
            $container->get(GetAllContactsInterface::class),
            $container->get(FilterWithEmailInterface::class),
            $container->get(PrepareForImportInterface::class),
            $container->get(ImportContactsInterface::class),
            $container->get(SaveContactService::class)
        );
    }
}
