<?php

declare(strict_types=1);

namespace AmoApiClient\Middleware;

use Psr\Container\ContainerInterface;
use Symfony\Component\Dotenv\Dotenv;

class DotEnvMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : DotEnvMiddleware
    {
        return new DotEnvMiddleware($container->get(Dotenv::class));
    }
}
