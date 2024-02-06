<?php

declare(strict_types=1);

namespace UnisenderApi;

use UnisenderApi\Handler\GetContactUnisApiHandler;
use UnisenderApi\Handler\GetContactUnisApiHandlerFactory;
use UnisenderApi\Handler\ImportContactsUnisHandler;
use UnisenderApi\Handler\ImportContactsUnisHandlerFactory;
use UnisenderApi\Services\GetContactInterface;
use UnisenderApi\Services\GetContactService;
use UnisenderApi\Services\ImportContactsInterface;
use UnisenderApi\Services\ImportContactsService;
use UnisenderApi\Services\PrepareData\GetPreparedContactsForDeleteService;
use UnisenderApi\Services\PrepareData\GetPreparedContactsForAddService;
use UnisenderApi\Services\PrepareData\GetPreparedContactsForUpdateService;
use UnisenderApi\Services\PrepareForImportInterface;
use UnisenderApi\Services\PrepareForImportService;
use UnisenderApi\Services\UnisenderApi\GetUnisenderApiService;
use UnisenderApi\Services\UnisenderApi\Interfaces\GetUnisenderApiInterface;

/**
 * The configuration provider for the UnisenderApi module
 *
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies() : array
    {
        return [
            'invokables' => [
                GetContactInterface::class => GetContactService::class,
                PrepareForImportInterface::class => PrepareForImportService::class,
                ImportContactsInterface::class => ImportContactsService::class,
                GetPreparedContactsForAddService::class => GetPreparedContactsForAddService::class,
                GetUnisenderApiInterface::class => GetUnisenderApiService::class,
                GetPreparedContactsForDeleteService::class => GetPreparedContactsForDeleteService::class,
                GetPreparedContactsForUpdateService::class => GetPreparedContactsForUpdateService::class,
            ],
            'factories'  => [
                GetContactUnisApiHandler::class => GetContactUnisApiHandlerFactory::class,
                ImportContactsUnisHandler::class => ImportContactsUnisHandlerFactory::class,
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates() : array
    {
        return [
            'paths' => [
                'unisender-api'    => [__DIR__ . '/../templates/'],
            ],
        ];
    }
}
