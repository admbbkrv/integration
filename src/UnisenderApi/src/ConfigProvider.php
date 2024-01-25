<?php

declare(strict_types=1);

namespace UnisenderApi;

use UnisenderApi\Handler\GetContactUnisApiHandler;
use UnisenderApi\Handler\GetContactUnisApiHandlerFactory;
use UnisenderApi\Services\GetContactInterface;
use UnisenderApi\Services\GetContactService;

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
            ],
            'factories'  => [
                GetContactUnisApiHandler::class => GetContactUnisApiHandlerFactory::class,
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
