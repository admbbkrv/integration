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
     * Сохранение ApiToken в БД
     * @param string $accessToken
     * @param int $expires
     * @param string $refreshToken
     * @param string $baseDomain
     * @param int|null $userId
     * @param string|null $apiKey
     * @return mixed
     */
    public function save(
        string $accessToken,
        int $expires,
        string $refreshToken,
        string $baseDomain,
        ?int $userId = null,
        ?string $apiKey = null
    ): ApiToken;
}
