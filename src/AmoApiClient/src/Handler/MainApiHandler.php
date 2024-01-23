<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use AmoApiClient\Constants\AmoApiConstants;
use AmoApiClient\Services\AccessTokenService\GetTokenInterface;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Класс обработчика запросов на галвную страницу /amo/main
 */
class MainApiHandler implements RequestHandlerInterface
{
    /**
     * @var AmoCRMApiClient
     */
    private AmoCRMApiClient $apiClient;
    /**
     * @var GetTokenInterface
     */
    private GetTokenInterface $getTokenService;
    /**
     * @var RouterInterface
     */
    private RouterInterface $router;

    public function __construct(
        AmoCRMApiClient $apiClient,
        GetTokenInterface $getTokenService,
        RouterInterface $router
    ) {
        $this->apiClient = $apiClient;
        $this->getTokenService = $getTokenService;
        $this->router = $router;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $accessToken = $this->getTokenService->get(AmoApiConstants::TOKEN_FILE);

        if ($accessToken->hasExpired()) {
            try {
                $accessToken = $this->apiClient->getOAuthClient()->getAccessTokenByRefreshToken($accessToken->getRefreshToken());
            } catch (AmoCRMoAuthApiException $e) {

                $uri = $this->router->generateUri('amo_auth');

                return new RedirectResponse($uri);
            }
        }

        $ownerDetails = $this->apiClient->getOAuthClient()->getResourceOwner($accessToken)->toArray();

        return new JsonResponse($ownerDetails);
    }
}
