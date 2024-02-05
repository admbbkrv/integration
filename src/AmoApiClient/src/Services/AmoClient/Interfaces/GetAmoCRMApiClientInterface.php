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
     * @param int $integrationId
     * @return AmoCRMApiClient
     */
    public function getAmoClient(
        int $integrationId
    ): AmoCRMApiClient;
}
