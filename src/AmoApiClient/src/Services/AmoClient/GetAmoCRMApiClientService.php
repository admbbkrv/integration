<?php

declare(strict_types=1);

namespace AmoApiClient\Services\AmoClient;

use AmoApiClient\Services\AmoClient\Interfaces\GetAmoCRMApiClientInterface;
use AmoCRM\Client\AmoCRMApiClient;

/**
 * Сервис дял получения
 * экземпляра AmoCRMApiClient класса
 */
class GetAmoCRMApiClientService implements GetAmoCRMApiClientInterface
{
    /**
     * @inheritDoc
     */
    public function get(
        string $clientId,
        string $clientSecret,
        string $redirectUri
    ): AmoCRMApiClient {
        return new AmoCRMApiClient(
            $clientId,
            $clientSecret,
            $redirectUri
        );
    }
}
