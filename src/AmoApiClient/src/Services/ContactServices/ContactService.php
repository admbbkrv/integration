<?php

declare(strict_types=1);

namespace AmoApiClient\Services\ContactServices;

use AmoApiClient\Services\ContactServices\Interfaces\GetNamesWithEmailsInterface;
use AmoCRM\Collections\ContactsCollection;

/**
 * Сервис для работы с моделями и колекциями контакта
 */
class ContactService extends AbstractContactService implements GetNamesWithEmailsInterface
{
    /**
     * @inheritDoc
     */
    public function getNamesWithEmails(ContactsCollection $contacts): array
    {
        $contactsNamesAndEmailsArray = [];
        foreach ($contacts as $contact) {
            $contactsNamesAndEmailsArray[] = [
                'name' => $this->getName($contact),
                'email' => $this->getEmail($contact),
            ];
        }

        return $contactsNamesAndEmailsArray;
    }
}
