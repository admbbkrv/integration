<?php

declare(strict_types=1);

namespace AmoApiClient\Middleware;

use DataBase\Services\ApiToken\get\Interfaces\GetAccessTokenInterface;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Класс middleware для проверки наличия токена авторизации
 */
class ApiAmoAuthMiddleware implements MiddlewareInterface
{
    /**
     * @var GetAccessTokenInterface
     */
    private GetAccessTokenInterface $getAccessToken;
    /**
     * @var RouterInterface
     */
    private RouterInterface $router;

    public function __construct(
        GetAccessTokenInterface $getAccessToken,
        RouterInterface $router
    ) {
        $this->getAccessToken = $getAccessToken;
        $this->router = $router;
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ) : ResponseInterface {

        $postParams = $request->getParsedBody();
        $baseDomain = $postParams['subdomain'] . '.amocrm.ru';
        $accessToken = $this->getAccessToken->getAccessToken($baseDomain);

        if ($accessToken === null) {
            $paramsForUri = '?' . $request->getUri()->getQuery();

            $authorizationUrl = $this->router
                ->generateUri('amo_auth') . $paramsForUri;

            return new RedirectResponse($authorizationUrl);
        }

        return $handler->handle($request);
    }
}
