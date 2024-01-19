<?php

declare(strict_types=1);

namespace AmoApiClient\Middleware;

use AmoApiClient\Constants\AmoApiConstants;
use AmoApiClient\Services\AccessTokenService\AccessTokenService;
use AmoApiClient\Services\AccessTokenService\GetTokenInterface;
use AmoCRM\Client\AmoCRMApiClient;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ApiAmoAuthMiddleware implements MiddlewareInterface
{

    private AmoCRMApiClient $apiClient;
    private GetTokenInterface $getToken;

    public function __construct(
        AmoCRMApiClient $apiClient,
        GetTokenInterface $getToken
    ) {
        $this->apiClient = $apiClient;
        $this->getToken = $getToken;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $accessToken = $this->getToken->get(AmoApiConstants::TOKEN_FILE);

        if ($accessToken === null) {
            if (!session_status()) {
                session_start();
            }

            $state = bin2hex(random_bytes(16));
            $_SESSION['oauth2state'] = $state;

            $authorizationUrl = $this->apiClient->getOAuthClient()->getAuthorizeUrl([
                'state' => $state,
                'mode' => 'post_message',
            ]);

            return new RedirectResponse($authorizationUrl);
        }

        return $handler->handle($request);
    }
}
