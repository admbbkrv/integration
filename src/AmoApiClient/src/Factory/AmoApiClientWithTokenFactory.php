<?php

namespace AmoApiClient\Factory;

use AmoApiClient\Constants\AmoApiConstants;
use AmoApiClient\Services\AccessTokenService\AccessTokenService;
use AmoCRM\Client\AmoCRMApiClient;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Psr\Container\ContainerInterface;

class AmoApiClientWithTokenFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $accessTokenService = $container->get(AccessTokenService::class);
        $accessToken = $accessTokenService->getToken(AmoApiConstants::TOKEN_FILE);

        $apiClient = $container->get(AmoCRMApiClient::class);

        $apiClient->setAccessToken($accessToken)
            ->setAccountBaseDomain($accessToken->getValues()['baseDomain'])
            ->onAccessTokenRefresh(
                function (AccessTokenInterface $accessToken, string $baseDomain) use ($accessTokenService) {
                    $accessTokenService->saveToken(
                        [
                            'accessToken' => $accessToken->getToken(),
                            'refreshToken' => $accessToken->getRefreshToken(),
                            'expires' => $accessToken->getExpires(),
                            'baseDomain' => $baseDomain,
                        ]
                    );
                }
            );

        return $apiClient;
    }
}