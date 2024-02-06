<?php

declare(strict_types=1);

namespace UnisenderApi\Services\PrepareData;

use DataBase\Models\Contact;
use DataBase\Models\User;
use UnisenderApi\Services\PrepareData\Interfaces\GetPreparedContactsInterface;

/**
* Интерфейс для обработки данныз контактов
* для удаления в Unis
*/
class GetPreparedContactsForDeleteService implements
    GetPreparedContactsInterface
{
    /**
     * @inheritDoc
     */
    public function prepare(array $data): ?array
    {
        $contactData = $data['contacts']['delete'][0];

        $contactId = $contactData['id'];

        /** @var User $user */
        $user = User::query()
            ->where('account_id', $data['account']['id'])
            ->first();

        /** @var Contact $contact */
        $contact = $user->contacts()
            ->where('contact_id', $contactId)
            ->first();

        // Получаем текущие email адреса контакта
        $currentEmails = $contact->emails()->pluck('email')->all();
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
