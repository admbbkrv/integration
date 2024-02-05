<?php

declare(strict_types=1);

namespace UnisenderApi\Services\PrepareData;

use DataBase\Models\Contact;
use UnisenderApi\Services\PrepareData\Interfaces\GetPreparedContactsForUpdateInterface;

/**
* Интерфейс для обработки данныз контактов
* для обновления в Unis
*/
class GetPreparedContactsForUpdateService implements
    GetPreparedContactsForUpdateInterface
{
    /**
     * @inheritDoc
     */
    public function prepare(array $contactData): array
    {
        $dataForUnis = [];
        $updatedEmails = [];
        $contactId = $contactData['id'];
        foreach ($contactData['custom_fields'] as $custom_field) {
            if ($custom_field['code'] === 'EMAIL') {
                foreach ($custom_field['values'] as $value) {
                    $updatedEmails[] = $value['value'];
                }
            }
        }
        $contact = Contact::query()
            ->where('contact_id', $contactId)
            ->first();

        // Получаем текущие email адреса контакта
        $currentEmails = $contact->emails->pluck('email')->all();

        // Определяем email адреса к удалению и к добавлению
        $emailsToDelete = array_diff($currentEmails, $updatedEmails);
        $emailsToAdd = array_diff($updatedEmails, $currentEmails);

        foreach ($emailsToDelete as $emailToDelete) {
            $dataForUnis[] = [
                $emailToDelete,
                1
            ];
        }
        $emailsToCreate = [];
        foreach ($emailsToAdd as $emailToAdd) {
            $dataForUnis[] = [
                $emailToAdd,
                0
            ];
            $emailsToCreate[] = [
                'email' => $emailToAdd,
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
                'emails' => [
                    'create' => $emailsToCreate,
                    'delete' => $emailsToDelete,
                ]
            ]
        ];

        return $preparedContacts;
    }
}
