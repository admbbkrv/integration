<?php

namespace AmoApiClient\Handler;

use AmoApiClient\Services\AccessTokenService\GetTokenInterface;
use AmoCRM\Client\AmoCRMApiClient;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class ApiHandler implements RequestHandlerInterface
{
    protected AmoCRMApiClient $apiClient;
    protected GetTokenInterface $getToken;

    public function __construct(
        AmoCRMApiClient $apiClient,
        GetTokenInterface $getToken
    ) {
        $this->apiClient = $apiClient;
        $this->getToken = $getToken;
    }

    /**
     * @inheritDoc
     */
    abstract public function handle(ServerRequestInterface $request): ResponseInterface;
}