<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use AmoApiClient\Constants\AmoApiConstants;
use AmoApiClient\Services\AccessTokenService\AccessTokenService;
use AmoApiClient\Services\AccessTokenService\GetTokenInterface;
use AmoCRM\Client\AmoCRMApiClient;
use Psr\Container\ContainerInterface;

class ApiMainHandlerFactory
{
    public function __invoke(ContainerInterface $container) : ApiMainHandler
    {
        $apiClient = $container->get('AmoClientWithToken');
        $getTokenService = $container->get(GetTokenInterface::class);
        $accessToken = $getTokenService->get(AmoApiConstants::TOKEN_FILE);

        return new ApiMainHandler(
            $apiClient,
            $accessToken
        );
    }
}
