<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use AmoApiClient\Services\AccessTokenService\GetTokenInterface;
use AmoCRM\Client\AmoCRMApiClient;
use Mezzio\Router\RouterInterface;

/**
 * Фабрика для генерации MainApiHandler обработчика
 */
class MainApiHandlerFactory extends ApiHandlerFactory
{

    /**
     * Возвращает экземпляр обратчика типа ApiHandler
     * @param AmoCRMApiClient $apiClient
     * @param GetTokenInterface $getTokenInterface
     * @param RouterInterface $router
     * @return ApiHandler
     */
    public function getApiHandler(
        AmoCRMApiClient $apiClient,
        GetTokenInterface $getTokenInterface,
        RouterInterface $router
    ): ApiHandler {
        return new MainApiHandler($apiClient, $getTokenInterface, $router);
    }
}
