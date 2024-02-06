<?php

declare(strict_types=1);

namespace DataBase\Services\Email\create\Interfaces;

use DataBase\Models\Email;

/**
 * Интерфейс создания email в базе данных
 */
interface SaveEmailInterface
{
    /**
     * Создаие Email в emails table
     * @param int $contactId
     * @param string $email
     * @return Email
     */
    public function save(
        int $contactId,
        string $email
    ): Email;
}
