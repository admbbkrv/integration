<?php

declare(strict_types=1);

namespace AmoApiClient\Services\AccessTokenService;

use Psr\Container\ContainerInterface;

class AccessTokenServiceFactory
{
    public function __invoke(ContainerInterface $container): AccessTokenService
    {
        return new AccessTokenService(
            $container->get(SaveTokenService::class),
            $container->get(GetTokenService::class)
        );
    }
}
