<?php

declare(strict_types=1);

namespace AmoApiClient\Services\AccessTokenService;

use League\OAuth2\Client\Token\AccessToken;

/**
 * Интерфейс сохранения данные токена доступа(Access Token)
 * в токен файл
 */
interface GetTokenInterface
{
    /**
     * Метод возвращает токен доступа (Access Token)
     * @param string $tokenFile
     * @return ?AccessToken
     */
    public function get(string $tokenFile): ?AccessToken;
}
