<?php

declare(strict_types=1);

namespace DataBase\Services\Integration\Create\Interfaces;

use DataBase\Models\Integration;

/**
 * Интерфейс сохранения интеграции в БД
 */
interface SaveIntegrationInterface
{
    /**
     * Сохранение Integration в БД
     * @param string $clientId
     * @param string $clientSecret
     * @param string $redirectUri
     * @return Integration
     */
    public function save(
        string $clientId,
        string $clientSecret,
        string $redirectUri
    ): Integration;
}
