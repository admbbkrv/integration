<?php

declare(strict_types=1);

namespace DataBase\Services\ApiToken\get\Interfaces;

use DataBase\Models\ApiToken;

/**
 * Инетерфейс получения ApiToken Model
 */
interface GetApiTokenInterface
{
    /**
     * Получение ApiToken Model
     * @param string $column
     * @param mixed $value
     * @return ApiToken|null
     */
    public function get(
        string $column,
        $value
    ): ?ApiToken;
}
