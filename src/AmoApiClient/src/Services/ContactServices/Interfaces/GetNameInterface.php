<?php

declare(strict_types=1);

namespace AmoApiClient\Services\ContactServices\Interfaces;

use AmoCRM\Models\ContactModel;

/**
 * Интерфейс для получения массива с именами контактов
 */
interface GetNameInterface
{
    /**
     * Возвращает имя контакта
     * @param ContactModel $contact
     * @return string
     */
    public function getName(ContactModel $contact): string;
}
