<?php

declare(strict_types=1);

namespace DataBase\Services\ApiToken\create;

use DataBase\Models\ApiToken;
use DataBase\Services\ApiToken\create\Interfaces\SaveApiTokenInterface;

/**
 * Сервис сохранения данных токенов в БД
 */
class SaveApiTokenService implements SaveApiTokenInterface
{
    /**
     * @inheritDoc
     */
    public function save(
        string $accessToken,
        int $expires,
        string $refreshToken,
        string $baseDomain,
        ?int $userId = null,
        ?string $apiKey = null
    ): ApiToken {
        $values = [
            'access_token' => $accessToken,
            'expires' => $expires,
            'refresh_token' => $refreshToken,
            'base_domain' => $baseDomain,
        ];

        if ($userId !== null) {
            $values['user_id'] = $userId;
        }

        if ($apiKey !== null) {
            $values['api_key'] = $apiKey;
        }

        return ApiToken::query()->updateOrCreate(
            [
                'base_domain' => $baseDomain,
            ],
            $values,
        );
    }
}
