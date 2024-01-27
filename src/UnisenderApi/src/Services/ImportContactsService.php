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
    public function importContacts(
        array $contacts,
        UnisenderApi $unisenderApi
    ): array {
        if (count($contacts) > 500) {

            $chunks = array_chunk($contacts, 500);

            $responce = [];

            foreach ($chunks as $chunk) {
                $responce[] = $unisenderApi->importContacts($chunk);
            }

        } else {
            $responce = $unisenderApi->importContacts($contacts);
        }

        return json_decode($responce, true);
    }
}
