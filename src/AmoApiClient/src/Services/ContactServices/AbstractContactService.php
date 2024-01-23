<?php

declare(strict_types=1);

namespace AmoApiClient\Services\ContactServices;

use AmoApiClient\Services\ContactServices\GetEmailInterface;
use AmoApiClient\Services\ContactServices\GetNameInterface;
use AmoCRM\Models\ContactModel;

/**
 * Абстрактный класс для работы с контактами типа ContactModel
 */
abstract class AbstractContactService implements GetEmailInterface, GetNameInterface
{
    /**
     * @inheritDoc
     */
    public function getName(ContactModel $contact): string
    {
        return $contact->getName();
    }

    /**
     * @inheritDoc
     */
    public function getEmail(ContactModel $contact): ?string
    {
        $customFields = $contact->getCustomFieldsValues();
        $contactEmailField = $customFields->getBy('fieldCode', 'EMAIL');
        $email = $contactEmailField
            ? $contactEmailField->getValues()->current()->getValue()
            : null;

        return $email;
    }
}
