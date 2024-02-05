<?php

declare(strict_types=1);

namespace UnisenderApi\Services\PrepareData;

use DataBase\Models\Contact;
use UnisenderApi\Services\PrepareData\Interfaces\GetPreparedContactsForDeleteInterface;

/**
* Интерфейс для обработки данныз контактов
* для удаления в Unis
*/
class GetPreparedContactsForDeleteService implements
    GetPreparedContactsForDeleteInterface
{
    /**
     * @inheritDoc
     */
    public function prepare(array $contactData): array
    {
        $contact = Contact::query()
            ->where('contact_id', $contactData['id'])
            ->first();
        // Получаем текущие email адреса контакта
        $currentEmails = $contact->emails->pluck('email')->all();
        $dataForUnis = [];
        foreach ($currentEmails as $email) {
            $dataForUnis[] = [
                $email,
                1
            ];
        }
        $preparedContacts = [
            'unis' => [
                'field_names' => [
                    'email',
                    'delete',
                ],
                'data' => $dataForUnis,
            ],
            'bd' => [
                'contact_model' => $contact,
            ]
        ];

        return $preparedContacts;
    }
}
