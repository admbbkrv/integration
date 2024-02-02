<?php

declare(strict_types=1);

namespace DataBase\Services\User\get;

use DataBase\Models\User;
use DataBase\Services\User\get\Interfaces\GetUserInterface;

/**
 * Сервис получения User Model
 */
class GetUserService implements GetUserInterface
{
    /**
     * @inheritDoc
     */
    public function get(
        string $column,
        $value
    ): ?User {
        if ($column === 'id') {
            return User::query()->find($value);
        }

        return User::query()
            ->where($column, $value)
            ->first();
    }
}
