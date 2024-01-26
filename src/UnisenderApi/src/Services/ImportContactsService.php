<?php

declare(strict_types=1);

namespace UnisenderApi\Services;

use Unisender\ApiWrapper\UnisenderApi;

/**
 * Сервис для импорта контактов в Unisender
 */
class ImportContactsService implements ImportContactsInterface
{
    /**
     * @inheritDoc
     */
    public function importContacts(array $contacts, UnisenderApi $unisenderApi): array
    {
        $responce = $unisenderApi->importContacts($contacts);

        return json_decode($responce, true);
    }
}
