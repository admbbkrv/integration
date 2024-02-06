<?php

declare(strict_types=1);

namespace DataBase\Services\Email\create;

use DataBase\Models\Email;
use DataBase\Services\Email\create\Interfaces\SaveEmailInterface;

/**
 * Сервис создания пользователя в бд
 */
class SaveEmailService implements SaveEmailInterface
{
    /**
     * @inheritDoc
     */
    public function save(
        int $contactId,
        string $email
    ): Email {
        return Email::query()->create([
            'contact_id' => $contactId,
            'email' => $email,
        ]);
    }
}
