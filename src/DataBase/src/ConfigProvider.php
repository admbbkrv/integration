<?php

declare(strict_types=1);

namespace DataBase;

use DataBase\Handler\CreateIntegrationHandler;
use DataBase\Handler\CreateIntegrationHandlerFactory;
use DataBase\Handler\CreateUserHandler;
use DataBase\Handler\CreateUserHandlerFactory;
use DataBase\Handler\SaveApiKeyHandler;
use DataBase\Handler\SaveApiKeyHandlerFactory;
use DataBase\Handler\SaveIntegrationHandler;
use DataBase\Handler\SaveIntegrationHandlerFactory;
use DataBase\Middleware\DoConnectToDBMiddleware;
use DataBase\Middleware\DoConnectToDBMiddlewareFactory;
use DataBase\Services\ApiToken\create\Interfaces\SaveAccessTokenInterface;
use DataBase\Services\ApiToken\create\Interfaces\SaveApiKeyInterface;
use DataBase\Services\ApiToken\create\Interfaces\SaveApiTokenInterface;
use DataBase\Services\ApiToken\create\SaveAccessTokenService;
use DataBase\Services\ApiToken\create\SaveApiKeyService;
use DataBase\Services\ApiToken\create\SaveApiTokenService;
use DataBase\Services\ApiToken\get\GetAccessTokenService;
use DataBase\Services\ApiToken\get\GetApiTokenService;
use DataBase\Services\ApiToken\get\Interfaces\GetAccessTokenInterface;
use DataBase\Services\ApiToken\get\Interfaces\GetApiTokenInterface;
use DataBase\Services\ConnectToMysqlDBService;
use DataBase\Services\Integration\Create\Interfaces\SaveIntegrationInterface;
use DataBase\Services\Integration\Create\SaveIntegrationService;
use DataBase\Services\Integration\Get\GetIntegrationService;
use DataBase\Services\Integration\Get\Interfaces\GetIntegrationInterface;
use DataBase\Services\Interfaces\ConnectToDBInterface;
use DataBase\Services\User\create\Interfaces\SaveUserInterface;
use DataBase\Services\User\create\SaveUserService;
use DataBase\Services\User\get\GetUserService;
use DataBase\Services\User\get\Interfaces\GetUserInterface;

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
            'invokables' => array(
                ConnectToDBInterface::class => ConnectToMysqlDBService::class,
                //User
                SaveUserInterface::class => SaveUserService::class,
                GetUserInterface::class => GetUserService::class,
                //Integration
                SaveIntegrationInterface::class => SaveIntegrationService::class,
                GetIntegrationInterface::class => GetIntegrationService::class,
                //ApiToken
                SaveApiTokenInterface::class => SaveApiTokenService::class,
                SaveAccessTokenInterface::class => SaveAccessTokenService::class,
                SaveApiKeyInterface::class => SaveApiKeyService::class,
                GetApiTokenInterface::class => GetApiTokenService::class,
                GetAccessTokenInterface::class => GetAccessTokenService::class,
            ),
            'factories'  => [
                DoConnectToDBMiddleware::class => DoConnectToDBMiddlewareFactory::class,
                //User
                CreateUserHandler::class => CreateUserHandlerFactory::class,
                //Integration
                CreateIntegrationHandler::class => CreateIntegrationHandlerFactory::class,
                SaveIntegrationHandler::class => SaveIntegrationHandlerFactory::class,
                //ApiToken
                SaveApiKeyHandler::class => SaveApiKeyHandlerFactory::class,
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
