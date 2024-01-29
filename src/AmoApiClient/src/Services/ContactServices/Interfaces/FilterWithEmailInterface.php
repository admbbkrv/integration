<?php

declare(strict_types=1);

namespace AmoApiClient\Services\ContactServices\Interfaces;

use AmoCRM\Collections\ContactsCollection;

/**
 * Интерфейс фильтрует колекцию контактов по признаку "имеет email"
 */
interface FilterWithEmailInterface
{
    /**
     * Возвращает коллекцию контактов с полем email
     * @param ContactsCollection $contactsCollection
     * @return ContactsCollection
     */
    public function filterWithEmail(ContactsCollection $contactsCollection): ContactsCollection;
}
