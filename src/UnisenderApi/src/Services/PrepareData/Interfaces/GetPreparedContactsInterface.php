<?php

declare(strict_types=1);

namespace UnisenderApi\Services\PrepareData\Interfaces;

/**
 * Интерфейс для обработки данныз контактов
 * для импорта в Unis
 */
interface GetPreparedContactsInterface
{
    /**
     * Возвращает массив контактов для импорта
     * @param array $data
     * @return ?array
     */
    public function prepare(array $data): ?array;
}
