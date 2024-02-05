<?php

declare(strict_types=1);

namespace UnisenderApi\Services\PrepareData\Interfaces;

/**
 * Интерфейс для обработки данныз контактов
 * для удаления в Unis
 */
interface GetPreparedContactsForDeleteInterface
{
    /**
     * Возвращает массив контактов для импорта
     * @param array $data
     * @return array
     */
    public function prepare(array $contactData): array;
}
