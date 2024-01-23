<?php

declare(strict_types=1);

namespace AmoApiClient\Middleware;

use Laminas\Diactoros\Response\RedirectResponse;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Mezzio\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ApiAmoAuthMiddleware implements MiddlewareInterface
{
    private ?AccessTokenInterface $accessToken;
    private RouterInterface $router;

    public function __construct(
        ?AccessTokenInterface $accessToken,
        RouterInterface $router
    ) {
        $this->accessToken = $accessToken;
        $this->router = $router;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        if ($this->accessToken === null) {

            $authorizationUrl = $this->router->generateUri('amo_auth');

            return new RedirectResponse($authorizationUrl);
        }

        return $handler->handle($request);
    }
}
