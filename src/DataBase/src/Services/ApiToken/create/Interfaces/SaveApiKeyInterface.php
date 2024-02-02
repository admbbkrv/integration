<?php

declare(strict_types=1);

namespace DataBase\Services\ApiToken\create\Interfaces;

use DataBase\Models\ApiToken;

/**
 * Интерфейс сохранения api_key в БД
 */
interface SaveApiKeyInterface
{
    /**
     * Сохранение Api Key в БД
     * @param string $apiKey
     * @param int $user_id
     * @return ApiToken
     */
    public function saveApiKey(
        string $apiKey,
        int $user_id
    ): ApiToken;
}
