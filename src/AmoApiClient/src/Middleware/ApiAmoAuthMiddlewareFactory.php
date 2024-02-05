<?php

declare(strict_types=1);

namespace AmoApiClient\Middleware;

use DataBase\Services\ApiToken\get\Interfaces\GetAccessTokenInterface;
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
        return new ApiAmoAuthMiddleware(
            $container->get(GetAccessTokenInterface::class),
        );
    }
}
