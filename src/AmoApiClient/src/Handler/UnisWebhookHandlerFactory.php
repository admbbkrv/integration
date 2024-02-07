<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use Beanstalkd\Producers\WebhookProducer;
use Psr\Container\ContainerInterface;

/**
 * Фабрика генерации UnisWebhookHandler обработчика
 */
class UnisWebhookHandlerFactory
{
    public function __invoke(ContainerInterface $container) : UnisWebhookHandler
    {
        return new UnisWebhookHandler(
            $container->get(WebhookProducer::class),
        );
    }
}
