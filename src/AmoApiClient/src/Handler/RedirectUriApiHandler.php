<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use AmoApiClient\Services\AccessTokenService\SaveTokenInterface;
use AmoCRM\Client\AmoCRMApiClient;
use http\Exception\RuntimeException;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RedirectUriApiHandler implements RequestHandlerInterface
{
    private SaveTokenInterface $saveTokenService;
    private AmoCRMApiClient $apiClient;
    private RouterInterface $router;

    public function __construct(
        AmoCRMApiClient $apiClient,
        SaveTokenInterface $saveTokenService,
        RouterInterface $router
    ) {
        $this->saveTokenService = $saveTokenService;
        $this->apiClient = $apiClient;
        $this->router = $router;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $queryParams = $request->getQueryParams();

        if (isset($queryParams['referer'])) {
            $this->apiClient->setAccountBaseDomain($queryParams['referer']);
        }

        $accessToken = $this->apiClient->getOAuthClient()->getAccessTokenByCode($queryParams['code']);

        $this->saveTokenService->save([
            'accessToken' => $accessToken->getToken(),
            'refreshToken' => $accessToken->getRefreshToken(),
            'expires' => $accessToken->getExpires(),
            'baseDomain' => $this->apiClient->getAccountBaseDomain(),
        ]);

        $uri = $this->router->generateUri('amo_main');

        return new RedirectResponse($uri);
    }
}
