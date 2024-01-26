<?php

declare(strict_types=1);

namespace UnisenderApi\Services;

use Unisender\ApiWrapper\UnisenderApi;

/**
 * Интерфейс импорта контактов в Unisender
 */
interface ImportContactsInterface
{
    /**
     * Метод импорта контактов в Unisender
     * @param array $contacts
     * @param UnisenderApi $unisenderApi
     * @return string
     */
    public function importContacts(array $contacts, UnisenderApi $unisenderApi): array;
}
