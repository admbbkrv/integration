<?php

declare(strict_types=1);

namespace DataBase\Services\User\get\Interfaces;

use DataBase\Models\User;

/**
 * Интерфейс получения User Model
 */
interface GetUserInterface
{
    /**
     * Получение User Model
     * @param string $column
     * @param mixed $value
     * @return ?User
     */
    public function get(
        string $column,
        $value
    ): ?User;
}
