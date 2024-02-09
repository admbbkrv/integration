<?php

declare(strict_types=1);

namespace AmoApiClient\Services\AccessTokenService;

use Illuminate\Database\Eloquent\Model;
use League\OAuth2\Client\Token\AccessToken;

/** Сервис инициализации объекта AccessToken
 * из данных модели ApiToken
 */
class InitAccessTokenFromApiTokenService implements InitAccessTokenFromModelInterface
{
    /**
     * @inheritDoc
     */
    public function init(Model $model): AccessToken
    {
        return new AccessToken([
            'access_token' => $model->access_token,
            'expires' => $model->expires,
            'refresh_token' => $model->refresh_token,
            'base_domain' => $model->base_domain,
        ]);
    }
}
