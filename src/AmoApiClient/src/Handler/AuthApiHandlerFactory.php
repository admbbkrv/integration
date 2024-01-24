<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AuthApiHandlerFactory extends AbstractApiHandlerFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AuthApiHandler
    {
        return new AuthApiHandler($this->getApiClient($container));
    }
}
