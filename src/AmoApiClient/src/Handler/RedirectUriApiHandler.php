<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use AmoApiClient\Services\AccessTokenService\SaveTokenInterface;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Класс обработчика отвественного за получение и обработку данных
 * авторизации полученных от AmoCRM. /amo_redirect_uri
 */
class RedirectUriApiHandler implements RequestHandlerInterface
{

    /**
     * Интерфейс для сохранения данных Access Token в файле
     * @var SaveTokenInterface
     */
    private SaveTokenInterface $saveTokenService;
    /**
     * Объект API клиента
     * @var AmoCRMApiClient
     */
    private AmoCRMApiClient $apiClient;
    /**
     * Интерфейс для работы с роутингом
     * @var RouterInterface
     */
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

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws AmoCRMoAuthApiException
     */
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
