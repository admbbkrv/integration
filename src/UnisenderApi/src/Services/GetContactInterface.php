<?php

declare(strict_types=1);

namespace UnisenderApi\Services;

use Laminas\Diactoros\Response\JsonResponse;
use Unisender\ApiWrapper\UnisenderApi;

/**
 * Интерфейс для получения контакты из сервиса Unisender
 */
interface GetContactInterface
{
    /**
     * Метод получения контакта
     * @param UnisenderApi $unisenderApi
     * @param string $email
     * @return JsonResponse
     */
    public function getContact(UnisenderApi $unisenderApi, string $email): array;
}