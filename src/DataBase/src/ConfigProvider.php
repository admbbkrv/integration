<?php

declare(strict_types=1);

namespace DataBase;

use DataBase\Handler\CreateIntegrationHandler;
use DataBase\Handler\CreateIntegrationHandlerFactory;
use DataBase\Handler\CreateUserHandler;
use DataBase\Handler\CreateUserHandlerFactory;
use DataBase\Handler\SaveIntegrationHandler;
use DataBase\Handler\SaveIntegrationHandlerFactory;
use DataBase\Middleware\DoConnectToDBMiddleware;
use DataBase\Middleware\DoConnectToDBMiddlewareFactory;
use DataBase\Services\ConnectToMysqlDBService;
use DataBase\Services\CreateUserService;
use DataBase\Services\Integration\Create\Interfaces\SaveIntegrationInterface;
use DataBase\Services\Integration\Create\SaveIntegrationService;
use DataBase\Services\Integration\Get\GetIntegrationService;
use DataBase\Services\Integration\Get\Interfaces\GetIntegrationInterface;
use DataBase\Services\Interfaces\ConnectToDBInterface;
use DataBase\Services\Interfaces\User\CreateUserInterface;

/**
 * The configuration provider for the DataBase module
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
                ConnectToDBInterface::class => ConnectToMysqlDBService::class,
                CreateUserInterface::class => CreateUserService::class,
                //Integration
                SaveIntegrationInterface::class => SaveIntegrationService::class,
                GetIntegrationInterface::class => GetIntegrationService::class,
            ],
            'factories'  => [
                CreateUserHandler::class => CreateUserHandlerFactory::class,
                DoConnectToDBMiddleware::class => DoConnectToDBMiddlewareFactory::class,
                //Integration
                CreateIntegrationHandler::class => CreateIntegrationHandlerFactory::class,
                SaveIntegrationHandler::class => SaveIntegrationHandlerFactory::class,
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
                'data-base'    => [__DIR__ . '/../templates/'],
            ],
        ];
    }
}
