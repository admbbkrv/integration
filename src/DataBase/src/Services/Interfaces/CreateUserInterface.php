<?php

declare(strict_types=1);

namespace DataBase\Services\Interfaces;

use DataBase\Models\User;

/**
 * Интерфейс создания пользователя в базе данных
 */
interface CreateUserInterface
{
    /**
     * Метод создания пользователя в бд
     * @param string $email
     * @param string $password
     * @return User
     */
    public function create(string $email, string $password): User;
}
