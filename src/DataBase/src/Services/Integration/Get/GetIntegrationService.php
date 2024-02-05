<?php

declare(strict_types=1);

namespace DataBase\Services\Integration\Get;

use DataBase\Models\Integration;
use DataBase\Services\Integration\Get\Interfaces\GetIntegrationInterface;

/**
 * Сервис получения Integration Model
 */
class GetIntegrationService implements GetIntegrationInterface
{
    /**
     * @inheritDoc
     */
    public function get(
        string $column,
        $value
    ): ?Integration {

        if ($column === 'id') {
            return Integration::query()->find($value);
        }

        return Integration::query()
            ->where($column, $value)
            ->first();
    }
}
