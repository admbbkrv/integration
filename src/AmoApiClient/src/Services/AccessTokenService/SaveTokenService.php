<?php

namespace AmoApiClient\Services\AccessTokenService;

use AmoApiClient\Constants\AmoApiConstants;

/**
 * Сервис для сохранения данных токена доступа(Access Token)
 *  в токен файл
 */
class SaveTokenService implements SaveTokenInterface
{

    /**
     * @inheritDoc
     * @throws \RuntimeException
     */
    public function save(array $accessToken): void
    {
        if (
            isset($accessToken)
            && isset($accessToken['accessToken'])
            && isset($accessToken['refreshToken'])
            && isset($accessToken['expires'])
            && isset($accessToken['baseDomain'])
        ) {
            $data = [
                'accessToken' => $accessToken['accessToken'],
                'expires' => $accessToken['expires'],
                'refreshToken' => $accessToken['refreshToken'],
                'baseDomain' => $accessToken['baseDomain'],
            ];

            file_put_contents(AmoApiConstants::TOKEN_FILE, json_encode($data));

            return;
        }

        throw new \RuntimeException('Токен доступа не прошел валидацию');
    }
}