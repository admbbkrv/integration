<?php

declare(strict_types=1);

namespace AmoApiClient\Services\AccessTokenService;

use Illuminate\Database\Eloquent\Model;
use League\OAuth2\Client\Token\AccessToken;

/** Интерфейс инициализации
 * объекта AccessToken из данных модели
 */
interface InitAccessTokenFromModelInterface
{
    /** Возврвщает объект AccessToken класса
     * @param Model $model
     * @return AccessToken
     */
    public function init(Model $model): AccessToken;
}
