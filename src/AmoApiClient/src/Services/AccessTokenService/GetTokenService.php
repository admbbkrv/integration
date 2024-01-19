<?php

namespace AmoApiClient\Services\AccessTokenService;

use AmoApiClient\Services\AccessTokenService\GetTokenInterface;
use Illuminate\Validation\ValidationException;
use League\OAuth2\Client\Token\AccessToken;
use RuntimeException;

class GetTokenService implements GetTokenInterface
{

    /**
     * @inheritDoc
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function get(string $tokenFile): ?AccessToken
    {
        if (!file_exists($tokenFile)) {
//            throw new RuntimeException('Access token file not found');
            return null;
        }

        $accessToken = json_decode(
            file_get_contents($tokenFile),
            true
        );

        if (
            isset($accessToken)
            && isset($accessToken['accessToken'])
            && isset($accessToken['refreshToken'])
            && isset($accessToken['expires'])
            && isset($accessToken['baseDomain'])
        ) {
            return new AccessToken([
                'access_token' => $accessToken['accessToken'],
                'refresh_token' => $accessToken['refreshToken'],
                'expires' => $accessToken['expires'],
                'baseDomain' => $accessToken['baseDomain'],
            ]);
        }

//        throw new ValidationException('Invalid access token ' . var_export($accessToken, true));
        return null;
    }
}