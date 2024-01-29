<?php

namespace DataBase\Services;

use DataBase\Models\User;
use DataBase\Services\Interfaces\CreateUserInterface;

/**
 * Сервис создания пользователя в бд
 */
class CreateUserService implements CreateUserInterface
{
    /**
     * @inheritDoc
     */
    public function create(string $email, string $password): User
    {
        return User::query()->create([
            'user_email' => $email,
            'user_password' => password_hash($password, PASSWORD_DEFAULT),
        ]);
    }
}
