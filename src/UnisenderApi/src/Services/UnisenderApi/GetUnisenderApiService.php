<?php

namespace UnisenderApi\Services\UnisenderApi;

use Unisender\ApiWrapper\UnisenderApi;
use UnisenderApi\Services\UnisenderApi\Interfaces\GetUnisenderApiInterface;

/**
 * Сервис получение UnisenderApi сервиса
 */
class GetUnisenderApiService implements GetUnisenderApiInterface
{
    /**
     * @inheritDoc
     */
    public function get(string $apiKey): UnisenderApi
    {
        return new UnisenderApi($apiKey);
    }
}
