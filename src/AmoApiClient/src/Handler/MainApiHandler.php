<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use AmoApiClient\Constants\AmoApiConstants;
use AmoApiClient\Services\AccessTokenService\AccessTokenService;
use AmoApiClient\Services\AccessTokenService\GetTokenInterface;
use AmoApiClient\Services\AccessTokenService\GetTokenService;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MainApiHandler extends ApiHandler implements RequestHandlerInterface
{
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
