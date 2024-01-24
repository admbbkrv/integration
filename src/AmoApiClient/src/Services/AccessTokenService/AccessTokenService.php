<?php

declare(strict_types=1);

namespace AmoApiClient\Services\AccessTokenService;

use League\OAuth2\Client\Token\AccessToken;

/**
 * Сервис для управления токеном доступа (Access Token)
 */
class AccessTokenService
{
    /**
     * @see SaveTokenInterface
     * @var SaveTokenInterface
     */
    private SaveTokenInterface $saveToken;

    /**
     * @see GetTokenInterface
     * @var GetTokenInterface
     */
    private GetTokenInterface $getToken;

    public function __construct(
        SaveTokenInterface $saveToken,
        GetTokenInterface $getToken
    ){
        $this->saveToken = $saveToken;
        $this->getToken = $getToken;
    }

    /**
     * @see SaveTokenInterface::save()
     * @param array $accessToken
     * @return void
     */
    public function saveToken(array $accessToken): void
    {
        $this->saveToken->save($accessToken);
    }

    /**
     * @see GetTokenInterface::get()
     * @param string $tokenFile
     * @return AccessToken
     */
    public function getToken(string $tokenFile): ?AccessToken
    {
        return $this->getToken->get($tokenFile);
    }
}
