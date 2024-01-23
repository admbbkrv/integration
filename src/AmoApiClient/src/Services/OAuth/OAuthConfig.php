<?php

namespace AmoApiClient\Services\OAuth;

use AmoCRM\OAuth\OAuthConfigInterface;

/**
 * Класс получения данных интеграции для ее аутенцификации
 */
class OAuthConfig implements OAuthConfigInterface
{
    /**
     * Возвращает CLIENT_ID интеграции
     * @return string
     */
    public function getIntegrationId(): string
    {
        return $_ENV['CLIENT_ID'];
    }

    /**
     * Возвращает CLIENT_SECRET интеграции
     * @return string
     */
    public function getSecretKey(): string
    {
        return $_ENV['CLIENT_SECRET'];
    }

    /**
     * Возвращает CLIENT_REDIRECT_URI интеграции
     * @return string
     */
    public function getRedirectDomain(): string
    {
        return $_ENV['CLIENT_REDIRECT_URI'];
    }
}