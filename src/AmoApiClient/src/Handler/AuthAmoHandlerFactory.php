<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use AmoCRM\Client\AmoCRMApiClientFactory;
use AmoCRM\OAuth\OAuthConfigInterface;
use AmoCRM\OAuth\OAuthServiceInterface;
use Psr\Container\ContainerInterface;

/**
 * Фабрика для генерации AuthAmoHandler обработчика
 */
class AuthAmoHandlerFactory
{
    public function __invoke(ContainerInterface $container): AuthAmoHandler
    {
        $apiClientFactory = new AmoCRMApiClientFactory(
            $container->get(OAuthConfigInterface::class),
            $container->get(OAuthServiceInterface::class)
        );

        $apiClient = $apiClientFactory->make();

        return new AuthAmoHandler($apiClient);
    }
}
