<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use AmoApiClient\Services\AccessTokenService\SaveTokenInterface;
use AmoCRM\Client\AmoCRMApiClientFactory;
use AmoCRM\OAuth\OAuthConfigInterface;
use AmoCRM\OAuth\OAuthServiceInterface;
use Mezzio\Router\RouterInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Класс фабрики для генерации RedirectUriApiHandler обработчика
 */
class RedirectUriApiHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return RedirectUriApiHandler
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : RedirectUriApiHandler
    {
        $apiClientFactory = new AmoCRMApiClientFactory(
            $container->get(OAuthConfigInterface::class),
            $container->get(OAuthServiceInterface::class)
        );

        $apiClient = $apiClientFactory->make();

        return new RedirectUriApiHandler(
            $apiClient,
            $container->get(SaveTokenInterface::class),
            $container->get(RouterInterface::class)
        );
    }
}
