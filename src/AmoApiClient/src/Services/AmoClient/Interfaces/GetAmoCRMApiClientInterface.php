<?php

declare(strict_types=1);

namespace AmoApiClient\Services\AmoClient\Interfaces;

use AmoCRM\Client\AmoCRMApiClient;

/**
 * Интерфейс дял получения
 * экземпляра AmoCRMApiClient класса
 */
interface GetAmoCRMApiClientInterface
{
    /**
     * Возвращает объект AmoCRMApiClient класса
     * @param string $clientId
     * @param string $clientSecret
     * @param string $redirectUri
     * @return AmoCRMApiClient
     */
    public function get(
        string $clientId,
        string $clientSecret,
        string $redirectUri
    ): AmoCRMApiClient;
}
