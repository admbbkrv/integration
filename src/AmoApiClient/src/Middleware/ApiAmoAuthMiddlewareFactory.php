<?php

declare(strict_types=1);

namespace AmoApiClient\Middleware;

use AmoApiClient\Constants\AmoApiConstants;
use AmoApiClient\Services\AccessTokenService\GetTokenInterface;
use Mezzio\Router\RouterInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Фабрика генерации middleware ApiAmoAuthMiddleware
 */
class ApiAmoAuthMiddlewareFactory
{
    /**
     * @param ContainerInterface $container
     * @return ApiAmoAuthMiddleware
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
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
