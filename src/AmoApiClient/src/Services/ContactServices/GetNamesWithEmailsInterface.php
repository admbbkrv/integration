<?php

namespace AmoApiClient\Services\ContactServices;

use AmoCRM\Collections\ContactsCollection;

/**
 * Интерфейс для получения имен и электронных почт контактов
 */
interface GetNamesWithEmailsInterface
{
    /**
     * Метод возвращает массив с именами и почтами контактов
     * @param ContactsCollection $contacts
     * @return array
     */
    public function getNamesWithEmails(ContactsCollection $contacts): array;
}