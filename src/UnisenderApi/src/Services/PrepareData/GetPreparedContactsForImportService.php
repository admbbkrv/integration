<?php

declare(strict_types=1);

namespace UnisenderApi\Services\PrepareData;

use UnisenderApi\Services\PrepareData\Interfaces\GetPreparedContactsForImportInterface;

/**
* Интерфейс для обработки данныз контактов
* для импорта в Unis
*/
class GetPreparedContactsForImportService implements
    GetPreparedContactsForImportInterface
{
    /**
     * @inheritDoc
     */
    public function prepare(array $contactData): array
    {
        $dataForUnis = [];
        $dataForBd = [];
        $name = $contactData['name'];
        $contactId = $contactData['id'];
        foreach ($contactData['custom_fields'] as $custom_field) {
            if ($custom_field['code'] === 'EMAIL') {
                foreach ($custom_field['values'] as $value) {
                    $dataForUnis[] = [
                        $value['value'],
                    ];
                    $dataForBd[] = [
                        'email' => $value['value']
                    ];
                }
            }
        }

        $preparedContacts = [
            'unis' => [
                'field_names' => [
                    'email',
                ],
                'data' => $dataForUnis,
            ],
            'bd' => [
                'contact_id' => $contactId,
                'emails' => $dataForBd
            ]
        ];

        return $preparedContacts;
    }
}
