<?php

namespace AmoApiClient\Handler;

use AmoApiClient\Services\AccessTokenService\GetTokenInterface;
use AmoCRM\Client\AmoCRMApiClient;
use Mezzio\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Абстрактный родительский класс обработчиков
 * для работы с API AmoCRM
 */
abstract class ApiHandler implements RequestHandlerInterface
{
    /**
     * Объект API клиента
     * @var AmoCRMApiClient
     */
    protected AmoCRMApiClient $apiClient;
    /**
     * Интерфейс для получения файла Access Token
     * @var GetTokenInterface
     */
    protected GetTokenInterface $getTokenService;
    /**
     * Интерфейс для работы с роутингом
     * @var RouterInterface
     */
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