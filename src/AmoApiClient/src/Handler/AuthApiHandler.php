<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use AmoApiClient\Services\AmoClient\Interfaces\GetAmoCRMApiClientInterface;
use DataBase\Services\Integration\Get\Interfaces\GetIntegrationInterface;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Класс обработчка, который отвественен за страницу авторизации API AmoCRN /auth
 */
class AuthApiHandler implements RequestHandlerInterface
{
    private GetIntegrationInterface $getIntegration;
    private GetAmoCRMApiClientInterface $getAmoCRMApiClient;

    public function __construct(
        GetIntegrationInterface $getIntegration,
        GetAmoCRMApiClientInterface $getAmoCRMApiClient
    ) {
        $this->getIntegration = $getIntegration;
        $this->getAmoCRMApiClient = $getAmoCRMApiClient;
    }

    public function handle(
        ServerRequestInterface $request
    ): ResponseInterface {

        if (!session_status()) {
            session_start();
        }

        $integration_id = $request->getQueryParams()['integration_id'];

        $state = bin2hex(random_bytes(16));

        $_SESSION['oauth2list'] = [
            $state => $integration_id
        ];

        $apiClient = $this->getAmoCRMApiClient
            ->getAmoClient((int) $integration_id);

        $authorizationUrl = $apiClient->getOAuthClient()->getAuthorizeUrl([
            'state' => $state,
            'mode' => 'post_message',
        ]);

        return new RedirectResponse($authorizationUrl);
    }
}
