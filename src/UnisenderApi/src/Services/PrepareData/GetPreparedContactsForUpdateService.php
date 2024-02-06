<?php

declare(strict_types=1);

namespace UnisenderApi\Services\PrepareData;

use DataBase\Models\Contact;
use DataBase\Models\User;
use UnisenderApi\Services\PrepareData\Interfaces\GetPreparedContactsInterface;

/**
* Интерфейс для обработки данныз контактов
* для обновления в Unis
*/
class GetPreparedContactsForUpdateService implements
    GetPreparedContactsInterface
{
    /**
     * @inheritDoc
     */
    public function prepare(array $data): ?array
    {
        $contactData = $data['contacts']['update'][0];
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

        // Определяем email адреса к удалению и к добавлению
        $emailsToDelete = array_diff($currentEmails, $updatedEmails);
        $emailsToAdd = array_diff($updatedEmails, $currentEmails);

        //Если нет изменений в email контакта возвращает null
        if (empty($emailsToDelete) && empty($emailsToAdd)) {
            return null;
        }

        //проверка есть ли email для удаления
        if (!empty($emailsToDelete)) {
            foreach ($emailsToDelete as $emailToDelete) {
                $dataForUnis[] = [
                    $emailToDelete,
                    1
                ];
            }
            $preparedContacts['bd']['emails']['delete'] = $emailsToDelete;
        }

        //проверка есть ли email для добавления
        if (!empty($emailsToAdd)) {
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
            $preparedContacts['bd']['emails']['create'] = $emailsToCreate;
        }

        $preparedContacts['unis'] = [
            'field_names' => [
                'email',
                'delete',
            ],
            'data' => $dataForUnis,
        ];
        $preparedContacts['bd']['contact_model'] = $contact;

        return $preparedContacts;
    }
}
