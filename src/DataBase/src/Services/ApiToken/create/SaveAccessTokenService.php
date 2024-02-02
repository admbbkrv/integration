<?php

declare(strict_types=1);

namespace DataBase\Services\ApiToken\create;

use DataBase\Models\ApiToken;
use DataBase\Services\ApiToken\create\Interfaces\SaveAccessTokenInterface;

/**
 * Сервис сохранения AccessToken в БД
 */
class SaveAccessTokenService extends SaveApiTokenService implements
    SaveAccessTokenInterface
{
    /**
     * @inheritDoc
     */
    public function saveAccessToken(
        string $accessToken,
        int $expires,
        string $refreshToken,
        string $baseDomain,
        int $user_id
    ): ApiToken {
        $values = [
            'access_token' => $accessToken,
            'expires' => $expires,
            'refresh_token' => $refreshToken,
            'base_domain' => $baseDomain,
        ];

        return $this->save(
            $user_id,
            $values
        );
    }
}
