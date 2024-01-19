<?php

declare(strict_types=1);

namespace AmoApiClient\Middleware;

use AmoApiClient\Services\AccessTokenService\GetTokenInterface;
use AmoCRM\Client\AmoCRMApiClient;
use Psr\Container\ContainerInterface;

class ApiAmoAuthMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : ApiAmoAuthMiddleware
    {
        return new ApiAmoAuthMiddleware(
            $container->get(AmoCRMApiClient::class),
            $container->get(GetTokenInterface::class)
        );
    }
}
