<?php

declare(strict_types=1);

namespace AmoApiClient\Middleware;

use AmoApiClient\Services\AccessTokenService\SaveTokenInterface;
use AmoApiClient\Services\AccessTokenService\SaveTokenService;
use AmoCRM\Client\AmoCRMApiClient;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SaveTokenMiddleware implements MiddlewareInterface
{
    private AmoCRMApiClient $apiClient;
    private SaveTokenInterface $saveToken;
    private RouterInterface $router;

    public function __construct(
        AmoCRMApiClient $apiClient,
        SaveTokenInterface $saveToken,
        RouterInterface $router
    ) {
        $this->apiClient = $apiClient;
        $this->saveToken = $saveToken;
        $this->router = $router;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $queryPrams = $request->getQueryParams();

        if (isset($_GET['referer'])) {
            $this->apiClient->setAccountBaseDomain($_GET['referer']);
        }

        try {
            $accessToken = $this->apiClient->getOAuthClient()->getAccessTokenByCode($queryPrams['code']);

            if (!$accessToken->hasExpired()) {
                $this->saveToken->save([
                    'accessToken' => $accessToken->getToken(),
                    'refreshToken' => $accessToken->getRefreshToken(),
                    'expires' => $accessToken->getExpires(),
                    'baseDomain' => $this->apiClient->getAccountBaseDomain(),
                ]);
            }
        } catch (\Throwable $e) {
            die((string)$e);
        }

        $uri = $this->router->generateUri('main');

        return new RedirectResponse($uri);
    }
}
