<?php

declare(strict_types=1);

namespace AmoApiClient;


use AmoApiClient\Factory\AmoApiClientWithTokenFactory;
use AmoApiClient\Factory\AmoCRMApiClientFactory;
use AmoApiClient\Handler\ApiMainHandler;
use AmoApiClient\Handler\ApiMainHandlerFactory;
use AmoApiClient\Middleware\ApiAmoAuthMiddleware;
use AmoApiClient\Middleware\ApiAmoAuthMiddlewareFactory;
use AmoApiClient\Services\AccessTokenService\AccessTokenService;
use AmoApiClient\Services\AccessTokenService\AccessTokenServiceFactory;
use AmoApiClient\Services\AccessTokenService\GetTokenInterface;
use AmoApiClient\Services\AccessTokenService\GetTokenService;
use AmoApiClient\Services\AccessTokenService\SaveTokenInterface;
use AmoApiClient\Services\AccessTokenService\SaveTokenService;
use AmoCRM\Client\AmoCRMApiClient;

/**
 * The configuration provider for the AmoApiClient module
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
                SaveTokenInterface::class => SaveTokenService::class,
                GetTokenInterface::class => GetTokenService::class,
            ],
            'factories'  => [
                AmoCRMApiClient::class => AmoCRMApiClientFactory::class,
                'AmoClientWithToken' => AmoApiClientWithTokenFactory::class,
                ApiMainHandler::class => ApiMainHandlerFactory::class,
                AccessTokenService::class => AccessTokenServiceFactory::class,
                ApiAmoAuthMiddleware::class => ApiAmoAuthMiddlewareFactory::class,
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
                'amo-api-client'    => [__DIR__ . '/../templates/'],
            ],
        ];
    }
}
