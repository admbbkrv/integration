<?php

namespace AmoApiClient\Handler;

use AmoApiClient\Services\AccessTokenService\GetTokenInterface;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiClientFactory;
use AmoCRM\OAuth\OAuthConfigInterface;
use AmoCRM\OAuth\OAuthServiceInterface;
use Mezzio\Router\RouterInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Dotenv\Dotenv;

/**
 * Абстрактный родительский класс фабрик
 * для обработчков, которые работают с API AMO
 */
abstract class ApiHandlerFactory
{
    public function __invoke(ContainerInterface $container): ApiHandler
    {
        $apiClientFactory = new AmoCRMApiClientFactory(
            $container->get(OAuthConfigInterface::class),
            $container->get(OAuthServiceInterface::class)
        );

        $apiClient = $apiClientFactory->make();

        return $this->getApiHandler(
            $apiClient,
            $container->get(GetTokenInterface::class),
            $container->get(RouterInterface::class)
        );
    }

    /**
     * Метод для получения экземпляра класс обработчика
     * @param AmoCRMApiClient $apiClient
     * @param GetTokenInterface $getTokenInterface
     * @param RouterInterface $router
     * @return ApiHandler
     */
    abstract public function getApiHandler(
        AmoCRMApiClient $apiClient,
        GetTokenInterface $getTokenInterface,
        RouterInterface $router
    ): ApiHandler;
}