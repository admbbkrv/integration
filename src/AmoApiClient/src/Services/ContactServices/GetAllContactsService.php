<?php

declare(strict_types=1);

namespace AmoApiClient\Services\ContactServices;

use AmoApiClient\Services\ContactServices\Interfaces\GetAllContactsInterface;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\ContactsCollection;

/**
 * Сервис получения всех контактов из AmoCRM
 */
class GetAllContactsService implements GetAllContactsInterface
{
    /**
     * @inheritDoc
     */
    public function getContacts(AmoCRMApiClient $apiClient): ContactsCollection
    {
        return $apiClient->contacts()->get();
    }
}
