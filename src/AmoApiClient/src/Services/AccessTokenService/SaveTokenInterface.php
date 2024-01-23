<?php

declare(strict_types=1);

namespace AmoApiClient\Services\AccessTokenService;

/**
 * Интерфейс сохранения данные токена доступа(Access Token)
 * в токен файл
 */
interface SaveTokenInterface
{
    /**
     * Метод сохраняет данные из массива в токен файл
     * @param array $accessToken
     * @return void
     */
    public function save(array $accessToken): void;
}