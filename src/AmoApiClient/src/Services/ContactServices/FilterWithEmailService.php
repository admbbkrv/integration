<?php

declare(strict_types=1);

namespace AmoApiClient\Services\ContactServices;

use AmoApiClient\Services\ContactServices\Interfaces\FilterWithEmailInterface;
use AmoCRM\Collections\ContactsCollection;

/**
 * Фильтрует коллекцию контактов по признаку 'имеет поле email'
 */
class FilterWithEmailService implements FilterWithEmailInterface
{
    /**
     * @inheritDoc
     */
    public function filterWithEmail(ContactsCollection $contactsCollection): ContactsCollection
    {
        foreach ($contactsCollection as $contactModel) {

            $email_fields = $contactModel->getCustomFieldsValues()->getBy('fieldCode', 'EMAIL');

            if (!$email_fields) {
                $contactsCollection->removeBy(
                    'id',
                    $contactModel->getId()
                );
            }
        }
        return $contactsCollection;
    }
}
