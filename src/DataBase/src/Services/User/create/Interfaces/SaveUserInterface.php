<?php

declare(strict_types=1);

namespace DataBase\Services\Interfaces\User;

use DataBase\Models\User;

/**
 * Интерфейс создания пользователя в базе данных
 */
interface CreateUserInterface
{
    /**
     * Метод создания пользователя в бд
     * @param int $account_id
     * @return User
     */
    public function create(int $account_id): User;
}
