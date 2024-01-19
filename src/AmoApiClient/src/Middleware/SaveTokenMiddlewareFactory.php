<?php

declare(strict_types=1);

namespace AmoApiClient\Middleware;

use AmoApiClient\Services\AccessTokenService\SaveTokenInterface;
use AmoCRM\Client\AmoCRMApiClient;
use Mezzio\Router\RouterInterface;
use Psr\Container\ContainerInterface;

class SaveTokenMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : SaveTokenMiddleware
    {
        return new SaveTokenMiddleware(
            $container->get(AmoCRMApiClient::class),
            $container->get(SaveTokenInterface::class),
            $container->get(RouterInterface::class)
        );
    }
}
