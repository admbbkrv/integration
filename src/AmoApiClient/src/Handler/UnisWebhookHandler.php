<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use Beanstalkd\Producers\WebhookProducer;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Обработчик хуков с контактами
 */
class UnisWebhookHandler implements RequestHandlerInterface
{

    private WebhookProducer $producer;

    public function __construct(
        WebhookProducer $producer
    ) {
        $this->producer = $producer;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $postParams = $request->getParsedBody();
        $this->producer->produce($postParams);

        return new JsonResponse(
            'Contact synchronization is successful',
            200
        );
    }
}
