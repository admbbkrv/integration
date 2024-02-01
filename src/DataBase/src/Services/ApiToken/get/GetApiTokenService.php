<?php

declare(strict_types=1);

namespace DataBase\Services\ApiToken\get;

use DataBase\Models\ApiToken;
use DataBase\Services\ApiToken\get\Interfaces\GetApiTokenInterface;

/**
 * Сервис получения ApiToken Model
 */
class GetApiTokenService implements GetApiTokenInterface
{
    /**
     * @inheritDoc
     */
    public function get(
        string $column,
        $value
    ): ?ApiToken {

        if ($column === 'id') {
            return ApiToken::query()->find($value);
        }

        return ApiToken::query()->where($column, $value)->first();
    }
}
