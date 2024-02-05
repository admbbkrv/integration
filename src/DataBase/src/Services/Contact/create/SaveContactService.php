<?php

namespace DataBase\Services\Contact\create;

use DataBase\Models\Contact;
use DataBase\Services\Contact\create\Interfaces\SaveContactInterface;

/**
 * Сервис создания пользователя в бд
 */
class SaveContactService implements SaveContactInterface
{
    /**
     * @inheritDoc
     */
    public function save(
        int $contactId
    ): Contact {
        return Contact::query()->firstOrCreate(
            [
                'contact_id' => $contactId,
            ],
            [
                'contact_id' => $contactId,
            ],
        );
    }
}
