<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use AmoApiClient\Services\AccessTokenService\SaveTokenInterface;
use Mezzio\Router\RouterInterface;
use Psr\Container\ContainerInterface;

/**
 * Фабрика генерации RedirectUriApiHandler обработчика
 */
class RedirectUriApiHandlerFactory extends AbstractApiHandlerFactory
{
    public function __invoke(ContainerInterface $container) : RedirectUriApiHandler
    {
        return new RedirectUriApiHandler(
            $this->getApiClient($container),
            $container->get(SaveTokenInterface::class),
            $container->get(RouterInterface::class)
        );
    }
}
