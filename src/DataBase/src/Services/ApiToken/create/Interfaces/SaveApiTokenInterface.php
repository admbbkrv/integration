<?php

declare(strict_types=1);

namespace DataBase\Services\ApiToken\create\Interfaces;

use DataBase\Models\ApiToken;

/**
 * Интерфейс сохранения данных токенов в БД
 */
interface SaveApiTokenInterface
{
    /**
     * Создание записи в api_tokens table
     * @param int $userId
     * @param array $values
     * @return ApiToken
     */
    public function save(
        int $userId,
        array $values
    ): ApiToken;
}
