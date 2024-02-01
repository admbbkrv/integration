<?php

declare(strict_types=1);

namespace DataBase\Services\User\create\Interfaces;

use DataBase\Models\User;

/**
 * Интерфейс создания пользователя в базе данных
 */
interface SaveUserInterface
{
    /**
     * Сохранение user в БД
     * @param int $account_id
     * @return User
     */
    public function save(int $account_id): User;
}
