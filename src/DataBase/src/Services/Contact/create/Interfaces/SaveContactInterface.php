<?php

declare(strict_types=1);

namespace DataBase\Services\Contact\create\Interfaces;

use DataBase\Models\Contact;

/**
 * Интерфейс создания контакта в базе данных
 */
interface SaveContactInterface
{
    /**
     * Создание Contact в contacts table
     * @param int $contactId
     * @return Contact
     */
    public function save(
        int $contactId
    ): Contact;
}
