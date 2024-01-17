<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class GetApiTokenHandlerFactory
{
    public function __invoke(ContainerInterface $container) : GetApiTokenHandler
    {
        return new GetApiTokenHandler($container->get(TemplateRendererInterface::class));
    }
}
