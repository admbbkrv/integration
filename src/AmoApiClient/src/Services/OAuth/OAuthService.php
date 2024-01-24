<?php

declare(strict_types=1);

namespace AmoApiClient\Services\OAuth;

use AmoApiClient\Constants\AmoApiConstants;
use AmoCRM\OAuth\OAuthServiceInterface;
use League\OAuth2\Client\Token\AccessTokenInterface;

/**
 * Класс реализующий интерфейс необходимый для фабрики AmoCRMApiClient.
 * Класс предоставляет интерфейс сохранения токера
 */
class OAuthService implements OAuthServiceInterface
{
    /**
     * Мето сохранеия Access Token в файле
     * @param AccessTokenInterface $accessToken
     * @param string $baseDomain
     * @return void
     */
    public function saveOAuthToken(AccessTokenInterface $accessToken, string $baseDomain): void
    {
        if (
            isset($accessToken)
            && isset($accessToken['accessToken'])
            && isset($accessToken['refreshToken'])
            && isset($accessToken['expires'])
        ) {
            $data = [
                'accessToken' => $accessToken['accessToken'],
                'expires' => $accessToken['expires'],
                'refreshToken' => $accessToken['refreshToken'],
                'baseDomain' => $baseDomain,
            ];

            file_put_contents(AmoApiConstants::TOKEN_FILE, json_encode($data));

            return;
        }

        throw new \RuntimeException('Токен доступа не прошел валидацию');
    }
}
