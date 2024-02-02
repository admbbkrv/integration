<?php

declare(strict_types=1);

namespace AmoApiClient\Services\AmoClient;

use AmoApiClient\Services\AmoClient\Interfaces\GetAmoCRMApiClientInterface;
use AmoCRM\Client\AmoCRMApiClient;
use DataBase\Services\Integration\Get\GetIntegrationService;
use Exception;

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

        return new AmoCRMApiClient(
            $integration->client_id,
            $integration->client_secret,
            $integration->redirect_uri,
        );
    }
}
