<?php

declare(strict_types=1);

namespace DataBase\Services\Integration\Get\Interfaces;

use DataBase\Models\Integration;

/**
 * Интерфейс получения Integration Model
 */
interface GetIntegrationInterface
{
    /**
     * Получение Integration Model
     * @param string $column
     * @param mixed $value
     * @return Integration|null
     */
    public function get(
        string $column,
        $value
    ): ?Integration;
}
