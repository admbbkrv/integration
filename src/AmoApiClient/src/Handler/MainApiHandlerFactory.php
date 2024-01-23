<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use AmoApiClient\Constants\AmoApiConstants;
use AmoApiClient\Services\AccessTokenService\AccessTokenService;
use AmoApiClient\Services\AccessTokenService\GetTokenInterface;
use AmoCRM\Client\AmoCRMApiClient;
use Psr\Container\ContainerInterface;

class ApiMainHandlerFactory extends ApiHandlerFactory
{

    public function getApiHandler(AmoCRMApiClient $apiClient, GetTokenInterface $getTokenInterface): ApiHandler
    {
        return new ApiMainHandler($apiClient, $getTokenInterface);
    }
}
