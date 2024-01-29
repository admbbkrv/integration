<?php

declare(strict_types=1);

namespace UnisenderApi\Services;

use AmoCRM\Collections\ContactsCollection;

/**
 * Сервис для подготовки данных контактов для импорта в Unisender
 */
class PrepareForImportService implements PrepareForImportInterface
{
    /**
     * @inheritDoc
     */
    public function prepare(ContactsCollection $contactsCollection): array
    {
        $data = [];
        foreach ($contactsCollection as $contactModel) {
            $name = $contactModel->getName();
            $emails = $contactModel->getCustomFieldsValues()
                ->getBy('fieldCode', 'EMAIL')->getValues();

            foreach ($emails as $email) {
                $data[] = [
                    $email->getValue(),
                    $name,
                ];
            }
        }

        $preparedContacts = [
            'field_names' => [
                'email',
                'Name',
            ],
            'data' => $data,
        ];

        return $preparedContacts;
    }
}
