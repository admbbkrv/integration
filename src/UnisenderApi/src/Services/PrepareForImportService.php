<?php

declare(strict_types=1);

namespace UnisenderApi\Services;

use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Models\ContactModel;

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
        $dataForUnis = [];
        $dataForBd = [];
        foreach ($contactsCollection as $contactModel) {
            /** @var ContactModel $contactModel */
            $emails = $contactModel->getCustomFieldsValues()
                ->getBy('fieldCode', 'EMAIL')->getValues();

            $contactId = $contactModel->getId();

            $dataForBd[$contactId] = [
                'contact_id' => $contactId,
            ];

            foreach ($emails as $email) {
                $dataForUnis[] = [
                    $email->getValue(),
                ];

                $dataForBd[$contactId]['emails']= [
                    ['email' => $email->getValue()],
                ];
            }
        }

        $preparedContacts = [
            'unis' => [
                'field_names' => [
                    'email',
                ],
                'data' => $dataForUnis,
            ],
            'bd' => $dataForBd
        ];

        return $preparedContacts;
    }
}
