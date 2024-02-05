<?php

declare(strict_types=1);

namespace DataBase\Services\ApiToken\create;

use DataBase\Models\ApiToken;
use DataBase\Services\ApiToken\create\Interfaces\SaveApiTokenInterface;
use Exception;

/**
 * Сервис сохранения данных токенов в БД
 */
class SaveApiTokenService implements SaveApiTokenInterface
{
    /**
     * @inheritDoc
     */
    public function save(
        int $userId,
        array $values
    ): ApiToken {

        if (
            (
                isset($values['access_token'])
                && isset($values['expires'])
                && isset($values['refresh_token'])
                && isset($values['base_domain'])
            )
            || isset($values['api_key'])
        ) {
            return ApiToken::query()->updateOrCreate(
                ['user_id' => $userId,],
                $values,
            );
        }

        throw new Exception('Access Token or Api Key data has not been passed');
    }
}
