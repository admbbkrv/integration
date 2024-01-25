<?php

namespace UnisenderApi\Handler\Traits;

use Unisender\ApiWrapper\UnisenderApi;

/**
* Трейт для получения сервиса
 * для работы с Unisender через API
*/
trait GetUnisenderApiServiceTrait
{
    /**
     * Возвращает объект типа UnisenderApi
     * для работы с Unisender через API
     * @return UnisenderApi
     */
    private function getUnisenderApi(): UnisenderApi
    {
        return new UnisenderApi($_ENV['API_KEY_UNISENDER']);
    }
}