<?php

declare(strict_types=1);

namespace AmoApiClient\Services\ContactServices\Interfaces;

use AmoCRM\Models\ContactModel;

/**
 * Интерфейс для получения массива с электронными почтами контактов
 */
interface GetEmailInterface
{
    /**
     * Возвращает почту контакта
     * @param ContactModel $contact
     * @return ?string
     */
    public function getEmail(ContactModel $contact): ?string;
}
