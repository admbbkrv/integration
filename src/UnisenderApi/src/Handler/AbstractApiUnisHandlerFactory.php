<?php

declare(strict_types=1);

namespace UnisenderApi\Handler;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Unisender\ApiWrapper\UnisenderApi;

/**
 * Родительский абстрактный класс для фабрик handler
 * обрабатывающих запросы API Unisender
 */
abstract class AbstractApiUnisHandlerFactory
{
    abstract public function __invoke(ContainerInterface $container): RequestHandlerInterface;

    /**
     * Возвращает объект типа UnisenderApi
     * для работы с Unisender через API
     * @return UnisenderApi
     */
    protected function getUnisenderApi(): UnisenderApi
    {
        return new UnisenderApi($_ENV['API_KEY_UNISENDER']);
    }
}
