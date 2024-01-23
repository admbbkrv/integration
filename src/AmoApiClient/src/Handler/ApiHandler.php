<?php

namespace AmoApiClient\Handler;

use AmoApiClient\Services\AccessTokenService\GetTokenInterface;
use AmoCRM\Client\AmoCRMApiClient;
use Mezzio\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class ApiHandler implements RequestHandlerInterface
{
    /**
     * Объект API клиента
     * @var AmoCRMApiClient
     */
    protected AmoCRMApiClient $apiClient;
    protected GetTokenInterface $getTokenService;
    protected RouterInterface $router;

    public function __construct(
        AmoCRMApiClient $apiClient,
        GetTokenInterface $getTokenService,
        RouterInterface $router
    ) {
        $this->apiClient = $apiClient;
        $this->getTokenService = $getTokenService;
        $this->router = $router;
    }

    /**
     * @inheritDoc
     */
    abstract public function handle(ServerRequestInterface $request): ResponseInterface;
}