<?php

declare(strict_types=1);

namespace UnisenderApi\Services\PrepareData;

use UnisenderApi\Services\PrepareData\Interfaces\GetPreparedContactsInterface;

/**
* Интерфейс для обработки данныз контактов
* для импорта в Unis
*/
class GetPreparedContactsForAddService implements
    GetPreparedContactsInterface
{
    /**
     * @inheritDoc
     */
    public function prepare(array $data): ?array
    {
        $dataForUnis = [];
        $dataForBd = [];
        $contactId = $data['id'];
        foreach ($data['custom_fields'] as $custom_field) {
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
