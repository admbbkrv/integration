<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use AmoApiClient\Constants\AmoApiConstants;
use AmoApiClient\Services\AccessTokenService\AccessTokenService;
use AmoCRM\Client\AmoCRMApiClient;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ApiMainHandler implements RequestHandlerInterface
{
    /**
     * Объект API клиента. ПРИМЕЧАНИЕ! Для обработчиков
     *  объекты этого типа инициализируется отдельная фабрика AmoApiClientWithTokenFactory.
     * @var AmoCRMApiClient
     */
    private AmoCRMApiClient $apiClient;

    /**
     * @var AccessTokenService
     */
    private AccessTokenService $accessTokenService;

    public function __construct(
        AmoCRMApiClient $apiClient,
        AccessTokenInterface $accessToken
    ) {
        $this->apiClient = $apiClient;
        $this->accessToken = $accessToken;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $ownerDetails = $this->apiClient->getOAuthClient()->getResourceOwner($this->accessToken);

        return new HtmlResponse(sprintf('<h1>Hello, %s!</h1>', $ownerDetails->getName()));
    }
}
