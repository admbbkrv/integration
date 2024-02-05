<?php

declare(strict_types=1);

namespace AmoApiClient\Services\AmoClient;

use AmoApiClient\Services\AmoClient\Interfaces\GetAmoCRMApiClientInterface;
use AmoCRM\Client\AmoCRMApiClient;
use DataBase\Services\Integration\Get\GetIntegrationService;
use Exception;
use League\OAuth2\Client\Token\AccessTokenInterface;

/**
 * Сервис дял получения
 * экземпляра AmoCRMApiClient класса
 */
class GetAmoCRMApiClientService extends GetIntegrationService implements
    GetAmoCRMApiClientInterface
{
    /**
     * @inheritDoc
     */
    public function getAmoClient(
        int $integrationId
    ): AmoCRMApiClient {

        $integration = $this->get(
            'id',
            $integrationId
        );

        if ($integration === null) {
            throw new Exception(
                'Интеграции с таким ID не существует'
            );
        }

        $apiClient = new AmoCRMApiClient(
            $integration->client_id,
            $integration->client_secret,
            $integration->redirect_uri,
        );

        $apiClient->onAccessTokenRefresh(
            function (AccessTokenInterface $accessToken, string $baseDomain) {
                /** @var \DataBase\Models\ApiToken $apiToken */
                $apiToken = \DataBase\Models\ApiToken::query()
                    ->where('base_domain', $baseDomain)
                    ->first();
                $apiToken->access_token = $accessToken->getToken();
                $apiToken->expires = $accessToken->getExpires();
                $apiToken->refresh_token = $accessToken->getRefreshToken();

                $apiToken->save();
            }
        );

        return $apiClient;
    }
}
