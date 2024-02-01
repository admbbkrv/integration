<?php

namespace DataBase\Services\ApiToken\get;

use DataBase\Services\ApiToken\get\Interfaces\GetAccessTokenInterface;
use League\OAuth2\Client\Token\AccessToken;

/**
 * Сервис получения AccessToken
 */
class GetAccessTokenService extends GetApiTokenService implements
    GetAccessTokenInterface
{
    /**
     * @inheritDoc
     */
    public function getAccessToken(string $baseDomain): ?AccessToken
    {
        /** get ApiToken Model */
        $apiToken = $this->get(
            'base_domain',
            $baseDomain
        );

        if ($apiToken === null) {
            return null;
        }

        return new AccessToken([
            'access_token' => $apiToken->access_token,
            'expires' => $apiToken->expires,
            'refresh_token' => $apiToken->refresh_token,
            'base_domain' => $apiToken->base_domain,
        ]);
    }
}
