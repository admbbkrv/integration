<?php

declare(strict_types=1);

namespace DataBase\Services\ApiToken\get\Interfaces;

use League\OAuth2\Client\Token\AccessToken;

/**
 * Интерфейс получения AccessToken
 */
interface GetAccessTokenInterface
{
    /**
     * Получение AccessToken
     * @param string $baseDomain
     * @return ?AccessToken
     */
    public function getAccessToken(string $baseDomain): ?AccessToken;
}
