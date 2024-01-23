<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use AmoApiClient\Services\AccessTokenService\GetTokenInterface;
use Mezzio\Router\RouterInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Фабрика генерации MainApiHandler обработчика
 */
class MainApiHandlerFactory extends AbstractApiHandlerFactory
{
    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        return new MainApiHandler(
            $this->getApiClient($container),
            $container->get(GetTokenInterface::class),
            $container->get(RouterInterface::class)
        );
    }
}
