<?php

declare(strict_types=1);

namespace DataBase\Services\ApiToken\create\Interfaces;

use DataBase\Models\ApiToken;

/**
 * Интерфейс сохранения AccessToken в БД
 */
interface SaveAccessTokenInterface
{
    /**
     * Сохранение AccessToken в БД
     * @param string $accessToken
     * @param int $expires
     * @param string $refreshToken
     * @param string $baseDomain
     * @param int $user_id
     * @return ApiToken
     */
    public function saveAccessToken(
        string $accessToken,
        int $expires,
        string $refreshToken,
        string $baseDomain,
        int $user_id
    ): ApiToken;
}
