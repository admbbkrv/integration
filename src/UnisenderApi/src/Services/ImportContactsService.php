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
        $arrayLength = count($contacts);

        if ($arrayLength > 500) {
            $chunks = array_chunk($contacts, 500);

            $result = [];
            foreach ($chunks as $chunk) {
                $responce = $unisenderApi->importContacts($chunk);
                $result[] = json_decode($responce, true);
            }

            return $result;
        }

        $responce = $unisenderApi->importContacts($contacts);

        return json_decode($responce, true);
    }
}
