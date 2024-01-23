<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiClientFactory;
use AmoCRM\OAuth\OAuthConfigInterface;
use AmoCRM\OAuth\OAuthServiceInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Абстрактный родительский класс фабрик
 * для обработчков, которые работают с API AMO
 */
abstract class AbstractApiHandlerFactory
{
    abstract public function __invoke(ContainerInterface $container): RequestHandlerInterface;

    /**
     * Метод для получения объекта AmoCRMApiClient
     * @param ContainerInterface $container
     * @return AmoCRMApiClient
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getApiClient(ContainerInterface $container): AmoCRMApiClient
    {
        $apiClientFactory = new AmoCRMApiClientFactory(
            $container->get(OAuthConfigInterface::class),
            $container->get(OAuthServiceInterface::class)
        );

        return $apiClientFactory->make();
    }
}
