<?php

namespace DataBase\Services\User\create;

use DataBase\Models\User;
use DataBase\Services\User\create\Interfaces\SaveUserInterface;

/**
 * Сервис создания пользователя в бд
 */
class SaveUserService implements SaveUserInterface
{
    /**
     * @param int $account_id
     * @inheritDoc
     */
    public function save(int $account_id): User
    {
        return User::query()->firstOrCreate(
            [
                'account_id' => $account_id,
            ],
            [
                'account_id' => $account_id,
            ],
        );
    }
}
