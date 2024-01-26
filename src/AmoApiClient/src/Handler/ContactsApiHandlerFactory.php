<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use AmoApiClient\Services\AccessTokenService\GetTokenInterface;
use AmoApiClient\Services\ContactServices\Interfaces\GetNamesWithEmailsInterface;
use Mezzio\Router\RouterInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Фабрика генерации ContactsApiHandler обработчика
 */
class ContactsApiHandlerFactory extends AbstractApiHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        return new ContactsApiHandler(
            $this->getApiClient($container),
            $container->get(GetTokenInterface::class),
            $container->get(GetNamesWithEmailsInterface::class),
            $container->get(RouterInterface::class)
        );
    }
}
