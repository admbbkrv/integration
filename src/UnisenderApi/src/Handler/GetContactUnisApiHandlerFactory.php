<?php

declare(strict_types=1);

namespace UnisenderApi\Handler;

use Psr\Container\ContainerInterface;
use UnisenderApi\Services\GetContactInterface;

/**
 * Фабрика для генерации GetContactUnisApiHandler обработчика
 */
class GetContactUnisApiHandlerFactory
{
    public function __invoke(ContainerInterface $container) : GetContactUnisApiHandler
    {
        return new GetContactUnisApiHandler(
            $container->get(GetContactInterface::class)
        );
    }
}
