<?php

declare(strict_types=1);

namespace UnisenderApi\Services;

use AmoCRM\Collections\ContactsCollection;

/**
 * Интерфейс подготавливает данные контактов для импорта
 */
interface PrepareForImportInterface
{
    /**
     * Возвращает валидные данные контактов для импорта в Unisender
     * @param ContactsCollection $contactsCollection
     * @return array
     */
    public function prepare(ContactsCollection $contactsCollection): array;
}
