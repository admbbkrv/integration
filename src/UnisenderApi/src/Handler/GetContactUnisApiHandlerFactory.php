<?php

declare(strict_types=1);

namespace UnisenderApi\Handler;

use Psr\Container\ContainerInterface;
use UnisenderApi\Services\GetContactInterface;

class GetContactUnisApiHandlerFactory extends AbstractApiUnisHandlerFactory
{
    public function __invoke(ContainerInterface $container) : GetContactUnisApiHandler
    {
        return new GetContactUnisApiHandler(
            $this->getUnisenderApi(),
            $container->get(GetContactInterface::class)
        );
    }
}
