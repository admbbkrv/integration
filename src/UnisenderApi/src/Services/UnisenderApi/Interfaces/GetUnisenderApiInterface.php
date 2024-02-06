<?php

declare(strict_types=1);

namespace UnisenderApi\Services\UnisenderApi\Interfaces;

use Unisender\ApiWrapper\UnisenderApi;

/**
 * Интерфейс получение UnisenderApi сервиса
 */
interface GetUnisenderApiInterface
{
    /**
     * Возврвщает UnisenderApi сервис
     * @param string $apiKey
     * @return UnisenderApi
     */
    public function get(string $apiKey): UnisenderApi;
}
