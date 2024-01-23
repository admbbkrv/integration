<?php

declare(strict_types=1);

namespace AmoApiClient\Middleware;

use AmoApiClient\Constants\AmoApiConstants;
use AmoApiClient\Services\AccessTokenService\GetTokenInterface;
use AmoCRM\Client\AmoCRMApiClient;
use Mezzio\Router\RouterInterface;
use Psr\Container\ContainerInterface;

class ApiAmoAuthMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : ApiAmoAuthMiddleware
    {
        $getTokenService = $container->get(GetTokenInterface::class);
        $accessToken = $getTokenService->get(AmoApiConstants::TOKEN_FILE);

        return new ApiAmoAuthMiddleware(
            $accessToken,
            $container->get(RouterInterface::class)
        );
    }
}
