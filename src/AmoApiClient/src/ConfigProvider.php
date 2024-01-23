<?php

declare(strict_types=1);

namespace AmoApiClient;


use AmoApiClient\Handler\AuthAmoHandler;
use AmoApiClient\Handler\AuthAmoHandlerFactory;
use AmoApiClient\Handler\MainApiHandler;
use AmoApiClient\Handler\MainApiHandlerFactory;
use AmoApiClient\Handler\RedirectUriApiHandler;
use AmoApiClient\Handler\RedirectUriApiHandlerFactory;
use AmoApiClient\Middleware\ApiAmoAuthMiddleware;
use AmoApiClient\Middleware\ApiAmoAuthMiddlewareFactory;
use AmoApiClient\Middleware\DotEnvMiddleware;
use AmoApiClient\Middleware\DotEnvMiddlewareFactory;
use AmoApiClient\Services\AccessTokenService\AccessTokenService;
use AmoApiClient\Services\AccessTokenService\AccessTokenServiceFactory;
use AmoApiClient\Services\AccessTokenService\GetTokenInterface;
use AmoApiClient\Services\AccessTokenService\GetTokenService;
use AmoApiClient\Services\AccessTokenService\SaveTokenInterface;
use AmoApiClient\Services\AccessTokenService\SaveTokenService;
use AmoApiClient\Services\OAuth\OAuthConfig;
use AmoApiClient\Services\OAuth\OAuthService;
use AmoCRM\OAuth\OAuthConfigInterface;
use AmoCRM\OAuth\OAuthServiceInterface;
use Symfony\Component\Dotenv\Dotenv;

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
                OAuthServiceInterface::class => OAuthService::class,
                OAuthConfigInterface::class => OAuthConfig::class,
                Dotenv::class => Dotenv::class,
            ],
            'factories'  => [
                MainApiHandler::class => MainApiHandlerFactory::class,
                AccessTokenService::class => AccessTokenServiceFactory::class,
                ApiAmoAuthMiddleware::class => ApiAmoAuthMiddlewareFactory::class,
                AuthAmoHandler::class => AuthAmoHandlerFactory::class,
                RedirectUriApiHandler::class => RedirectUriApiHandlerFactory::class,
                DotEnvMiddleware::class => DotEnvMiddlewareFactory::class,
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