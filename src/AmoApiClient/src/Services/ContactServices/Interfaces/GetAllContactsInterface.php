<?php

declare(strict_types=1);

namespace AmoApiClient\Services\ContactServices\Interfaces;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\ContactsCollection;

/**
 * Интерфейс получения всех контактов из AmoCRM
 */
interface GetAllContactsInterface
{
    /**
     * Возвращает колекцию всех контактов из AmoCRM
     * @param AmoCRMApiClient $apiClient
     * @return ContactsCollection
     */
    public function getContacts(AmoCRMApiClient $apiClient): ContactsCollection;
}
