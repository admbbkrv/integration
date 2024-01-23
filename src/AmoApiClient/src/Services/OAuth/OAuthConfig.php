<?php

namespace AmoApiClient\Services\OAuth;

use AmoCRM\OAuth\OAuthConfigInterface;

class OAuthConfig implements OAuthConfigInterface
{

    /**
     * @inheritDoc
     */
    public function getIntegrationId(): string
    {
        return $_ENV['CLIENT_ID'];
    }

    /**
     * @inheritDoc
     */
    public function getSecretKey(): string
    {
        return $_ENV['CLIENT_SECRET'];
    }

    /**
     * @inheritDoc
     */
    public function getRedirectDomain(): string
    {
        return $_ENV['CLIENT_REDIRECT_URI'];
    }
}